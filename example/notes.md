18.-Editing,-Updating-and-Deleting
# Editing , updating and deleting
Editing, updating and deleting will complete the CRUD functionality for the Job resource.

## Add an Edit Button
In the job show view, add an "Edit Job" button using your reusable button component. Position and style it appropriately.

## Define the Edit Route
Add a `GET` route for editing a job:

```php
Route::get('/jobs/{id}/edit', function ($id) {
    // Return edit view with job data
});
```

## Create the Edit view
This will be very similar to the create view so that can be copied and edited to save some time. The form will still have a `POST` method as that's all the browser can understand. To make sure the `PATCH` request is recognised the `@method('PATCH')` Blade directive can be used to tell Laravel this is actually a PATCH request. 

The action attribute will need to point to the resource that is being edited so that when the form is submitted Laravel knows which job to update `action="$job->id"`.

For a good UX it would be nice if the edit form fields were pre-populated with the data for that job. This can be done by adding the attribute to an input and inserting the data required `value="{{ $job->title }}"`.

The cancel button should probably just return to the job listing so update that to be an link

```php
<a href="/jobs/{{ $job->id }}">
```

### Side Note - Accessing properties
The Jobs objects have been treated like arrays so far meaning that when accessing the values the key has been used with bracket syntax
```php
$job['title']
```
But now that the Jobs objects are Eloquent collections they can also be accessed with arrow syntax
```php
$job->title
```
Either is fine and will work but it is more common to see the arrow syntax in real world development.

## Update with Patch
Currently browsers only understand two request types `GET` and `POST`. Most modern frameworks however understand a few more and this is useful for managing resources while adhering to `RESTful` conventions.

To update a job resource the `PATCH` request will be used. To do this Laravel needs a route listening for a patch request to the resource.

```php
Route::patch('/jobs/{id}', function () {

});
```

Because this is a `PATCH` request this route can use the same `uri` as the other routes for this resource. No need to add `/update` to the end. Laravel will see the patch request type and understands that it needs to update the resource.

## Update Logic
Similarly to when the job was created any updating will also require `validation`. Additionally this type of action should also involve some form of `authorisation` to ensure that the person editing the record has the authority to do so.

Currently there is no user authentication in place so authorisation will have to wait but it is a vital consideration when handling editing of records. The psuedo flow would be something like this.

```php
Route::patch('/jobs/{id}', function () {
    // validate form data
    // Authorise user
    // Update the record
    // Redirect user (probs back to jobs index)
});
```

The complete code will look something like this:
```php
// Update
Route::patch('/jobs/{id}', function ($id) {
    // Validate
    request()->validate([
        'title' => 'required|min:3',
        'salary' => 'required'
    ]);

    // Authorise - Not possible yet...

    // Update the record
    $job = Job::findOrFail($id); // findOrFail protects against a user trying to update a record that doesn't exist

    // Update with the Update() method
    $job->Update([
        'title' => request('title'),
        'salary' => request('salary'),
    ]);

    // OR alternatively update each field manually

    // $job->title = request('title');
    // $job->salary = request('salary');
    // $job->save();

    // Redirect back to jobs index
    return redirect('/jobs/' . $job->id);
});
```

Remember, Laravel needs to be informed that the edit form is actually using a `PATCH` request and not a `POST` request. Blade has a simple direective for this `@method()` which accepts an argument. Adding `@method('PATCH')` to the form instructs Laravel to treat this as a `PATCH` request and it will know to look for `Route::patch()` in the routes file and as we are `PATCH`ing to the `/jobs` resource it knows to look for the PATCH method on that resource.

## Deleting
To delete a job requires a similar setup. But in this case the delete button will live inside the edit form and the edit form already has a method of PATCH so it cant be delete too. To get around this the delete button needs to reference another form from inside the edit form.

* Create the delete button inside the edit form.
    ```php
    <!-- Note the use of the form="" attribute. This is how the delete button binds to the delete form -->
    <button
        form="delete-form"
        type="submit"
        class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
        >
        Delete
    </button>
    ```

* Outside the edit form create another form for the delete button to bind to.
    ```php
    // Delete form
    <form id="delete-form" action="/jobs/{{ $job->id }}" method="POST" hidden>
        @method('DELETE')
        @csrf
    </form>
    ```

* Create a delete route. This needs to find the job and then simply delete it and redirect the user.
```php
// Delete
Route::delete('/jobs/{id}', function ($id) {
    // Authorise user

    // Get the job
    $job = Job::findOrFail($id);
    
    // Destroy the job
    $job->delete();

    return redirect('/jobs');
});
```