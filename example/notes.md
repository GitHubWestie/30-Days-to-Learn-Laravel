# Make a Login and Registration System
To implement any kind of registration or authentication an app needs forms. Lots of forms. To save time in the long run we can extract the existing create a job form into reusable components.

## Extracting Form Components
Create reusable Blade components for:

form-label.blade.php — for form labels
form-error.blade.php — for displaying validation errors
form-input.blade.php — for input fields
These components accept attributes and slots to make them flexible.

Example usage in a form:

<x-form-label for="title">Title</x-form-label>
<x-form-input id="title" name="title" required />
<x-form-error name="title" />

You can then even bundle them all together into another component called `form-field.blade.php`.

## Building Registration and Login Forms

* Create `register.blade.php` and `login.blade.php` views under an `auth directory/namespace`.
* Use the form components to build inputs for `first name`, `last name`, `email`, `password`, and `password confirmation`.
* Add `required attributes` for client-side validation.

## Defining Authentication Routes and Controllers

* Create controllers using
    ```
    php artisan make:controller <ControllerName>
    ```
* Add routes for showing `registration` and `login` forms (`GET /register`, `GET /login`).
* Add routes for handling form submissions (`POST /register`, `POST /login`).

## Displaying Authentication Links Conditionally
Blade has some very useful directives for handling conditional html concerning authenticated users. The `@auth` and `@guest` directives can be used and any content inside their tags will only render if the condition evaluates to true.

```php
    @guest
        <x-nav-link href="/login" :active="request('login')">Login</x-nav-link>
        <x-nav-link href="/register" :active="request('register')">Register</x-nav-link>
    @endguest
```