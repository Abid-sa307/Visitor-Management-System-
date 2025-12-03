<?php

namespace App\Contracts;

interface AuthenticatableUser
{
    public function hasRole($role);
    public function getCompanyId();
}
