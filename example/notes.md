21.-Make-a-Login-and-Registration-System
# Make a Login and Registration System
With the frontend elements in place the backend logic can now be implemented for full authentication functionality.

## Registration Process
When a user submits the registration form, the following steps occur:

* Validation
    Use `$request->validate()` to ensure required fields like first name, last name, email, and password meet your criteria. Laravel provides many validation rules, including a fluent Password helper for complex password requirements.
    
    ```php
    'password' => ['required', Password::required()->min(8)->max(16)->letters()->numbers()],
    ```

    The password validation rules can also be set in `AppServiceProvider` and then called in the same way but using the default method instead which is much more readable.
    
    ```php
    'password' => Password::default(),
    ```

    Finally we can also add the `confirmed` rule to the password validation. When the `confirmed` rule is present Laravel will look for a field by the name of `password_confirmation` and automatically compare the values. This rule can also be used on other fields. Laravel only requires that the naming convention is `<field>_confirmation` in the form so that it can recognise it in the validator.

    ℹ️ *A list of available validation rules can be found at [Laravel.com](https://laravel.com/docs/12.x/validation#available-validation-rules)*

* Creating the User
    With the data validated the user can now be created using that data. Rather than inputting the data into the `User::create` method manually the validated data can be assigned to a variable and sent through that way `User::create($validatedData)`.
    
    For ultimate efficiency the validation can even be inlined staright into `User::create()`.

    ```php
    User::create(request()->validate([
        // validation...
    ]));
    ```

    Once the user has been created you'll find the password has been `hashed`. This was done thanks to the `casts()` function in the default user model. 

    ℹ️ *Attributes that are `cast` are attributes that can be changed upon saving or retrieving them from the database. So in the case of a password when the user picks a rubbish password like 'password' it is hashed into something unreadable. When the user logs in the password is retrieved and converted back into the password the user chose.*

* Logging In
    Use `Auth::login($user)` to sign in the newly registered user.

* Redirecting
    `Redirect` the user to a desired page, such as the jobs listing or dashboard.
    ```php
    return redirect('/jobs');
    ```

All these steps form the `PRG` pattern or `Post`, `Request`, `Get`.

* Logout
    Logging out should always be handled by a form and a `POST` request and **NOT** a link!
    
    ```php
    <form method="POST" action="/logout">
        <button type="submit">Logout</button>
    </form>
    ```

    Setup a route to listen for the logout request
    ```php
    Route::post('/logout', [SessionController::class, 'destroy']);
    ```
    
    The logout logic is also made ludicrously easy by Laravel.
    ```php
    public function destroy()
    {
        Auth::logout();

        return redirect('/jobs');
    }
    ```

## Logging in an Existing User
When a user submits the login form:

* Validation
    Validate the email and password fields.

* Authentication Attempt
    Use `Auth::attempt($credentials)` to try logging in.

* Session Regeneration
    On successful login, regenerate the session token for security `$request->session()->regenerate();`.

* Redirect
    Redirect the user to the intended page `return redirect('/')`.

* Handling Failure
    If login fails, throw a validation exception with an appropriate error message.

And that's the happy path taken care of. But what about the unhappy path? To handle scenarios where the validated form data doesn't match the stored users data we can throw an error using 
```php
throw ValidationException::withMessages([
    'email' => 'Sorry. Credentials do not match.',
]);
```

Now, if the validation fails we'll throw an error. But (depending on the users browser) if an exception is thrown the form fields might be cleared which is bad UX. To preven this Laravel has a `old()` helper that can be used to temporarily remember the most recently entered values in the form.

```php
<input id="email" name="email" type="email" :value="old('email')">
```
ℹ️*The colon before the value attribute is essential. Without this `old()` will simply be treated as a string rather than an expression.*

## Too much to cover
That's covered a lot but there is more to authorisation than whats covered here. It is recommended to look into `Rate Limiting` which controls the rate at which subsequent requests can be sent to the server.

[Laravel Route Rate Limiting](https://laravel.com/docs/12.x/routing#rate-limiting)
[Laravel Rate Limiting](https://laravel.com/docs/12.x/rate-limiting)

Another thing we missed was restting the password.
[Laravel Password Reset](https://laravel.com/docs/12.x/passwords)