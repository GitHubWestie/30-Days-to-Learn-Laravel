# View Data and Route Wildcards

In addition to just fetching the required view, the view helper function used by the Route can also handle data to be passed to the view.

```php
Route::get('/', function () {
    return view('welcome', [
        'greeting' => 'Hello world! 🌍',
    ]);
});
```

Now, when the welcome view is loaded it will also have access to a variable `$greeting`. This can of course be used in the templates.

**welcome.blade.php**
```php
<h1>{{ $greeting }}</h1>
```

Obviously this can take much more data than just a simple greeting. For example if it were a jobs board it might have something like this.

```php
Route::get('/jobs', function () {
    return view('jobs', [
        'jobs' => [
            [
                'title' => 'Director',
                'salary' => '$50,000',
            ],
            [
                'title' => 'Programmer',
                'salary' => '$10,000',
            ],
            [
                'title' => 'Teacher',
                'salary' => '$40,000',
            ],
        ]
    ]);
});
```

The `$jobs` array would then be available to the view, where it could be looped over using the blade `@foreach` directive to extract the data for each available job. 