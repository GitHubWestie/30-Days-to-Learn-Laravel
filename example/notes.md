# Meet Eloquent
Eloquent is an `ORM` or `Object Relational Mapper`. All this means that it maps an object in the database, such as a table row, to an `object{}` in PHP code. Simple.

## Getting Started
To get started with eloquent the `Jobs` model needs to `extend` Laravels built in Model class. As the `Model` class already has many built in methods, including `all()` and `find()`, we'll use those.

Eloquent class names should also be singular so rename the class to `Job`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Job extends Model // Extends model class and class name becomes singular
{
    public static function all(): array
    {
        //...
```

**Important**
```
Something that needs to be understood about eloquent is that it favours convention over configuration. 

This means that when eloquent sees the 'Job' class it will assume that the table that matches it is 'Jobs'. The class name is often the singular version of the table name and that's an important thing to keep in mind. This also applies to the name of the file itself, not just the class name.
```

As eloquent expects the related table name to be the plural of the class name we have a problem. The jobs table already exists for Laravel so our table is called job_listings to avoid any conflict. There are 2 options in these scenarios:

1. Give the class a protected property called `$table` and then assign it the value `job_listings`
```php
class Job extends Model
{
    protected $table = 'job_listings';
    public static function all(): array
    {
        //...
```
2. Option two is to simply rename the class `JobListing`
*Note: If this isn't done Eloquent will return empty collections from the database*

Now when Laravel sees the Job class method calls in the routes it will get the data from the database.

For example:
```php
$jobs = Job::all();
```
will return a `collection` from the database.

## Interacting with a collection
Once a collection has been retrieved it can be used in a couple of ways. One way is to interact with it as if it were an array.

For example:
```php
$jobs[0];
```
Will isolate the first item in the eloquent collection. Once we have that we can access it's attributes just like an array.
```php
$jobs[0]->title;
```

## Tinkering Around
Laravel ships with a powerful feature `php artisan tinker`. Tinker is like a command line playground that allows trying things out, creating variables, grabbing them, manipulating things, writing and testing functions to see if the output is as expected. The list goes on.

For example let's say we want to try using the `create()` method for the `Job` class. 

* `php artisan tinker` to open the tinker console
* `App\Models\Job::create(['title' => 'Acme Director', 'salary' => '1,000,000']);`

At the moment this will error and trigger a `mass assignment exception` and it's not because it's wrong. Laravel protects against mass assignment by default as a safety mechanism. Remember, all users are assumed guilty so Laravel handles massassignment this way to protect against bad data. For example if a malicious user insert a sneaky field into a form that wasnt expected, massassignment protection would prevent it from reaching the database.


Fortunately Laravel even tells us how to deal with this in the exception.
```
Illuminate\Database\Eloquent\MassAssignmentException  Add [title] to fillable property to allow mass a
ssignment on [App\Models\Job].
```

By explicitly telling Laravel the properties we wish to be mass assignable we can have mass assignable properties and still be protected from bad data.

This is as simple as adding a protected $fillable array to the class and adding the properties we want to allow for mass assignment.

```php
protected $fillable = ['title', 'salary'];
```

Tinker wont recognise this change automatically so quit Tinker with `ctrl + c` and go back in. Fortunately Tinker does remember previously run commands so push up to get back to any from a previous session.

Now when the create() method is run Tinker should output something like this:
```php
= App\Models\Job {#5260
    title: "Acme Director",
    salary: "1,000,000",
    updated_at: "2025-06-17 07:23:44",
    created_at: "2025-06-17 07:23:44",
    id: 4,
  }
```

Now if we run `App\Models\Job::all()` we will see all of the results from the database including the newly added entry which, because it was created through Eloquent also has timestamps.

The find method can also be used
```php
$job = App\Models\Job::find(4);
```

And then regular Laravel methods can be used too
```php
$job->delete();
```

### Homework
Play around with Models and tinker and get familiar with available options and methods.

Run `php artisan help make:model` to see a list of all of the available options when creating a model.

Delete it all after.