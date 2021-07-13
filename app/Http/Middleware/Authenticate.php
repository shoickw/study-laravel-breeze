<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if (strpos($request->fullUrl(), env('APP_CONSOLE_URL')) !== false) {
                return route('console.login');
            }
            if (strpos($request->fullUrl(), env('APP_MANAGER_URL')) !== false) {
                return route('manager.login');
            }
            return route('login');
        }
    }
}
