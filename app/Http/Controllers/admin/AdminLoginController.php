<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 2])) {
            // If successful, then redirect to their intended location
            return redirect()->intended(route('admin.dashboard'));
        }
        elseif (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 1])) {
            // If successful, then redirect to their intended location
            return redirect()->route('admin.login')->withInput($request->only('email'))->withErrors([
                'email' => 'you are not authorized to access this page',
            ]);
        }


        // If unsuccessful, then redirect back to the login with the form data
        return redirect()->route('admin.login')->withInput($request->only('email'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }
}

