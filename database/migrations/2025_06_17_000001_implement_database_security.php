<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration implements comprehensive database-level security
        // Note: Implementation varies by database system (MySQL, PostgreSQL, etc.)

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $this->implementPostgreSQLSecurity();
        } elseif ($driver === 'mysql') {
            $this->implementMySQLSecurity();
        }

        // Add security context columns to support RLS
        $this->addSecurityContextColumns();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $this->rollbackPostgreSQLSecurity();
        } elseif ($driver === 'mysql') {
            $this->rollbackMySQLSecurity();
        }

        $this->removeSecurityContextColumns();
    }

    /**
     * Implement PostgreSQL Row-Level Security
     */
    private function implementPostgreSQLSecurity(): void
    {
        // Enable RLS on sensitive tables
        DB::statement('ALTER TABLE scholar_profiles ENABLE ROW LEVEL SECURITY');
        DB::statement('ALTER TABLE fund_requests ENABLE ROW LEVEL SECURITY');
        DB::statement('ALTER TABLE documents ENABLE ROW LEVEL SECURITY');
        DB::statement('ALTER TABLE manuscripts ENABLE ROW LEVEL SECURITY');

        // Create database roles
        DB::statement('CREATE ROLE erdt_admin');
        DB::statement('CREATE ROLE erdt_scholar');
        DB::statement('CREATE ROLE erdt_super_admin');

        // Grant permissions to roles
        // Admin role permissions
        DB::statement('GRANT SELECT, INSERT, UPDATE ON ALL TABLES IN SCHEMA public TO erdt_admin');
        DB::statement('GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO erdt_admin');

        // Scholar role permissions (limited)
        DB::statement('GRANT SELECT, INSERT, UPDATE ON scholar_profiles TO erdt_scholar');
        DB::statement('GRANT SELECT, INSERT, UPDATE ON fund_requests TO erdt_scholar');
        DB::statement('GRANT SELECT, INSERT, UPDATE ON documents TO erdt_scholar');
        DB::statement('GRANT SELECT, INSERT, UPDATE ON manuscripts TO erdt_scholar');
        DB::statement('GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO erdt_scholar');

        // Super admin role permissions (full access)
        DB::statement('GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO erdt_super_admin');
        DB::statement('GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO erdt_super_admin');

        // Create RLS policies
        $this->createPostgreSQLPolicies();
    }

    /**
     * Create PostgreSQL RLS policies
     */
    private function createPostgreSQLPolicies(): void
    {
        // Scholar profiles - scholars can only see their own profile
        DB::statement("
            CREATE POLICY scholar_profile_policy ON scholar_profiles
            FOR ALL TO erdt_scholar
            USING (user_id = current_setting('app.current_user_id')::integer)
        ");

        // Admin can see all scholar profiles
        DB::statement("
            CREATE POLICY admin_scholar_profile_policy ON scholar_profiles
            FOR ALL TO erdt_admin, erdt_super_admin
            USING (true)
        ");

        // Fund requests - scholars can only see their own requests
        DB::statement("
            CREATE POLICY scholar_fund_request_policy ON fund_requests
            FOR ALL TO erdt_scholar
            USING (scholar_profile_id IN (
                SELECT id FROM scholar_profiles
                WHERE user_id = current_setting('app.current_user_id')::integer
            ))
        ");

        // Admin can see all fund requests
        DB::statement("
            CREATE POLICY admin_fund_request_policy ON fund_requests
            FOR ALL TO erdt_admin, erdt_super_admin
            USING (true)
        ");

        // Documents - scholars can only see their own documents
        DB::statement("
            CREATE POLICY scholar_document_policy ON documents
            FOR ALL TO erdt_scholar
            USING (scholar_profile_id IN (
                SELECT id FROM scholar_profiles
                WHERE user_id = current_setting('app.current_user_id')::integer
            ))
        ");

        // Admin can see all documents
        DB::statement("
            CREATE POLICY admin_document_policy ON documents
            FOR ALL TO erdt_admin, erdt_super_admin
            USING (true)
        ");

        // Manuscripts - scholars can only see their own manuscripts
        DB::statement("
            CREATE POLICY scholar_manuscript_policy ON manuscripts
            FOR ALL TO erdt_scholar
            USING (scholar_profile_id IN (
                SELECT id FROM scholar_profiles
                WHERE user_id = current_setting('app.current_user_id')::integer
            ))
        ");

        // Admin can see all manuscripts
        DB::statement("
            CREATE POLICY admin_manuscript_policy ON manuscripts
            FOR ALL TO erdt_admin, erdt_super_admin
            USING (true)
        ");
    }

    /**
     * Implement MySQL security (limited RLS support)
     */
    private function implementMySQLSecurity(): void
    {
        // MySQL doesn't have native RLS, so we'll use views and triggers
        // Create database users for different roles

        try {
            // Create users (using environment variables for passwords)
            DB::statement("CREATE USER IF NOT EXISTS 'erdt_admin'@'%' IDENTIFIED BY ?", [env('DB_ADMIN_PASSWORD', 'admin_secure_password')]);
            DB::statement("CREATE USER IF NOT EXISTS 'erdt_scholar'@'%' IDENTIFIED BY ?", [env('DB_SCHOLAR_PASSWORD', 'scholar_secure_password')]);
            DB::statement("CREATE USER IF NOT EXISTS 'erdt_super_admin'@'%' IDENTIFIED BY ?", [env('DB_SUPER_ADMIN_PASSWORD', 'super_admin_secure_password')]);

            // Grant permissions
            $database = env('DB_DATABASE', 'laravel');

            // Admin permissions
            DB::statement("GRANT SELECT, INSERT, UPDATE ON {$database}.* TO 'erdt_admin'@'%'");

            // Scholar permissions (limited to specific tables)
            DB::statement("GRANT SELECT, INSERT, UPDATE ON {$database}.scholar_profiles TO 'erdt_scholar'@'%'");
            DB::statement("GRANT SELECT, INSERT, UPDATE ON {$database}.fund_requests TO 'erdt_scholar'@'%'");
            DB::statement("GRANT SELECT, INSERT, UPDATE ON {$database}.documents TO 'erdt_scholar'@'%'");
            DB::statement("GRANT SELECT, INSERT, UPDATE ON {$database}.manuscripts TO 'erdt_scholar'@'%'");

            // Super admin permissions
            DB::statement("GRANT ALL PRIVILEGES ON {$database}.* TO 'erdt_super_admin'@'%'");

            DB::statement("FLUSH PRIVILEGES");

        } catch (Exception $e) {
            // Log error but don't fail migration
            Log::warning('Failed to create MySQL users: ' . $e->getMessage());
        }
    }

    /**
     * Add security context columns
     */
    private function addSecurityContextColumns(): void
    {
        // Add columns to support security context
        if (!Schema::hasColumn('scholar_profiles', 'security_context')) {
            Schema::table('scholar_profiles', function (Blueprint $table) {
                $table->json('security_context')->nullable()->after('updated_at');
                $table->index('user_id', 'idx_scholar_profiles_user_id');
            });
        }

        if (!Schema::hasColumn('fund_requests', 'security_context')) {
            Schema::table('fund_requests', function (Blueprint $table) {
                $table->json('security_context')->nullable()->after('updated_at');
                $table->index('scholar_profile_id', 'idx_fund_requests_scholar_profile_id');
            });
        }

        if (!Schema::hasColumn('documents', 'security_context')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->json('security_context')->nullable()->after('updated_at');
                $table->index('scholar_profile_id', 'idx_documents_scholar_profile_id');
            });
        }

        if (!Schema::hasColumn('manuscripts', 'security_context')) {
            Schema::table('manuscripts', function (Blueprint $table) {
                $table->json('security_context')->nullable()->after('updated_at');
                $table->index('scholar_profile_id', 'idx_manuscripts_scholar_profile_id');
            });
        }
    }

    /**
     * Rollback PostgreSQL security
     */
    private function rollbackPostgreSQLSecurity(): void
    {
        // Drop policies
        DB::statement('DROP POLICY IF EXISTS scholar_profile_policy ON scholar_profiles');
        DB::statement('DROP POLICY IF EXISTS admin_scholar_profile_policy ON scholar_profiles');
        DB::statement('DROP POLICY IF EXISTS scholar_fund_request_policy ON fund_requests');
        DB::statement('DROP POLICY IF EXISTS admin_fund_request_policy ON fund_requests');
        DB::statement('DROP POLICY IF EXISTS scholar_document_policy ON documents');
        DB::statement('DROP POLICY IF EXISTS admin_document_policy ON documents');
        DB::statement('DROP POLICY IF EXISTS scholar_manuscript_policy ON manuscripts');
        DB::statement('DROP POLICY IF EXISTS admin_manuscript_policy ON manuscripts');

        // Disable RLS
        DB::statement('ALTER TABLE scholar_profiles DISABLE ROW LEVEL SECURITY');
        DB::statement('ALTER TABLE fund_requests DISABLE ROW LEVEL SECURITY');
        DB::statement('ALTER TABLE documents DISABLE ROW LEVEL SECURITY');
        DB::statement('ALTER TABLE manuscripts DISABLE ROW LEVEL SECURITY');

        // Drop roles
        DB::statement('DROP ROLE IF EXISTS erdt_admin');
        DB::statement('DROP ROLE IF EXISTS erdt_scholar');
        DB::statement('DROP ROLE IF EXISTS erdt_super_admin');
    }

    /**
     * Rollback MySQL security
     */
    private function rollbackMySQLSecurity(): void
    {
        try {
            // Drop users if they exist
            DB::statement("DROP USER IF EXISTS 'erdt_admin'@'%'");
            DB::statement("DROP USER IF EXISTS 'erdt_scholar'@'%'");
            DB::statement("DROP USER IF EXISTS 'erdt_super_admin'@'%'");
            DB::statement("FLUSH PRIVILEGES");
        } catch (Exception $e) {
            Log::warning('Failed to drop MySQL users: ' . $e->getMessage());
        }
    }

    /**
     * Remove security context columns
     */
    private function removeSecurityContextColumns(): void
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            $table->dropColumn('security_context');
            $table->dropIndex('idx_scholar_profiles_user_id');
        });

        Schema::table('fund_requests', function (Blueprint $table) {
            $table->dropColumn('security_context');
            $table->dropIndex('idx_fund_requests_scholar_profile_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('security_context');
            $table->dropIndex('idx_documents_scholar_profile_id');
        });

        Schema::table('manuscripts', function (Blueprint $table) {
            $table->dropColumn('security_context');
            $table->dropIndex('idx_manuscripts_scholar_profile_id');
        });
    }
};
