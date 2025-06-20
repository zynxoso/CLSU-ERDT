<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ScholarAccessScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if (!$user) {
            // If no user is authenticated, deny all access
            $builder->whereRaw('1 = 0');
            return;
        }

        // Super admins have full access
        if ($user->role === 'super_admin') {
            return;
        }

        // Admins have full access
        if ($user->role === 'admin') {
            return;
        }

        // Scholars can only access their own data
        if ($user->role === 'scholar') {
            $this->applyScholarRestrictions($builder, $model, $user);
            return;
        }

        // Default: deny access for unknown roles
        $builder->whereRaw('1 = 0');
    }

    /**
     * Apply scholar-specific restrictions
     */
    private function applyScholarRestrictions(Builder $builder, Model $model, $user): void
    {
        $tableName = $model->getTable();

        switch ($tableName) {
            case 'scholar_profiles':
                $builder->where('user_id', $user->id);
                break;

            case 'fund_requests':
            case 'documents':
            case 'manuscripts':
                if ($user->scholarProfile) {
                    $builder->where('scholar_profile_id', $user->scholarProfile->id);
                } else {
                    // No scholar profile means no access
                    $builder->whereRaw('1 = 0');
                }
                break;

            default:
                // For unknown tables, deny access
                $builder->whereRaw('1 = 0');
                break;
        }
    }
}
