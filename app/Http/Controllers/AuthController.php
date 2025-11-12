<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle Register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^(\+62|62|0)[0-9]{9,12}$/',
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()],
            'role' => 'required|in:buyer,seller',
        ], [
            'phone.regex' => 'Phone number must be a valid Indonesian number',
            'password.mixed_case' => 'Password must contain uppercase and lowercase letters',
            'password.numbers' => 'Password must contain at least one number',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);

        return redirect()->route('welcome')->with('success', 'Registration successful! Welcome to CampusMarket');
    }

    // Show Login Form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
            } elseif ($user->role === 'seller') {
                return redirect()->route('seller.dashboard')->with('success', 'Welcome back, Seller!');
            } else {
                return redirect()->route('welcome')->with('success', 'Welcome back!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully');
    }
}
