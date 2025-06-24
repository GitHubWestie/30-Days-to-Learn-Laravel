17.-Always-Validate.-Never-Trust-the-User-
# Always Validate. Never Trust the User.

Validation can be implemented at various points of the application in a couple of different ways. 

## Client side validation
The most basic level of validation is `client-side` validation. This relates to things like attributes on a form field such as `required` and many others, which can be enforced by the browser. These are useful for the user as the feedback they provide is instantaneous and doesn't involve sending data however it isn't the most robust and is easy to circumvent.

## Server side validation
Server side validation happens in the backend of the application. This does require sending data and so can be a little slower than client side validation but it is significantly more robust. Laravel makes it easy to validate data by providing a `validate()` method.

The validate method can be chained onto the `request()` method. It accepts an assoc array of fields that require validation and then associates those with the validation rules to be applied. Validation rules can be an array or separated with pipes `|`.

Laravels available validation rules can be found [here](https://laravel.com/docs/12.x/validation#available-validation-rules)

```php
Route::post('/jobs', function () {
    // Validation
    request()->validate([
        'title' => 'required|min:3',
        'salary' => 'required',
    ]);
});
```

## Failed Validation
If any validation fails Laravel handles this gracefully automatically. It will redirect back and populate an errors object that is automatically available for us to use.

The errors object can be accessed in the templates by using the `$errors` variable. This variable is always available, even when empty. To access the errors the object can be looped over to extract the specific errors required. This is useful for displaying errors back to the user.

```php
@if ($errors->any())
    <ul>
        @foreach ($errors as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
```

Alternatively, there is an even simpler way to access the errors using the Blade directive `@error`. This automatically accesses the errors object and allows direct access to the individual error keys without needing to loop over anything. To do this the `@error` directive uses a `$message` variable which is only available within the @error directive.

```php
@error ('title')
    <p>{{ $message }}</P>
@enderror
```