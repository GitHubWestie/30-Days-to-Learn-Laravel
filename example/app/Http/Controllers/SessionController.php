<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // Validate form data
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to login
        if ( ! Auth::attempt($user)) {
            throw ValidationException::withMessages([
                'email' => 'Sorry. Those credentials do not match.'
            ]);
        };

        // Regenerate session
        $request->session()->regenerate();

        // Redirect user
        return redirect('/jobs');
    }

    public function destroy()
    {
        // Log user out
        Auth::logout();

        // Redirect after logout
        return redirect('/login');
    }
}
