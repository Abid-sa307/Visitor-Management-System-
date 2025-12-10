<?php

namespace App\Policies;

use App\Contracts\AuthenticatableUser as User;
use App\Models\VisitorCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow superadmins and company users to view visitor categories
        return $user->hasRole('superadmin') || $user->hasRole('company');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VisitorCategory $visitorCategory): bool
    {
        // Superadmins can view all, company users can only view their own
        return $user->hasRole('superadmin') || 
               ($user->hasRole('company') && $user->company_id === $visitorCategory->company_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow superadmins and company users to create visitor categories
        // Also check if the user has the specific permission if using permissions
        return $user->hasRole('superadmin') || 
               $user->hasRole('company') || 
               $user->can('create_visitor_categories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VisitorCategory $visitorCategory): bool
    {
        // Superadmins can update all, company users can only update their own
        return $user->hasRole('superadmin') || 
               ($user->hasRole('company') && $user->company_id === $visitorCategory->company_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VisitorCategory $visitorCategory): bool
    {
        // Only allow delete if no visitors are using this category
        if ($visitorCategory->visitors()->count() > 0) {
            return false;
        }
        
        // Superadmins can delete all, company users can only delete their own
        return $user->hasRole('superadmin') || 
               ($user->hasRole('company') && $user->company_id === $visitorCategory->company_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VisitorCategory $visitorCategory): bool
    {
        return $user->hasRole('superadmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VisitorCategory $visitorCategory): bool
    {
        return $user->hasRole('superadmin');
    }
}
