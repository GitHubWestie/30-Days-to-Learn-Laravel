# 03. Create a Layout File Using Laravel Components

A powerful feature in Laravel is the Blade templating engine. It allows for the creation of reusable components that are scalable and reduce repetition in templates.

Files can leverage the Blade engine by adding the blade suffix to the file name

```
welcome.blade.php
```

Components can be many things such as layout files, menus, navigation, forms etc. Basically anything you like that would be useful if it was re-usable.

## Creating a component
Laravel expects components to live inside a components directory. Anything within it will be treated as a component.

```
|- resources/views/components
|--> layout.blade.php
```

Then within the component file create the component how you would in any other html file.

## Using the Component
Components are used by referencing the components using custom html tags. The tags always start with `x-` to prevent any possible clashes with existing components.

```html
<x-layout>
    <!-- Page content goes here -->
</x-layout>
```

## Adding Content
Unfortunately the content cant quite just be dumped in between the layout tags. Blade expects content to be `slotted` so that it knows where to put things. Blade achieves this by using double curly braces or moustache syntax and the `$slot` keyword which is available globally thanks to PHP.

```php
<x-layout>
    {{ $slot }}
</x-layout>
```

All this moustache syntax is doing is shorthanding PHP. It literally translates to:
```php
<?php echo $slot ?>
```

At runtime the blade shorthand is compiled back down to this vanilla PHP. They are absolutely interchangeable but the shorthand is obviously more efficient and cleaner.

## Homework
The assignemnt was to extract the navigation links into their own component so that they too can be more modular.

For the most part the approach was the same as creating the layout component.

The tricky part of this assignment was setting the `href` attribute uniquely for each <x-nav-link> component. Fortunately, Blade provides access to an `$attribute` object which will store attributes that are passed to a given component.

**nav-link.blade.php**
```php
<a {{ $attributes }}>{{ $slot }}</a>
```

**layout.blade.php**
```php
<x-nav-link href="/">Home</x-nav-link>
<x-nav-link href="/about">about</x-nav-link>
<x-nav-link href="/contact">contact</x-nav-link>
```