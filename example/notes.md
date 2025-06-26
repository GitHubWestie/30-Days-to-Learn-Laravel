# Routes Reloaded - 6 Essential Tips

## Route Model Binding
Route model binding leverages more of Laravels auto magic to do stuff. Currently there are a few routes in the routes file that look similar to this:
```php
// Show
Route::get('/jobs/{id}', function ($id) {

    $job = Job::find($id);

    return view('jobs/show', [
        'job' => $job,
    ]);
});
```

To use route model binding the route can be updated like this:
```php
// Show
Route::get('/jobs/{job}', function (Job $job) { // Wildcard replaced with more generic reference

    // No need to fetch the job

    return view('jobs/show', [
        'job' => $job,
    ]);
});
```

### Key points:

* The route parameter name ({job}) must match the variable name in the closure.
* Laravel uses the model’s primary key (id by default) to fetch the record.
* You can specify a different column (e.g., slug) by adding :slug to the route parameter.

## Controller Classes
Make a controller with `php artisan make:controller` OR `php artisan make:controller JobController`. If the name is omitted in the make command Laravel will prompt for further information, one of which being the controller type. In this case empty is fine.

With the controller created the routes can now defer to the controller for any logic that needs to be executed. For example the jobs index route becomes:
```php
Route::get('/jobs', [JobController::class, 'index']);
```

And the controller holds the same logic that the route used to. But now the responsibility of each is clear. Routes do route things and controllers do controller things.
```php
    public function index()
    {
        $jobs = Job::with('employer')->latest()->simplePaginate(3);

        return view('jobs/index', [
            'jobs' => $jobs,
        ]);
    }
```

## Route::view()
Often there will be routes that do nothing other than get a view and return it. For these routes the `Route::view()` shorthand provided by Laravel can be used to replace a more verbose route definition.
```php
// Standard route definition
Route::get('/', function () {
    return view('home');
});

// Using Route shorthand
Route::view('/', 'home');
```

## List Your Routes
A useful tool is artisans route:list. This will list all routes in the project including ones used by vendor packages. The routes for vendor packages can be omitted by using the --except-vendor flag
```php
// All routes
php artisan route:list

// Without vendor routes
php artisan route:list --except-vendor
```
This will output something like this in the terminal, showing the request type, uri and even the controller and method that the route points to
```bash
  POST       jobs .......................................................................... JobController@store  
  GET|HEAD   jobs/create ................................................................... JobController@create  
  GET|HEAD   jobs/{job} .................................................................... JobController@show  
  PATCH      jobs/{job} .................................................................... JobController@update  
  DELETE     jobs/{job} .................................................................... JobController@destroy  
  GET|HEAD   jobs/{job}/edit ............................................................... JobController@edit
```

## Route Groups
Routes can also be grouped which is useful when dealing with resources with many routes calling the same controller repeatedly. Using a route group allows the controller to be specified once and then all routes within that group will use that controller by default. 
```php
// Job routes
Route::controller(JobController::class)->group(function () {
    Route::get('/jobs', 'index');
    Route::get('/jobs/create', 'create');
    Route::get('/jobs/{job}', 'show');
    Route::post('/jobs', 'store');
    Route::get('/jobs/{job}/edit', 'edit');
    Route::patch('/jobs/{job}', 'update');
    Route::delete('/jobs/{job}', 'destroy');
});
```

## Route Resources
Even more helpful when dealing with resources is Laravels dedicated resource helper. This will almost automatically create all of the routes. It's 'almost' automatic because it does require following RESTful conventions but as long as they have been followed the routes for a resource can be as simple as this:

```php
Route::resource('jobs', JobController::class);
```

A third argument can also be provided as an array where we can specify which routes are required or not required.

```php
Route::resource('jobs', JobController::class, [
    'only' => ['index', 'show']
]);

// Or

Route::resource('jobs', JobController::class, [
    'except' => ['destroy', 'update']
]);
```