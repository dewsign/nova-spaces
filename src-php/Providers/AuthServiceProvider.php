<?php

namespace Dewsign\NovaSpaces\Providers;

use Illuminate\Support\Facades\Gate;
use Dewsign\NovaSpaces\Models\Space;
use Dewsign\NovaSpaces\Policies\SpacePolicy;
use Silvanite\Brandenburg\Traits\ValidatesPermissions;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use ValidatesPermissions;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Space::class => SpacePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        collect([
            'viewSpace',
            'manageSpace',
        ])->each(function ($permission) {
            Gate::define($permission, function ($user) use ($permission) {
                if ($this->nobodyHasAccess($permission)) {
                    return true;
                }

                return $user->hasRoleWithPermission($permission);
            });
        });

        $this->registerPolicies();
    }
}
