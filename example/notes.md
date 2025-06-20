14.-All-You-Need-to-Know-About-Pagination
# All You Need to Know About Pagination


At the moment the jobs route is getting all of the jobs from the database in one query and dumping them all into memory when the page is requested.

```php
Route::get('/jobs', function () {
    $jobs = Job::with('employer')->get();

    return view('jobs', [
        'jobs' => $jobs,
    ]);
});
```

That's ok while there's only a small number of jobs in the database but if there were hundreds or even thousands like there would be in real life this would have huge overhead and cause the app to be extremely slow. Pagination allows the results to be broken up into managable chunks.

## Pagination
Laravel makes paginating query results easy.

```php
Route::get('/jobs', function () {
    $jobs = Job::with('employer')->paginate(3); // The number tells Laravel how many results to display per page

    return view('jobs', [
        'jobs' => $jobs,
    ]);
});
```

Adding the pagination to the view is equally as simple.

```php
// jobs.blade.php
<div>{{ $jobs->links() }}</div>
```

Functionally, that's it. Done. And even aesthetically, if using Tailwind that's also done as the paginator comes with Tailwind classes baked in.

## My shit is custom
If the styles arent desirable they can be changed. By default they live in the vendor directory. To update the styles we'll need a copy in our project.

```php
php artisan vendor:publish
```

Running this command will bring up a list of all vendor packages. In this case look for `tag:laravel-pagination` and enter the associated number into the command line. The files will be copied and placed in their own directory within the views folder.

Inside that directory are many files for various css frameworks. If another framework was preferable then this can be configured in AppServiceProvider just like the lazy loading option.

```php
    public function boot(): void
    {
        Model::preventLazyLoading();

        Paginator::useBootstrapFive();
        // Paginator::defaultView(''); // Control the pagination entirely through a view
    }
```

In our case we want to edit the `tailwind.blade.php` file.

## Pagination Options
Even using pagination the queries will still introduce significant overhead with enough results being pulled in. Another option is `simplePaginate()`. This replaces the pages nav with simple previous and next buttons instead that will simply navigate through the paginated results one page at a time.

Another option is `cursor based pagination` by using `cursorPaginate()`. This is the most performant option of the three choices but it does come with a small caveat. The url generated by cursor based pagination is like a random uuid and relates specifically to the data being fetched. This wont be suitable for all applications such as if the url was to be used to get a query string for some reason or something similar. But it's a useful tool when it suits the situation.

