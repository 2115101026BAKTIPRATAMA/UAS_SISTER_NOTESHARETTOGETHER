<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' =>'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();


        if (! $user || ! Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['the provider credentials are incorrect'],
            ]);
        }

        return $user->createToken('user login')->plainTextToken;
       
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

    public function me(Request $request)
    {
        return response()->json(Auth::user());
    }

    public function register(Request $request)
{
    $request->validate([
        'username' => 'required',
        'firstname' => 'required',
        'lastname' => 'nullable',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
    ]);

    $user = User::create([
        'username' => $request->input('username'),
        'firstname' => $request->input('firstname'),
        'lastname' => $request->input('lastname', ''),
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')),
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
}


public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset password link sent to your email.'])
            : response()->json(['message' => 'Unable to send reset password link.'], 500);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset($request->only(
            'email', 'password', 'password_confirmation', 'token'
        ), function ($user) use ($request) {
            $user->forceFill([
                'password' => bcrypt($request->password),
                // 'remember_token' => Str::random(60), 
            ])->save();
        });

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been successfully reset.'])
            : response()->json(['message' => 'Unable to reset password.'], 500);
    }

}
