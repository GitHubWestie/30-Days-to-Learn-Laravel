# 6 Steps to Authorization Mastery

## Step 1: Establishing Relationships for Authorization
To perform user authorization on a job, there must be a relationship between the job and a user. Currently, jobs relate to employers, but employers don’t relate to users. Fix this by adding a foreign key user_id to the employers table and updating the Employer factory to associate an employer with a user.

```php
// create_employers_migration_table.php
$table->foreignIdFor(User::class);

// EmployerFactory.php
'user_id' => User::factory(),
```

## Step 2: Inline Authorization in Controller
Add a simple authorization check in your job controller’s edit action:

* Redirect guests to login.
    ```php
    if (Auth::guest()) {
        return redirect('/login');
    }
    ```

* Check if the authenticated user is responsible for the job.
    ```php
    if ($job->employer->user->isNot(Auth::user())) {
        abort(403);
    }
    ```
    *The `is()` and `isNot()` allow for quick comparison to check if two models have the same id and belong to the same table.*

    Rather than displaying a 403 forbidden page to the user if they're not allowed to edit a job, it would be far better UX to not display the edit button at all. The only issue with that though is that the logic to determine whether a user should see that button is stuck inside the edit function. The `Gate facade` can help solve this:

* Extract the logic to a `Gate`
    ```php
    // Define the gate function
    Gate::define('edit-job', function (User $user, Job $job) {
        return $job->employer->user->is($user);
    });

    // Implement the gate
    Gate::authorize('edit-job', $job);
    ```
    *This now uses `is()` to return a boolean value*

    By default `Gate::authorize` will return a `403` if the gate evaluates to `false`. This can be overridden by using `Gate::allows()` or `Gate::denies()`. Then a custom closure can be written to execute specific logic.

## Step 3: Define Gates Inside AppService Provider
Because the Gate is defined within the edit method of the controller it is only available when that specific route is accessed. To make it available to the entire app the definition needs to be moved to the `AppServiceProvider` and placed inside the `boot()` method.

* Move gate to AppServiceProvider
    ```php
    // Define the gate function
    Gate::define('edit-job', function (User $user, Job $job) {
        return $job->employer->user->is($user);
    });
    ```
    *Note that user will ALWAYS be the current user. If there is no user the gate returns false without reaching the closure logic. If this is undesirable the user can be given a default of null `User $user = null` or made optional `?User $user`*

## Step 4: Using can and cannot Methods
Laravel models have `can` and `cannot` methods to check permissions against gates. Use these in controllers or Blade views to conditionally allow actions.
```php
// In controller
if ($user->can(Gate::authorize('edit-job', $job))
    // Custom logic here...
);

// Or in Blade template
@can('edit-job', $job)
<div class="mt-6">
    <x-button href="/jobs/{{ $job['id'] }}/edit">Edit Job</x-button>
</div>
@endcan
```

## Step 5: Middleware Authorization
Apply authorization at the route level using middleware:

```php
Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->middleware('auth')->can('edit-job', 'job');
```
This ensures users are authenticated and authorized before accessing the route and is the preferred method for many developers.

## Step 6: Policies
`Policies` are similar to `gates`. They simply define a set of rules that can be used to determine various things. In this case we'll create a JobPolicy that contains an `edit` method. This will execute the same logic as the Gate and can be applied in the same way.

* Create the Policy
    ```
    php artisan make:policy
    ```
    Laravel will prompt for a name fore the policy and a model to link to

* Laravel may pre-populate the policy with functions. These are just suggestions and can be deleted.
    ```php
    public function edit(User $user, Job $job) :bool
    {
        return $job->employer->user->is($user);
    }
    ```

* Update middleware and blade template to reference the new policy method
    ```php
    Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->middlewar('auth')->can('edit', 'job');

    @can('edit', $job)
    <div class="mt-6">
        <x-button href="/jobs/{{ $job['id'] }}/edit">Edit Job</x-button>
    </div>
    @endcan
    ```

As a general rule of thumb, for small projects Gates are fine but for anything larger, reach for policies.