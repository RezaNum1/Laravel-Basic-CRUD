<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Citizen;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register_view');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'min:1'],
            'email' => ['required', 'email'],
            'password' => ['required_with:password_confirmation', 'same:password_confirmation', 'min:3'],
            'password_confirmation' => ['required', 'min:3']
        ]);

        $citizen = Citizen::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (Auth::guard('web')->attempt(['email' => $citizen->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('home');
        }
    }

    public function username()
    {
        return 'email'; // or whichever field you are using for authentication
    }
}
