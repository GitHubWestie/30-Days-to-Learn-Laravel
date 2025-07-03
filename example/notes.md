# Queues Are Easier Than You Think
At the moment actions are happening synchronously inside the application. This means that when the email is sent the user has to wait for it to be sent. Just sitting there, staring at the screen, waiting...

Queues all jobs to be deferred or shooed away to the background so the user can carry on doing whatever it is they're doing and things like sending emails can happen in the background as and when they're ready.

## Queue Configuration
The queue config is found in the config directory. Similar to the email config it contains several options, many of which pull a variable from the `.env` file or fallback to a default.

```php
'default' => env('QUEUE_CONNECTION', 'database'),
```

Laravel supports multiple queue drivers:

* sync: Runs jobs synchronously (default, useful for local development).
* database: Stores jobs in a database table.
* beanstalkd, SQS, Redis: More robust queue backends.

The default is `database` which incidentally is why our jobs table had to be called jobListings. Laravel had already created a jobs table for using with the database driver.

## Adding to the queue
To send something to the queue the `queue()` method is used. In the email example the `send()` method is simply swapped out for `queue()`.
```php
Mail::to($user)->queue(new JobPosted($job));
```
However, nothing will happen without a `worker` to pcik up the job! In the terminal run `php artisan queue:work`. You can think of this as summoning a worker to do their job. While this is running the worker will constantly monitor the queue for jobs and handle them as they come in. Without a worker the queue will just stack up.

The `dispatch()` method can also be invoked for queues. This is called a `queued closure` and can have other methods chained onto it like `delay()` which will delay the job from being processed. Useful for things like sending a welocme email after a specific amount of time has passed.

```php
Route::('/test', function () {
    dispatch(function () {
        logger('Hello from the queue!');
    });

    return 'Done!';
})
```

## Dedicated Job Classes
Think queue jobs *not* the job listings jobs!

Imagine a scenario wherean employer posts a job and it needs to be translated into multiple languages using AI. A dedicated job class could be used for this. In the terminal run `php artisan make:job` and give the job a name.

The job class will be created in a new `Jobs` directory within the `app` directory.

The class can then be used like any other class so the test route can be updated like this:
```php
Route::get('/test', function () {

    App\Jobs\TranslateJob::dispatch();
    
});
```
*Job classes have their own `dispatch()` method so there is no need for the queue closure*

So now, in the context of the example scenario (translating a job to multiple languages) it would make sense to send a job listing through to be translated.

```php
Route::get('/test', function () {

    $jobListing = Job::first();

    TranlateJob::dispatch($jobListing);

});
```

Then in `TranslateJob.php`
```php
public function __construct(public Job $jobListing)
{
    //
}

public function handle()
{
    // Fake API call to translate class might look like this
    AI::translate($jobListing, 'Spanish');

    // To get some sort of output
    logger('Translating ' . $this->jobListing->title . ' to Spanish...');
}
```

Ultimately the goal of queues is to do things that the user doesn't need to wait around for that might take a while. For example if you went to a shop to have some photos developed. You wouldn't stand and wait at the counter while they were done. You'd hand your photos to a `worker`, your photos would go in a `queue` and when they were ready you'd be notified, freeing you up to go and do other things in the meantime.