# From Design to Blade
This is a full project from start to finish covering all of the topics learnt along the way plus some new bits.

## Adding the logo
Logo available from [Github](https://github.com/laracasts/pixel-position/blob/main/resources/images/logo.svg)

Instead of the public folder, add the logo to `resources/images/logo.svg`. This will allow Vite to automatically version and hash any images stored which helps with `cache busting`. Cache busting is a way of forcing browsers to use the most up to date assets instead of relying on cached assets which may be out of date.

In the layout component load the image:
```php
<img src="{{ Vite::asset('resources/images/logo.svg') }}"/>
```

This should work fine with a Vite dev server running. However, if `npm run build` is executed then it will likely cause Laravel to throw an error as it will be `unable to locate the file in Vite manifest`. If this is the case it can be fixed by explicitly telling Vite to collect the files, ready for a production build.

```js
// resources/js/app.js
import.meta.glob([
    '../images/**'
]);
```

## Be smart about the design
Start with a design that you can easily reference. Even just a basic layout will save a lot of time and pain in the log run. A layout design can provide valuable insight as to how the page should be broken up into sections, what should be extracted into re-usable components etc. A more detailed design can provide things like font choices, themes, colours and so on.

## Adding Tailwind
Follow Tailwinds install procedure to add it to the project.

The design brief for the site calls for a colour that is almost black but not quite; Specifically `#060606`. This can be modified in `app.css` using the `@theme` directive.
```css
@theme {
    --color-black: #060606;
}
```
Now whenever the Tailwind colour black is referenced e.g. `text-black` or `bg-black` the black will actually be `#060606`.

Colour opacity can be tweaked by adding `/10` or other numbers to indicate a level of opacity.
```html
<div class="border-b border-white/10">
```

The class `space-x-[value]` can be used to add space between items, similar to how gap works with flex or grid. Use it on a parent component to create space between the children.

## Placeholders
When scaffolding out a template it can be useful to insert some quick placeholder images. Rather than having a library of images to throw in there are services such as [Placehold](https://placehold.co/) that allow us to specify any size image placeholder.
```html
<img src="https://placehold.co/42x42" />
```

## Transitions
Transitions are super simple with Tailwind. Simply add the `transitions-[value]` class and then the `duration-[value]` class.
```html
<div class="transition-colors duration-300">
```

# Blade and Tailwind Techniques for Your Laravel Views
When working with flex and images flex can do weird things if an img is a direct descendant of a flex container. For example a 42x4px image might blow up to fill the whole screen. To fix this just throw the image inside a `<div>`.

## Custom fonts
If using a font service like google fonts:
* Use the `@import` statement to import the font at the very beginning of the app.css file
* Add the font to an `@theme` rule `--font-roboto: 'Roboto', sans-serif;`
* Use the font in the html <body class="font-roboto">
Instructions are on [Tailwind](https://tailwindcss.com/docs/font-family#customizing-your-theme)

## Tailwind Groups
By applying the group class to a parent element Tailwind is able to apply conditional styles to child elements. The snippet below demonstrates how to turn a child element purple when the user hovers over the parent element.
```html
<div class="group">
    <h3 class="group-hover:text-purple-500">
```

## Merging Classes
When making re-usable components sometimes it's necessary to pass through unique attributes of types that are already set on the component. For example a component may have it's own classes set but it may be necessary to set additional classes when using the component in certain scenarios. Blade allows for this with the `merge()` helper.
```php
// panel.blade.php
<div {{ $attributes->merge(['class' => 'text-bold font-sm bg-purple-500']) }}>
```
Then when the component is used additonal classes can be added that will be merged into the existing classes.
```php
<x-panel class="flex flex-col gap-y-2">
```
The `merge()` can also be shorthanded down to `$attributes(['class' => 'text-bold font-sm bg-purple-500'])`. Calling $attributes as a function will automaticaly merge attributes.

### Tip for small screens
It's also an idea to define the classes on a component as variables by opening up some php tags in the blade template. This way the variable can be referenced inside the component instead. Tailwind classes can quickly get long and spill off screen so this can help keep the clutter separated from the code.
```php
@php
$classes = 'text-bold font-sm bg-purple-500';
@endphp

<div {{ $attributes(['class' => $classes]) }}>
```

## PHP Tags
Opening up some php tags can be really helpful in a component. It allows for executing conditional logic and anything else PHP can do that you might need. For example:
```php
// tag.blade.php
@props(['size' => 'base'])

@php
$classes = 'bg-white/10 rounded-xl font-bold hover:bg-white/25 transition-colors duration-300';
if ($size === 'base') {
    $classes .= ' px-5 py-1 text-sm';
}

if ($size === 'small') {
    $classes .= ' px-3 py-1 text-2xs';
}
@endphp

<a href="#" class="{{ $classes }}">{{ $slot }}</a>

// job-card.blade.php
<x-tag size="small">Backend</x-tag>
```