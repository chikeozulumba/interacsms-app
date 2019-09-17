<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use App\Models\User;

use Mail;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset (Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
                Mail::to($user->email)
                    ->send(new PasswordReset($user));
                if (Mail::failures()) {
                    return response()->json(['status' => 406, 'message' => 'Password reset link could not be sent.']);
                } else {
                    return response()->json(['status' => 200, 'message' => "Password reset link sent successfully, check your inbox. "]);
                }
        } else {
            return response()->json(['status' => 403,'message' => 'You are not authorized to proceed!']);
        }
    }

    public function passwordReset (Request $request, $token)
    {
        return redirect('/auth?email='.$token);
    }

    public function updatePassword (Request $request)
    {
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        if($password !== $confirm_password) {
            $errors =[
                "error" => "Passwords don't match!"
            ];
            return redirect()->route('auth')->withErrors($errors);
        } else {
            $errors =[
                "error" => "Unauthorized to continue!"
            ];
            $success = [
                "success" => "Passwords updated successfully, sign in to continue!"
            ];
            $user = User::where('token', $request->token)->first();
            if (!$user) return redirect()->route('auth')->withErrors($errors);
            else $user->password = Hash::make($request->password); $user->save(); return redirect()->route('auth')->withErrors($success);
        }
    }
}
