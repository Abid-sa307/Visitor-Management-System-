<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class QRManagementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        // Allow both regular users and company users to view the QR scanner
        if ($user instanceof CompanyUser) {
            return in_array('qr_scanner', $user->master_pages ?? [], true);
        }
        
        // For regular users, check if they have the QR scanner permission
        if (method_exists($user, 'hasMasterPageAccess')) {
            return $user->hasMasterPageAccess('qr_scanner');
        }
        
        return false;
    }
}
