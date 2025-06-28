<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        // Validate form data
        $validData = request()->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email|max:254',
            'password' => ['required', Password::min(6), 'confirmed'],
        ]);

        // Create the user
        $user = User::create($validData);

        // Log the user in
        Auth::login($user);

        // Redirect the user
        return redirect('/jobs');
    }
}
