<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Mail;
use App\Mail\EmailVerification;
use App\Models\User;

class VerifyController extends Controller
{
    public function __construct ()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->isVerified === 1) {
            return redirect()->route('home');
        }
        return view('auth.verify');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doVerify()
    {
        if (Auth::check()) {
            $user = Auth::user();
            Mail::to($user->email)
                ->send(new EmailVerification($user));
            if (Mail::failures()) {
                return response()->json(['status' => 406, 'message' => 'Verification mail could not be sent.']);
            } else {
                return response()->json(['status' => 200, 'message' => "Verification mail sent successfully, check your inbox. "]);
            }
        } else {
            return response()->json(['status' => 403,'message' => 'You aren\'t logged in, retry.']);
        }
    }

    public function validateEmail (Request $request, $token)
    {
        $user = Auth::user();
        $tokenBearer = User::where('token', $token)->first();
        if ($tokenBearer !== NULL) {
            if ($tokenBearer->id == $user->id) {
                $user->isVerified = 1;
                $user->save();
                return redirect()->route('home');
            } else {
                // return 0;
                $errors =[
                    "error" => "Authorization link is invalid."
                ];
                return redirect()->route('auth.verify', ['verify' => 'email'])->withErrors($errors);
            }
        } else {
            // return 0;
            $errors =[
                "error" => "You aren\'t authorized."
            ];
            return redirect()->route('auth.verify', ['verify' => 'email'])->withErrors($errors);
        }
    }
}
