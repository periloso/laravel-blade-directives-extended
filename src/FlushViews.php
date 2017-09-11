<?php

namespace Periloso\BladeDirectivesExtended;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class FlushViews
{
    /**
     * Handle the request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     */
    public function handle(Request $request, $next)
    {
        Cache::tags('views')->flush();
        return $next($request);
    }
}
