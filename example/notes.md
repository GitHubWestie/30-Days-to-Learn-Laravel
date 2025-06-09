# 02. Your First Route and View

Routes are found in the routes directory inside web.php and are structured like this:

```php
Route::get('/', function () {
    return view('welcome');
});
```

In this case the `Route` class is calling the `get()` method which accepts a `$uri`. It then calls a helper function to return the `view`.

It's worth noting that the `get()` method doesnt *have* to return a view. It can return a `string`, `array` or other things.

## Views
Views are basically the html. View files are stored in the `resources/views` directory. Laravel uses the `Blade` templating language for it's views but plain html can also be used.

In order for the route to get the view there must be a view file with the same name as the file that is being returned by the route. The route above for example calls `welcome.blade.php`.