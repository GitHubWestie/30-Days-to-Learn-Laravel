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

# Jobs, Tags, TDD, Oh My!
In the previous lessons there were issues regarding the Laravel generated jobs tables which exist in the database for queued jobs. This forced the project to have a table named job_listings to avoid a conflict. This time around Laravel can take the hit and change it's tables.
* Update the references to the jobs tables (there are 3) in the `config/queues` file to be `queued_jobs`
* Update the migration file name to be create_queued_jobs_table
* Update the table names in that migration file to be pre-pended with queued
* Run `php artisan migrate:fresh` to update the database

## Schemas
When coding the schemas the `default()` method can be chained on to set a default value for that column.

## Seeding Factories
When seeding, model relaionships can be leveraged to attach a model instance to another model.

```php
// JobSeeder.php
$tags = Tag::factory(3)->create();

Job::factory(20)->hasAttached($tags)->create();
```

## Sequences
Sequences can be used to control how a factory behaves when generating data. A sequence tells Laravel to iterate over set parameters
```php
use Illuminate\Database\Eloquent\Factories\Sequence;

Job::factory(20)->create(new Sequence([
    'featured' => false,
    'schedule' => 'part-time',
],
[
    'featured' => true,
    'schedule' => 'full-time',
]));
```
In this instance Laravel will create 20 jobs; 10 will have featured set to 0 (false) and 10 will have featured set to 1 (true).

These can then be retrieved from the database into their own collection by doing something like this
```php
// JobController.php
$jobs = Job::all()->groupBy('featured');

return view('index', [
    'featuredJobs' => $jobs[0],
    'jobs' => $jobs[1],
]);
```

This will still get all jobs but they will be grouped into their own arrays inside the collection

# The Everything Episode

## Forms
* Break forms down into re-usable components.
* Use `props` to pass data/attributes through to the component from the parent template.
* Use the `$attributes()` shorthand or `$attributes->merge()` to allow unique attibutes on components

## Validation
* Remember to use the `'confirmed'` rule o passwords to automatically check for a `confirmed_password` field
* Use `Password::min()` to set the minimum length of a password
* Use `File::type()` to set the accepted file types when validating a file upload field
* When acceptng file uploads the form must have the `enctype="multipart/form-data"` attribute to work

## Creating User and Employer Together
The app uses a single registration form to capture user data and employer data. These can be split at the point of validation which allows for creating the user and employer together. This means we can also leverage the relationship between user and employer to automatically link the user_id to the employer at the point of creation.

The logo needs to be handled slightly differently as it is a file type. First Laravel needs to know where to store the logo. In the `config` directory lives `filesystems.php`. This file contains various preset `storage disk` configs and more can be added here if required. As the logo will need to be publicly available it will need the public disk. This can be changed in `.env` under `FILESYSTEM_DISK`. 

Laravel will then store images to the `storage/app/public` directory.

```php
// RegusteredUserController.php
public function store(Request $request)
    public function store(Request $request)
    {
        $userAttributes = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'employer' => ['required'],
            'logo' => ['required'],
        ]);

        $companyAttributes = $request->validate([
            'employer' => ['required'],
            'logo' => ['required', File::types(['png', 'jpg', 'jpeg', 'webp'])],
        ]);

        $user = User::create($userAttributes);

        // Laravel will create a directory called 'logos' when the logo is submitted
        $logoPath = $request->logo->store('logos');

        // Create the employer record using the employer relationship
        $user->employer()->create([
            'name' => $companyAttributes['employer'],
            'logo' => $logoPath,
        ]);

        Auth::login($user);

        return redirect('/');
    }
```

## Invokable Controllers
Sometimes a controller will only ever have one function. In these instances they can be setup as an invokable controller. This means whenever the controller is accessed the contained function will be execute automatically.
```php
// SearchController.php
public function __invoke()
{
    dd(request('q'));
}

//web.php
Route::get('/search', SearchController::class);
```
*Note how there is no need to put the Controller::class in an array*

## Route Model Binding
A similar approach can be used to get all jobs with a given tag. 
* Remember that by default when a wildcard is used in a route Laravel will search for an `id`. This can be changed by adding a colon followed by what we actually want Laravel to find `/tags/{tag:name}`.
```php
//web.php
Route::get('/tags/{tag:name}', TagController::class);

// TagController.php
class TagController extends Controller
{
    public function __invoke(Tag $tag)
    {
        return view('results', [
            'jobs' => $tag->jobs,
        ]);
    }
}
```