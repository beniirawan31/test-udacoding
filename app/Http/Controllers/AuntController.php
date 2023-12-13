<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuntController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Check if email meets specific criteria
        if (!$this->isEmailValid($request->input('email'))) {
            $validator->errors()->add('email', 'Email must meet specific criteria.');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'level' => 'admin'
        ]);

        // Your logic after user creation goes here

        return redirect()->route('login'); // Redirect to the login page after successful registration
    }

    private function isEmailValid($email)
    {
        // Add your specific email validation logic here
        // For example, check if the email meets certain criteria
        // If not, return false; otherwise, return true
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        // Validate the incoming request data
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // If authentication fails, throw a ValidationException with an error message
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // If authentication succeeds, regenerate the session and redirect to the dashboard
        $request->session()->regenerate();
        return redirect()->route('home');
    }
}
