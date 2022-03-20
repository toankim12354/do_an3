<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (Auth::guard('admin')->check()) {
            return view('auth.profiles.admin')->with('user', $user);
        }

        if (Auth::guard('teacher')->check()) {
            return view('auth.profiles.teacher')->with('user', $user);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $this->validation($request);

        try {
            $user->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('profile.show')
                             ->with('error', 'Update failed');
        }

        return redirect()->route('profile.show')
                         ->with('success', 'Update successfully');
    }

    public function validation(Request $request) 
    {
        $rule = [];
        $id = Auth::user()->id;

        if (Auth::guard('admin')->check()) {
            $rule = [
                'name' => 'required|min:3',
                'dob' => 'required',
                'phone' => 'required|regex:/^[0-9]{10}$/|unique:admins,phone,' 
                . $id,
                'gender' => 'required',
                'address' => 'required',
                'email' => 'required|email|unique:admins,email,' . $id,
            ];
        }

        if (Auth::guard('teacher')->check()) {
            $rule = [
                'name' => 'required|min:3',
                'dob' => 'required',
                'phone' => 'required|regex:/^[0-9]{10}$/|unique:teachers,phone,'
                . $id,
                'gender' => 'required',
                'address' => 'required',
                'email' => 'required|email|unique:teachers,email,' . $id,
            ];
        }

        return $request->validate($rule);
    }
}
