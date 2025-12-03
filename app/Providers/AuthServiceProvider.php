<?php

namespace App\Providers;

use App\Models\Company;
use App\Policies\CompanyPolicy;
use App\Policies\VisitorPolicy;
use App\Policies\VisitorCategoryPolicy;
use App\Policies\QRManagementPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Company::class => CompanyPolicy::class,
        Visitor::class => VisitorPolicy::class,
        VisitorCategory::class => VisitorCategoryPolicy::class,
        \App\Http\Controllers\QRManagementController::class => QRManagementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
