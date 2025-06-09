# Make a Pretty Layout Using TailwindCSS
This lesson the page gets jazzed up with a template from [Tailwind](https://tailwindcss.com/plus/ui-blocks/application-ui/application-shells/stacked). This template replaces the code in the layout.blade.php file.

The template has a hardcoded page heading but it would be nicer if this was dynamic. This can be achieved in a couple of different ways; Either by passing `attributes` or `'props'` to the <x-layout> tag:

**home.blade.php**
```php
<x-layout heading="Dashboard">
    // 
</x-layout>
```

 *or* by using a **named slot**:

**home.blade.php**
 ```php
<x-layout>
    <x-slot:heading>Dashboard</x-slot:heading>
</x-layout>
 ```

 Then in the template the heading can be used.

 **layout.blade.php**
 <h1>{{ $heading }}</h1>