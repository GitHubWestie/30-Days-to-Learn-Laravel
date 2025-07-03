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