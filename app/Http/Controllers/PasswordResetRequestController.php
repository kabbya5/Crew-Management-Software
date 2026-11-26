<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Notifications\UserPasswordUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class PasswordResetRequestController extends Controller
{
    public function create()
    {
        return view('auth.password-reset-request');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:30'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::table('users')->where('phone', $validated['phone_number'])->first();
 
        if(!$user){
                throw ValidationException::withMessages([
                'phone_number' => 'phone number does not match.',
            ]);
        }
        

        // Prevent multiple pending requests
        $existingRequest = PasswordResetRequest::where('phone_number', $validated['phone_number'])
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            throw ValidationException::withMessages([
                'phone_number' => 'You already have a pending password reset request.',
            ]);
        }

        // Save request only. Do NOT update crew password yet.
        $passwordRequest = PasswordResetRequest::create([
            'user_id' => $user->id,
            'phone_number' => $validated['phone_number'],
            'new_password' => Hash::make($validated['new_password']),
            'status' => 'pending',
        ]);

        $admins = User::where('role', 'admin')->get();

        Notification::send(
            $admins,
            new UserPasswordUpdated($passwordRequest)
        );

        return back()->with(
            'success',
            'Your password reset request has been submitted. Please wait for admin approval.'
        );
    }
}