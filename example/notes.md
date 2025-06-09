# Autoloading, Namespaces, and Models

The duplication of the jobs array needs to be removed from the routes file. This will be done incrementally to better illustrate the process. To begin remove the arrays from the routes and place one array at the root of the file. Then update the route functions to `use()` the `$jobs` array.

**web.php**
```php
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

$jobs = [
    [
        'id' => 1,
        'title' => 'Director',
        'salary' => '$50,000',
    ],
    [   
        'id' => 2,
        'title' => 'Programmer',
        'salary' => '$10,000',
    ],
    [
        'id' => 3,
        'title' => 'Teacher',
        'salary' => '$40,000',
    ],
];

Route::get('/jobs', function () use ($jobs) {
    return view('jobs', [
        'jobs' => $jobs,
    ]);
});

Route::get('/jobs/{id}', function ($id) use ($jobs) {
    
    // Returns the first job that matches the given $id
    $job = Arr::first($jobs, fn($job)=> $job['id'] == $id);
    
    return view('job', [
        'job' => $job,
    ]);
});
```

The array can then be moved into a class where a method can be defined to return all of the jobs.

```php
class Jobs
{
    public static function all() 
    {
        return [
            [
                'id' => 1,
                'title' => 'Director',
                'salary' => '$50,000',
            ],
            [   
                'id' => 2,
                'title' => 'Programmer',
                'salary' => '$10,000',
            ],
            [
                'id' => 3,
                'title' => 'Teacher',
                'salary' => '$40,000',
            ],
        ];
    }
}

Route::get('/jobs', function () {
    return view('jobs', [
        'jobs' => Jobs::all(),
    ]);
});

Route::get('/jobs/{id}', function ($id) {
    
    // Returns the first job that matches the given $id
    $job = Arr::first(Jobs::all(), fn($job)=> $job['id'] == $id);
    
    return view('job', [
        'job' => $job,
    ]);
});

```

But it doesnt really make sense for a class to live inside the routes file. So this should be extracted into it's own file, inside the models directory. The choice to put it in the models directory is not an arbitrary one. Laravel, like many other frameworks implements an MVC (Model, View, Controller) architecture. The Model element is where business logic lives and is therefore a suitable home for the Jobs class.

**app/Models/Jobs.php**
```php
namespace App\Models;

class Jobs
{
    public static function all() 
    {
        return [
            [
                'id' => 1,
                'title' => 'Director',
                'salary' => '$50,000',
            ],
            [   
                'id' => 2,
                'title' => 'Programmer',
                'salary' => '$10,000',
            ],
            [
                'id' => 3,
                'title' => 'Teacher',
                'salary' => '$40,000',
            ],
        ];
    }
}
```

Then all that is required in web.php is to `use` class. This allows the same access to the models methods as when it was declared in web.php.

**web.php**
```php
use App\Models\Jobs;
```

### A note on Namespaces
Namespaces are essentially a way of organising directories, files, classes etc. The `Jobs` class is not unique and other Jobs classes already exist within Laravel. A `namespace` tells Laravel explicitly *which* Jobs class is required.

## Back to Business (Logic)
The Jobs class also allows the flexibility to tuck complicated logic away and make it more user friendly. For example the logic responsible for getting a single job can now be rolled up into a method like `find()`

```php
public static function find(int $id)
{
    // Returns the first job that matches the given $id
    $jobs = Arr::first(Jobs::all(), fn($job)=> $job['id'] == $id);

    if(!$job) {
        abort(404);
    }

    return $job;
}
```

Making the logic much cleaner in web.php
```php
Route::get('/jobs/{id}', function ($id) {
    
    $job = Jobs::find($id);
    
    return view('job', [
        'job' => $job,
    ]);
});
```