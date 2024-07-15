<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $categories = Category::where('showathome', '1')->take(8)->get();
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();
        return view('front.account.login', compact('categories', 'subcategories'));
    }

    public function handleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user role is 2
            if ($user->role == 2) {
                Auth::logout();
                return response()->json(['error' => 'You are not authorized to access this page'], 403);
            }

            return response()->json(['success' => 'Login successful! Redirecting...'], 200);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }


    public function register()
    {
        $categories = Category::where('showathome', '1')->take(8)->get();
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();
        return view('front.account.register', compact('categories', 'subcategories'));
    }

    public function handleRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['success' => 'Registration successful! Please login.'], 200);
    }


    public function profile()
    {
        $user = Auth::user();
        $categories = Category::where('showathome', '1')->take(8)->get();
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();
        return view('front.account.profile', compact('user', 'categories', 'subcategories'));
    }

    public function updateprofile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->update($request->all());

        return response()->json(['success' => 'Information updated successfully.']);
    }
    public function orders()
    {
        // Implement orders logic
        return view('front.account.orders');
    }

    public function wishlist()
    {
        // Implement wishlist logic
        return view('front.account.wishlist');
    }

    public function changePassword()
    {
        return view('front.account.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}


