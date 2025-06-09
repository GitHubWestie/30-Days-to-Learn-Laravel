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

### Homework
Create a type prop so that the nav-link element can be either an <a> or a <button> tag.

#### My Attempt
**nav-link.blade.php**
```php
@props([
    'active' => false,
    'type' => 'a', // defaults to <a> if no type specified
    ])

<{{ $type }}
    {{ $attributes }}
    class="{{ $active ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium"
    aria-current="{{ $active ? 'page' : false }}"
    >
    {{ $slot }}
</{{ $type }}>
```

**layout.blade.php**
```php
<x-nav-link type="button">👤 Login</x-nav-link>
```

#### Laracasts Solution
While the solution I implemented above was demonstrated as an example of how this might be approached ultimately the solution was this:

**nav-link.blade.php**
```php
@props([
    'active' => false,
    'type' => 'a', // defaults to <a> if no type specified
    ])

<?php if($type === 'a') : ?>
    <a
        {{ $attributes }}
        class="{{ $active ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium"
        aria-current="{{ $active ? 'page' : false }}"
        >
        {{ $slot }}
    </a>
<?php else : ?>
    <button
        {{ $attributes }}
        class="{{ $active ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md px-3 py-2 text-sm font-medium"
        aria-current="{{ $active ? 'page' : false }}"
        >
        {{ $slot }}
    </button>
<?php endif; ?>
```

Remember that the php tags could also be substituted for Blade directive syntax @if, @else, @endif.