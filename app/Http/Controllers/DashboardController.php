<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function welcome(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('welcome');
    }

    public function index(): View
    {
        $user = Auth::user();
        $songs = $user->songs()->latest()->take(8)->get();

        return view('dashboard', [
            'songs' => $songs,
            'songCount' => $user->songs()->count(),
            'averageBpm' => $user->songs()->avg('bpm'),
        ]);
    }
}
