# How to Preview and Send Email Using Mailable Classes
The scenario for this assumes that the user is an employer and would like to receive a confirmation email upon successfully posting a new job. To send emails Laravel needs a mailable class

```php
php artisan make:mail
```

The terminal will prompt for a name fro the new mail class and if it should create a view. In this case select none.

## Inside he mail class
If you look in the mail class you'll see it is constructed of 3 main components:
* Envelope - Used for defining things like subject, sender, replyto and tags etc.
* Content - Represents the main body of the email. Returns a view
* Attachments - Allows content to be attached to the email

## Create an email view
Email views are stored in the views directory. They can be stored in their own sub-dirctory to keep things organised. They are still blade files and will render in the same way.

## View the email
To view the contents of the email we can setup a quick dummy route to test. All it has to do is instantiate a new instance of the email class.
```php
use App\Mail\JobPosted;

Route::get('/test', function () {
    return new JobPosted();
});
```
Then just visit the uri to view the email.

## Sending mail
To send the email Laravel uses the `email facade`. We can also test this on the dummy route by updating.
```php
use Illuminate\Support\Facades\Mail;

Route::get('/test', function () {
    Mail::to('joe@example.com')->send(new JobPosted());

    return 'Done!'; // Gives us feedback in the browser when visiting the test uri
});
```
Visit the test route again and you should see 'Done!'. As there are currently no mail providers configured for the app Laravel should default to logging the email instead. It will also be viewable in the Laravel DebugBar if installed.

## Mail Config
Mail config can be found in the config directory. This is where email providers would be configured. There are also other environment variables that can be configured in the .env file for example the `from` address.

There are many different email service providers and each will likely have their own methods for configuring for their service. These should be documented with the provider or sometimes even provided for you. [Mailtrap](https://mailtrap.io/) for example provide settings for many frameworks that can be copied and pasted into the config file.

```php
MAIL_MAILER=smtp
MAIL_HOST=sandbox.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=info@laracasts.com
MAIL_FROM_NAME="Laracasts"
```

## Implementing email functionality
To actually send an email when a job is posted the functionality needs to be triggered somewhere. The logic we created for the test route can be used from the `JobController` so that it is automatically fired when a job is created.

Instead of harcoding an recipient email the relationships can be leveraged to get the email of the user creating the job instead. We can also return get the data for that job in the email constructor by passing the `$job` object into `JobPosted()`.

```php
    public function store()
    {
        // validation...
        request()->validate([
            'title' => 'required|min:3',
            'salary' => 'required',
        ]);

        $job = Job::create([
            'title' => request('title'),
            'salary' => request('salary'),
            'employer_id' => 1 // Temporarily hardcoded
        ]);

        // Send confirmation email to user
        Mail::to($job->employer->user)->send(new JobPosted($job));

        return redirect('/jobs');
    }
```

**Be sure to update the constructor method in JobPosted to accept the $job data**
```php
public function __construct(public Job $job)
```

Something to be aware of with mail classes is that *all* `public` properties are instantly available within the mail view. If there is any data that you dont want to be available it needs to be a `protected` property in order to hide it. Then you can expose only the data that you want to the view by adding a `with: []` array to the view in the email `content()` method.

Now when the controller hits the `Mail::to` it will fire an email to the user who created the job. If no email server has been configured this will still go to the logs instead.