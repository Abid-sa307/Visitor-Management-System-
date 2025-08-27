<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MultiTenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // If no user is logged in, don't apply tenant filtering
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Super Admin can see everything
        if ($user->role === 'super_admin') {
            return;
        }

        // Always filter by company_id if model has company_id column
        if ($model->getTable() && \Schema::hasColumn($model->getTable(), 'company_id')) {
            $builder->where($model->getTable() . '.company_id', $user->company_id);
        }

        // If the user also has a department restriction AND the model has department_id
        if (!empty($user->department_id) &&
            \Schema::hasColumn($model->getTable(), 'department_id')) {
            $builder->where($model->getTable() . '.department_id', $user->department_id);
        }
    }
}
