# Get Your Build Process in Order
Laravel uses Vite for asset bundling by default and comes preconfigured. Asset bundling combines and optimizes your assets to reduce load times and improve performance. This includes minification, compression, and efficient loading.

Vite relies on node.js and npm. To check if these are installed run `node -v` and `npm -v` in the terminal. If needed node.js can be downloaded from [nodejs.org](https://nodejs.org/en)

## Running Vite
Vite can be run in a few dfferent ways. Most common are `npm run build` and `npm run dev`. The Vite server will use the value of `APP_URL` in your.env file as the host address. This usually defaults to `http://localhost` but can be changed if necessary, for example when running a `Herd` project using `nginx`.

## Hot Reload
Vite has a great feature called hot reloading which will instantly update the browser to reflect changes made as soon as a file is saved; No need to hit refresh. To leverage this the `@vite()` directive needs to be in the blade template within the `<head>` tags and correctly reference the css file in the project which lives in `resources/css/app.css` by default.
```php
@vite(['resources/css/app.css'])
```

The same applies for `app.js` which can be found alongside `app.css`. Just add it to the @vite directive array.

## Tailwind Proper
Up until now we've been using tailwind through a cdn which is fine for demos and quickly geting started but not for production. There are a variety of ways to install Tailwind and they are well documented in the [Tailwind docs](https://tailwindcss.com/docs/installation/framework-guides/laravel/vite). They eve have installatio guides specific to frameworks.