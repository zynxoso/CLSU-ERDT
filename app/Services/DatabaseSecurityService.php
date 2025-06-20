<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class DatabaseSecurityService
{
    /**
     * Set database security context for the current user
     *
     * @param User|null $user
     * @return void
     */
    public function setSecurityContext(?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $this->setPostgreSQLContext($user);
        } elseif ($driver === 'mysql') {
            $this->setMySQLContext($user);
        }
    }

    /**
     * Set PostgreSQL security context
     *
     * @param User $user
     * @return void
     */
    private function setPostgreSQLContext(User $user): void
    {
        try {
            // Set current user ID for RLS policies
            DB::statement("SELECT set_config('app.current_user_id', ?, false)", [$user->id]);
            DB::statement("SELECT set_config('app.current_user_role', ?, false)", [$user->role]);

            // Switch to appropriate database role
            $role = $this->mapUserRoleToDBRole($user->role);
            if ($role) {
                DB::statement("SET ROLE ?", [$role]);
            }

        } catch (\Exception $e) {
            Log::warning('Failed to set PostgreSQL security context: ' . $e->getMessage());
        }
    }

    /**
     * Set MySQL security context (limited support)
     *
     * @param User $user
     * @return void
     */
    private function setMySQLContext(User $user): void
    {
        try {
            // Set session variables for custom security logic
            DB::statement("SET @current_user_id = ?", [$user->id]);
            DB::statement("SET @current_user_role = ?", [$user->role]);

        } catch (\Exception $e) {
            Log::warning('Failed to set MySQL security context: ' . $e->getMessage());
        }
    }

    /**
     * Switch database connection based on user role
     *
     * @param User $user
     * @return void
     */
    public function switchDatabaseConnection(User $user): void
    {
        $connectionName = $this->getConnectionForRole($user->role);

        if ($connectionName && Config::has("database.connections.{$connectionName}")) {
            config(['database.default' => $connectionName]);
            DB::purge($connectionName);
            DB::reconnect($connectionName);

            Log::info('Switched database connection', [
                'user_id' => $user->id,
                'role' => $user->role,
                'connection' => $connectionName
            ]);
        }
    }

    /**
     * Map Laravel user role to database role
     *
     * @param string $userRole
     * @return string|null
     */
    private function mapUserRoleToDBRole(string $userRole): ?string
    {
        return match($userRole) {
            'super_admin' => 'erdt_super_admin',
            'admin' => 'erdt_admin',
            'scholar' => 'erdt_scholar',
            default => null,
        };
    }

    /**
     * Get database connection name for role
     *
     * @param string $role
     * @return string|null
     */
    private function getConnectionForRole(string $role): ?string
    {
        return match($role) {
            'super_admin' => 'mysql_super_admin',
            'admin' => 'mysql_admin',
            'scholar' => 'mysql_scholar',
            default => null,
        };
    }

    /**
     * Create security audit trail
     *
     * @param string $action
     * @param array $data
     * @return void
     */
    public function auditSecurityAction(string $action, array $data = []): void
    {
        $user = Auth::user();

        Log::info('Database security action', [
            'action' => $action,
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
            'data' => $data
        ]);
    }

    /**
     * Validate user access to specific records
     *
     * @param User $user
     * @param string $model
     * @param int $recordId
     * @return bool
     */
    public function validateRecordAccess(User $user, string $model, int $recordId): bool
    {
        switch ($model) {
            case 'FundRequest':
                return $this->validateFundRequestAccess($user, $recordId);
            case 'Document':
                return $this->validateDocumentAccess($user, $recordId);
            case 'Manuscript':
                return $this->validateManuscriptAccess($user, $recordId);
            case 'ScholarProfile':
                return $this->validateScholarProfileAccess($user, $recordId);
            default:
                return false;
        }
    }

    /**
     * Validate fund request access
     */
    private function validateFundRequestAccess(User $user, int $recordId): bool
    {
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'scholar' && $user->scholarProfile) {
            $fundRequest = DB::table('fund_requests')
                ->where('id', $recordId)
                ->where('scholar_profile_id', $user->scholarProfile->id)
                ->exists();

            return $fundRequest;
        }

        return false;
    }

    /**
     * Validate document access
     */
    private function validateDocumentAccess(User $user, int $recordId): bool
    {
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'scholar' && $user->scholarProfile) {
            $document = DB::table('documents')
                ->where('id', $recordId)
                ->where('scholar_profile_id', $user->scholarProfile->id)
                ->exists();

            return $document;
        }

        return false;
    }

    /**
     * Validate manuscript access
     */
    private function validateManuscriptAccess(User $user, int $recordId): bool
    {
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'scholar' && $user->scholarProfile) {
            $manuscript = DB::table('manuscripts')
                ->where('id', $recordId)
                ->where('scholar_profile_id', $user->scholarProfile->id)
                ->exists();

            return $manuscript;
        }

        return false;
    }

    /**
     * Validate scholar profile access
     */
    private function validateScholarProfileAccess(User $user, int $recordId): bool
    {
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'scholar') {
            return $user->scholarProfile && $user->scholarProfile->id === $recordId;
        }

        return false;
    }

    /**
     * Reset security context
     *
     * @return void
     */
    public function resetSecurityContext(): void
    {
        $driver = DB::connection()->getDriverName();

        try {
            if ($driver === 'pgsql') {
                DB::statement("RESET ROLE");
                DB::statement("SELECT set_config('app.current_user_id', NULL, false)");
                DB::statement("SELECT set_config('app.current_user_role', NULL, false)");
            } elseif ($driver === 'mysql') {
                DB::statement("SET @current_user_id = NULL");
                DB::statement("SET @current_user_role = NULL");
            }
        } catch (\Exception $e) {
            Log::warning('Failed to reset security context: ' . $e->getMessage());
        }
    }
}
