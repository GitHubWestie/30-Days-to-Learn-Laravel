# Style the Currently Active Navigation Link
In order to style the active navigation link the app needs to know what the current url is. Fortunately Laravel has a built in helper that can be used to determine just that.

**nav-link.blade.php**
```php
<a {{ $attributes }} class="{{ request()->is('/') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Home</a>
```

But now the url given to request()->is() is locked to '/'. Stuck in the component and unable to be changed. One way around this is to use props.

## Attributes Vs. Props
Put simply, `attributes` refers to html attributes. Things like `href`, `id`, `class` etc. A `prop` on the other hand is pretty much anything that *isn't* an attribute.

Props are defined using Blade's `@props` directive. Blade directives are basically just shorthand PHP which will be translated to vanilla PHP at runtime, just like the mooustache syntax.

**nav-link.blade.php**
```php
@props(['active'])

<a {{ $attributes }} class="{{ request()->is('/') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Home</a>
```

**layout.blade.php**
<x-nav-link href="/" :active="request()->is('/')">🏡 Home</x-nav-link>

And this is why it's important to define the props in the component. If the props hadn't been defined in the component then Laravel would just treat `active` as another attribute. This would obviously cause the code to break.

It's also worth noting here that the prop name is preceeded by a colon `:active`. This is also important as it let's Laravel know that this property should be treated as an expression and not a string. This way, boolean values are evaluated properly which is exactly what is needed in this case.