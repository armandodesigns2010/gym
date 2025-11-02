<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return match (Auth::user()->role) {
            'admin' => to_route('admin.dashboard'),
            'member' => to_route('member.dashboard'),
            'instructor' => to_route('instructor.dashboard'),
            default => to_route('login'),
        };
    }
}
