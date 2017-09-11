# Laravel Blade Directives Extended
[![Latest Stable Version](https://poser.pugx.org/periloso/laravel-blade-directives-extended/v/stable.svg)](https://packagist.org/packages/periloso/laravel-blade-directives-extended)
[![License](https://poser.pugx.org/periloso/laravel-blade-directives-extended/license)](https://packagist.org/packages/periloso/laravel-blade-directives-extended)

This package adds custom directives for the Laravel 5 blade engine.

| Directive                                                                    | Description   			                                                        |
| ---------------------------------------------------------------------------- | -------------------------------------------------------------------------------|
| @activeIfRoute($path, 'class if', 'class else')                              | Sets the class if the route is equal to the string.                            |
| @activeUnlessRoute($path, 'class unless', 'class else')                      | Sets the class if the route is different than the string.                      |
| @activeIfRouteContains($path, 'class if', 'class else')                      | Sets the class if the route contains the string.                               |
| @activeUnlessRouteContains($path, 'class unless', 'class else')              | Sets the class unless the route contains the string.                           |
| @activeIfRouteStartsWith($path, 'class if', 'class else')                    | Sets the class if the route starts with the string.                            |
| @activeUnlessRouteStartsWith($path, 'class unless', 'class else')            | Sets the class if the route does not start with the string.                    |
| @activeIfRouteEndsWith($path, 'class if', 'class else')                      | Sets the string if the route does not end with the string.                     |
| @activeUnlessRouteEndsWith($path, 'class unless', 'class else')              | Sets the string unless the route does end with the string.                     |
| @ifempty($array)                                                             | Checks whether the array is empty.                                             |
| @endifempty                                                                  | Closes the ifempty statement.                                                  |
| @set($variable, value)                                                       | Creating (declaring) PHP variables                                             |
| @dd($variable)                                                               | Laravel dd() function.                                                         |
| @explode($delimiter, $string)                                                | php explode() function.                                                        |
| @implode($delimiter, $array)                                                 | php implode() function.                                                        |
| @vardump($variable)                                                          | php var_dump() function.                                                       |
| @set($name, value)                                                           | Sets a variable.                                                               |
| @truncate('Your String' , 4)                                                 | Truncates a variable.                                                          |
| @csrf($namespace)                                                            | Sets the csrf token to the browser's window object. The namespace is optional. |
| @js(users, $users)                                                           | Passes a variable to javascript, adding it to window.$variableName.            |
| @optional('overlay')                                                         | Yields the content only if the yield is set.                                   |
| @endoptional                                                                 | Closes the optional statement.                                                 |
| @cache('my-cache-key')                                                       | Starts a cacheable block given a cache key
| @cache($post)                                                                | Starts a cacheable block given a model (must use Cacheable trait)
| @endcache                                                                    | Closes a cacheable block
## Installation

Install the package by using Composer:

``` bash
composer require "periloso/laravel-blade-directives-extended"
```

After updating composer, add the ServiceProvider to the providers array in config/app.php

``` bash
Periloso\BladeDirectivesExtended\BladeDirectivesExtendedServiceProvider::class
```

**Important** - when extending Blade, it's necessary to clear the cached view!

```bash
php artisan view:clear
```

## Usage example:

``` bash
@set($alpha, true)

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Laravel</title>
</head>
<body>
	<div class="panel panel-default">
		<div class="panel-body">
    		@if($alpha)
                <p>Welcome to Laravel!</p>
            @endif
    	</div>
    </div>
</body>
</html>
```

## About the caching directive:

For this package to function properly, you must use a Laravel cache driver that supports tagging (like `Cache::tags('foo')`). Drivers such as Memcached and Redis support this feature.

Check your `.env` file, and ensure that your `CACHE_DRIVER` choice accomodates this requirement:

```
CACHE_DRIVER=memcached
```

> Have a look at [Laravel's cache configuration documentation](https://laravel.com/docs/5.2/cache#configuration), if you need any help.

### The Basics

With the package now installed, you may use the provided `@cache` Blade directive anywhere in your views, like so:

```html
@cache('my-cache-key')
    <div>
        <h1>Hello World</h1>
    </div>
@endcache
```

By surrounding this block of HTML with the `@cache` and `@endcache` directives, we're asking the package to cache the given HTML. Now this example is trivial, however, you can imagine a more complex view that includes various nested caches, as well as lazy-loaded relationship calls that trigger additional database queries. After the initial page load that caches the HTML fragment, each subsequent refresh will instead pull from the cache. As such, those additional database queries will never be executed.

Please keep in mind that, in production, this will cache the HTML fragment "forever." For local development, on the other hand, we'll automatically flush the relevant cache for you each time you refresh the page. That way, you may update your views and templates however you wish, without needing to worry about clearing the cache manually.

Now because your production server will cache the fragments forever, you'll want to add a step to your deployment process that clears the relevant cache.

```php
Cache::tags('views')->flush();
```

### Caching Models

While you're free to hard-code any string for the cache key, the true power of Russian-Doll caching comes into play when we use a timestamp-based approach.

Consider the following fragment:

```html
@cache($post)
    <article>
        <h2>{{ $post->title }}></h2>
        <p>Written By: {{ $post->author->username }}</p>

        <div class="body">{{ $post->body }}</div>
    </article>
@endcache
```

In this example, we're passing the `$post` object, itself, to the `@cache` directive - rather than a string. The package will then look for a `getCacheKey()` method on the model. We've already done that work for you; just have your Eloquent model use the `Laracasts\Matryoshka\Cacheable` trait, like so:

```php
use Laracasts\Matryoshka\Cacheable;

class Post extends Eloquent
{
    use Cacheable;
}
```

Alternatively, you may use this trait on a parent class that each of your Eloquent models extend.

That should do it! Now, the cache key for this fragment will include the object's `id` and `updated_at` timestamp: `App\Post/1-13241235123`.

> The key is that, because we factor the `updated_at` timestamp into the cache key, whenever you update the given post, the cache key will change. This will then, in effect, bust the cache!

#### Touching

In order for this technique to work properly, it's vital that we have some mechanism to alert parent relationships (and subsequently bust parent caches) each time a model is updated. Here's a basic workflow:

1. Model is updated in the database.
2. Its `updated_at` timestamp is refreshed, triggering a new cache key for the instance.
3. The model "touches" (or pings) its parent.
4. The parent's `updated_at` timestamp, too, is updated, which busts its associated cache.
5. Only the affected fragments re-render. All other cached items remain untouched.

Luckily, Laravel offers this "touch" functionality out of the box. Consider a `Note` object that needs to alert its parent `Card` relationship each time an update occurs.

```php
<?php

namespace App;

use Laracasts\Matryoshka\Cacheable;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use Cacheable;

    protected $touches = ['card'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
```

Notice the `$touches = ['card']` portion. This instructs Laravel to ping the `card` relationship's timestamps each time the note is updated.

``` bash
@cache($card)
    <article class="Card">
        <h2>{{ $card->title }}</h2>

        <ul>
            @foreach ($card->notes as $note)
                @include ('cards/_note')
            @endforeach
        </ul>
    </article>
@endcache

@cache($posts)
    @foreach ($posts as $post)
        @include ('post')
    @endforeach
@endcache
```

Notice the Russian-Doll style cascading for our caches; that's the key. If any note is updated, its individual cache will clear - along with its parent - but any siblings will remain untouched.

Now, as long as the $posts collection contents does not change, that @foreach section will never run. Instead, as always, we'll pull from the cache.

Behind the scenes, this package will detect that you've passed a Laravel collection to the cache directive, and will subsequently generate a unique cache key for the collection.

## FAQ
### Is there any way to override the cache key for a model instance?

Yes. Let's say you have:

``` bash
@cache($post)
    <div>view here</div>
@endcache
```

Behind the scenes, we'll look for a getCacheKey method on the model. Now, as mentioned above, you can use the Laracasts\Matryoshka\Cacheable trait to instantly import this functionality. Alternatively, you may pass a second argument to the @cache directive, like this:

``` bash
@cache($post, 'my-custom-key')
    <div>view here</div>
@endcache
```

This instructs the package to use my-custom-key for the cache instead. This can be useful for pagination and other related tasks.

## Contributing
This package is always open to contributions:


* Master will always contain the newest work, however it may not always be stable; use at your own risk.  Every new tagged release will come from the work done on master.


## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
