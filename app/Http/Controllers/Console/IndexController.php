<?php

namespace App\Http\Controllers\Console;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct(Request $request)
    {
        // 
        $this->middleware('auth:console');
    }

    /**
     * index action
     *
     * @param Request $request
     * @return View
     */
    public function dashboard(Request $request)
    {
        return view('dashboard');
    }
}
