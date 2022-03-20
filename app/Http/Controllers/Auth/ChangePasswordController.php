<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordFormRequest;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function changePassword(ChangePasswordFormRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $currentPassword = $request->current_password;
        $newPassword = $request->password;

        if (Hash::check($currentPassword, $user->password)) {
           $user->password = Hash::make($newPassword);
           try {
               $user->save();
           } catch (\Exception $e) {
               return redirect()->route('profile.show')
                            ->with('change_error', 'Change password failed');
           }
           return redirect()->route('profile.show')
                    ->with('change_success', 'Change password successfully');
        }

        return redirect()->route('profile.show')
                    ->with('change_error', 'Current password not match');
    }
}
