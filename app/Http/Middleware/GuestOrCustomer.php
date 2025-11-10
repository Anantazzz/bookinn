<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestOrCustomer
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user belum login (guest), boleh akses
        if (!Auth::check()) {
            return $next($request);
        }

        // Jika user login sebagai user, boleh akses
        if (Auth::user()->role === 'user') {
            return $next($request);
        }

        // Jika user login dengan role lain, redirect ke dashboard masing-masing
        $role = Auth::user()->role;
        
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'resepsionis':
                return redirect()->route('resepsionis.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            default:
                return redirect()->route('login');
        }
    }
}