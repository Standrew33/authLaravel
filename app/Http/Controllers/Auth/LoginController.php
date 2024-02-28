<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt(/*$request->only('email', 'password')*/ $credentials)) {
            //TODO: Variant 2
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.'
            ]);
            //TODO: Variant 1
//            return back()->withInput()->withErrors([
//                'email' => 'These credentials do not match our records.'
//            ]);
        }

        return redirect()/*->route('dashboard')*/->intended(RouteServiceProvider::HOME);
    }
}
