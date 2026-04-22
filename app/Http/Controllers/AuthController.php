<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Session::has('authenticated')) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $envUser = env('SIMPLE_AUTH_USER');
        $envPass = env('SIMPLE_AUTH_PASS');

        if ($request->username === $envUser && $request->password === $envPass) {
            Session::put('authenticated', true);
            Session::put('username', $envUser);
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'login_error' => 'Credenciales incorrectas.',
        ])->withInput($request->only('username'));
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        Session::forget('authenticated');
        Session::forget('username');
        return redirect()->route('login');
    }
}
