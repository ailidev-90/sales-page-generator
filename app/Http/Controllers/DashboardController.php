<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'totalPages' => $user->salesPages()->count(),
            'modernCount' => $user->salesPages()->where('template', 'modern')->count(),
            'elegantCount' => $user->salesPages()->where('template', 'elegant')->count(),
            'boldCount' => $user->salesPages()->where('template', 'bold')->count(),
            'recentPages' => $user->salesPages()->latest()->take(4)->get(),
        ]);
    }
}
