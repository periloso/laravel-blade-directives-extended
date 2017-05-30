<?php

namespace Periloso\BladeDirectivesExtended;

use Illuminate\Support\ServiceProvider;
use \Request;
use \Blade;

class BladeDirectivesExtendedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Checks whether the array is empty.
         *
         * Usage: @ifempty($array)
         */
        Blade::directive('ifempty', function($expression)
        {
            return "<?php if(count($expression) == 0): ?>";
        });

        /*
         * Closes the ifempty statement.
         *
         * Usage: @endifempty
         */
        Blade::directive('endifempty', function($expression)
        {
            return '<?php endif; ?>';
        });

        /*
         * Laravel dd() function.
         *
         * Usage: @dd($variableToDump)
         */
        Blade::directive('dd', function ($expression) {
            return "<?php dd(with({$expression})); ?>";
        });

        /*
         * php explode() function.
         *
         * Usage: @explode($delimiter, $string)
         */
        Blade::directive('explode', function ($argumentString) {
            list($delimiter, $string) = $this->getArguments($argumentString);

            return "<?php echo explode({$delimiter}, {$string}); ?>";
        });

        /*
         * php implode() function.
         *
         * Usage: @implode($delimiter, $array)
         */
        Blade::directive('implode', function ($argumentString) {
            list($delimiter, $array) = $this->getArguments($argumentString);

            return "<?php echo implode(\"{$delimiter}\", {$array}); ?>";
        });

        /*
         * php var_dump() function.
         *
         * Usage: @vardump($variableToDump)
         */
        Blade::directive('vardump', function ($expression) {
            return "<?php var_dump({$expression}); ?>";
        });

        /*
         * Sets the class if the route is equal to the string.
         *
         * Usage: @activeIfRoute($path, 'class if', 'class else')
         */
        Blade::directive('activeIfRoute', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (Request::path() === $url) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Sets the class if the route is different than the string.
         *
         * Usage: @activeUnlessRoute($path, 'class unless', 'class else')
         */
        Blade::directive('activeUnlessRoute', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (!Request::path() === $url) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Sets the class if the route contains the string.
         *
         * Usage: @activeIfRouteContains($path, 'class if', 'class else')
         */
        Blade::directive('activeIfRouteContains', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (strpos(Request::path(), $url) !== false) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Sets the class if the route contains the string.
         *
         * Usage: @activeUnlessRouteContains($path, 'class unless', 'class else')
         */
        Blade::directive('activeUnlessRouteContains', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (strpos(Request::path(), $url) === false) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Sets the class if the route starts with the string.
         *
         * Usage: @activeIfRouteStartsWith($path, 'class if', 'class else')
         */
        Blade::directive('activeIfRouteStartsWith', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (starts_with(Request::path(), $url)) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Sets the class if the route does not start with the string.
         *
         * Usage: @activeUnlessRouteStartsWith($path, 'class unless', 'class else')
         */
        Blade::directive('activeUnlessRouteStartsWith', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (!starts_with(Request::path(), $url)) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Sets the string if the route does not end with the string.
         *
         * Usage: @activeIfRouteEndsWith($path, 'class if', 'class else')
         */
        Blade::directive('activeIfRouteEndsWith', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (ends_with(Request::path(), $url)) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Sets the string unless the route does end with the string.
         *
         * Usage: @activeUnlessRouteEndsWith($path, 'class unless', 'class else')
         */
        Blade::directive('activeUnlessRouteEndsWith', function($expression) {
            list($url, $then, $else) = $this->getArguments($expression, 3);
            if (!ends_with(Request::path(), $url)) { return "<?php echo \"$then\"; ?>"; }
            return "<?php echo '$else' ?>";
        });

        /*
         * Set a variable.
         *
         * Usage: @set($name, value)
         */
        Blade::directive('set', function($expression) {
            list($variable, $value) = explode(',', $expression, 2);

            // Ensure variable has no spaces or apostrophes
            $variable = trim(str_replace('\'', '', $variable));

            // Make sure that the variable starts with $
            if (! starts_with($variable, '$')) {
                $variable = '$' . $variable;
            }

            $value = trim($value);

            return "<?php {$variable} = {$value}; ?>";
        });

        /*
         * Truncate a variable.
         *
         * Usage: @truncate('Your String' , 4)
         */
        Blade::directive('truncate', function ($expression) {
            list($string, $length) = $this->getArguments($expression);
            return "<?php echo e(strlen('{$string}') > {$length} ? substr('{$string}',0,{$length}).'...' : '{$string}'); ?>";
        });

        /*
         * Sets the csrf token to the browser's window object.
         * The namespace is optional.
         *
         * Usage: @csrf('Laracasts')
         * Example: @csrf('namespace')
         */
        Blade::directive('csrf', function ($expression) {
            list($namespace) = $this->getArguments($expression);
            $namespace = ($namespace !== '') ? $namespace : 'Laravel';
            $csrf      = csrf_token();

            $metaTag   = "<meta name=\"csrf-token\" content='{$csrf}'>";
            $scriptTag = "<script>window.{$namespace} = {'csrfToken': '{$csrf}'}</script>";

            return $metaTag . $scriptTag;
        });

        /*
         * Passes a variable to javascript, adding it to window.$variableName.
         *
         * Usage: @js(users, $users)
         *        @js(users, 1234)
         *        @js(users, [$users])
         */
        Blade::directive('js', function ($arguments) {
            list($var, $data) = explode(',', str_replace(['(', ')', ' ', "'"], '', $arguments));
            return  "<?php echo \"<script>window['{$var}']= {$data};</script>\" ?>";
        });

        /*
         * Yields the content only if the yield is set.
         *
         * Usage:
         *   @optional('overlay')
         *
         *       <section id="overlay" class="overlay hide">
         *           <div class="overlay-wrapper">
         *               <div id="overlay-content" class="overlay-content">
         *                   @yield('overlay')
         *               </div>
         *               <button id="overlay-close" class="overlay-close" onclick="overlay.close()">Close</button>
         *           </div>
         *       </section>
         *
         *       @section('footer-left')
         *           <li id="overlay-open"><a href="javascript:void(0);" onClick="overlay.open()">Open Overlay</a></li>
         *       @endsection
         *   @endoptional
         */
        Blade::directive('optional', function($expression)
        {
            return "<?php if(trim(\$__env->yieldContent{$expression})): ?>";
        });

        /*
         * Closes the optional yield.
         */
        Blade::directive('endoptional', function($expression)
        {
            return "<?php endif; ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get argument array from argument string.
     *
     * @param string $arguments
     * @param integer $count
     *
     * @return array
     */
    private function getArguments($arguments, $count = 0)
    {
        $argumentArray = explode(', ', str_replace(['(', ')'], '', $arguments));
        $result = [];
        foreach($argumentArray as $value) {
            array_push($result, trim($value, '\''));
        }

        // Forces the array to have at least $count values.
        while(count($result) < $count) {
            array_push($result, '');
        }
        return $result;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['periloso.blade.directives.extended'];
    }

}
