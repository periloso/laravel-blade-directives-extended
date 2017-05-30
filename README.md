# Laravel Blade Directives Extended
[![Latest Stable Version](https://poser.pugx.org/periloso/laravel-blade-directives-extended/v/stable.svg)](https://packagist.org/packages/periloso/laravel-blade-directives-extended)
[![License](https://poser.pugx.org/periloso/laravel-blade-directives-extended/license)](https://packagist.org/packages/periloso/laravel-blade-directives-extended)

This package adds custom directives for the Laravel 5 blade engine.

| Directive                           | Description   			                                                   |
| ----------------------------------- | -------------------------------------------------------------------------- |
| @set($variable, value)              | Creating (declaring) PHP variables                                         |
| @implode($delimiter, $array)        | PHP implode() function                                                     |
| @explode($delimiter, $string)       | PHP explode() function                                                     |
| @var_dump($variable)                | PHP var_dump() function                                                    |
| @dd($variable)                      | Laravel dd() function                                                      |

## Installation

Install the package using Composer.

``` bash
composer require "periloso/laravel-blade-directives-extended"
```

After updating composer, add the ServiceProvider to the providers array in config/app.php

``` bash
Periloso\BladeDirectivesExtended\BladeDirectivesExtendedServiceProvider::class
```

**Important** - when extending Blade, it's necessary to clear the cached view.

```bash
php artisan view:clear
```

## Usage

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
