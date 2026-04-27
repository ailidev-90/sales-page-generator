<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return auth()->check()
            ? redirect()->route('sales-pages.index')
            : redirect()->route('login');
    }
}
