<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => User::where('role', 'seller')->count(),
            'total_buyers' => User::where('role', 'buyer')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        $recent_users = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_users'));
    }
}
