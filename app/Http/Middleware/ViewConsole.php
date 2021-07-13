<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;

class ViewConsole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Viewの読込先をconsoleに変更
        $app = app();
        $paths = [base_path('resources/views/console')];
        $finder = new FileViewFinder($app['files'], $paths);
        View::setFinder($finder);

        return $next($request);
    }
}
