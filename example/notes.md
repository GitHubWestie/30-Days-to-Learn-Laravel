# Forms and CSRF Explained
For a user to add a job to the jobsboard, or perhaos for a user to apply for a job on the jobsboard the app will need some forms. In `web.php` add a new route. Following convention will mean that this route is named `/jobs/create`.

```php
Route::get('/jobs/create', function() {
    return view('create');
})
```
***Note:***  *When adding this route make sure it goes ABOVE the `/jobs/{id}` route*

If this is added below a wildcard route then Laravel will try and interpret it as the wildcard value. Placing it above the wildcard route prevents this. 

## Getting organised
It's time to start thinking of our jobs related data and pages as a `resource`. Another common convention is to have a resource directory. In this case that would be a directory called jobs. Within that directory it is assumed that everything belongs to the job resource so files are also named conventionally. For example the page responsible for getting all of the jobs would be call `index`, the page responsible for showing a single job would be called `show`.

- Create the jobs directory
    Move all views related to the jobs resource into that folder. Create a new view for the jobs/create page. This will be empty for now.

- Update the existing routes.
    Simply change the path given to the view method to match the new path and filename e.g. `view('jobs.index')` This uses a `.` separator for the file path as it is more conventional but `/` will work as well.

- Create the new view
    Create the create.blade.php Grab a free form template from [tailwindUI](https://tailwindcss.com/plus/ui-blocks/application-ui/forms/form-layouts)

## More conventions
Currently the form doesnt have a route to submit to so any data submitted goes nowhere. Coventionally when  submitting a form for creating a new resource it submits to the resource which in this case is `/jobs`.

```html
<form method="POST" action="/jobs">
```
```php
Route::post('/jobs', function () {
    // Something happens here
});
```

## CSRF
Submitting this form as it is will trigger a 419 error from Laravel and give absolutely no explanation of why or what it means. This is because of `CSRF` or `Cross Site Request Forgery` protection.

To fix this the blade directive `@csrf` needs to be added at the top of the form.

```html
<form method="POST" action="/jobs">
    @csrf
```

At runtime the `@csrf` will compile down into a hidden form input that adds unique token to the form. If this token matches the session token stored in the browser then Laravel deems the request to be safe as it has originated from the same site and the POST request will be able to continue.

## Now what?
Well, now that Laravel isnt blocking the `request` the data inside it can be accessed using the `request()` helper method. This helper can grab all or some of the data in the request using `request()->all()` or `request('title')` for example.

With that the create method can be called on the `Job class` to add the new job to the database. Almost.


```php
Route::post('/jobs', function () {
    // validation...

    Job::create([
        'title' => request('title'),
        'salary' => request('salary'),
        'employer_id' => 1 // Temporarily hardcoded
    ]);

    return redirect('/jobs');
});
```

At this point there is no authentication in place so the `emplyoer_id` will need to be hardcoded. Because of this when trying to create the job Laravel will throw an SQL error. This is because the employer_id field cannot be mass assigned and therefore is ignored even though it's in the create() method array. To get around this the employer_id field can be added to the `$fillable` array in the Job model.

Alternatively the mass assignment protection be disabled. This is a point of contention among developers but the `$fillable` array can be substituted for the `$guarded` array. This explicitly tells Laravel which attributes *should* be guarded and when set to an `empty array` Laravel will allow everything. This does require doing on each model but is much less hassle than forever adding $fillable properites.

Another alternative is to actually disable the feature completely across the entire app. In the AppServiceProvider add this line to the boot() method
```php
Model::unguard();
```

