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

## Contributing
This package is always open to contributions:


* Master will always contain the newest work, however it may not always be stable; use at your own risk.  Every new tagged release will come from the work done on master.


## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
