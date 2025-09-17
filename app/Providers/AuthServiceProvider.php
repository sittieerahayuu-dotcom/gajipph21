<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

        $this->registerPolicies();

        // Staff Penggajian (role_id = 1) bisa tambah/edit/hapus
        Gate::define('isStaffPenggajian', function ($user) {
            return $user->role == 1;
        });

        // Direktur (role_id = 2) hanya bisa melihat data
        Gate::define('isDirektur', function ($user) {
            return $user->role == 2;
        });

        // Karyawan (role_id = 3) hanya bisa melihat data pribadinya
        Gate::define('isKaryawan', function ($user) {
            return $user->role == 3;
        });
    }
}
