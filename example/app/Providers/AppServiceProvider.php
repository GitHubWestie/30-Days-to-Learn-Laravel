<?php

namespace App\Providers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading();

        // Defines a new gate that determines if the current user has permission to edit a job. Returns a boolean.
        // Gate::define('edit-job', function (User $user, Job $job) :bool
        // {
        //     return $job->employer->user->is($user);
        // });

        // Model::unguard();
        // Paginator::useBootstrapFive(); Just an example of changing the default
    }
}
