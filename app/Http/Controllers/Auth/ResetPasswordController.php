<?php

namespace DMZ\Http\Controllers\Auth;

use DMZ\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DMZ\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use DMZ\Mail\ResetPasswordMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
//use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request) {
        //return $request->all();
        if(!$this->validateEmail($request->email)){
            return $this->failedResponse();
        }

        $this->send($request->email);
        return $this->successResponse();
    } 

    public function send($email){
        $token = $this->createToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    public function createToken($email){
        $oldToken = DB::table('password_resets')->where('email', $email)->first();
        if($oldToken) {
            return $oldToken->token;
        }
        $token = str_random(60);
        $this->saveToken($token, $email);

        return $token;
    }

    public function saveToken($token, $email){
        
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

    }

    public function validateEmail($email) {
        return !!User::where('email', $email)->first();
    }

    public function failedResponse() {
        return response()->json([
            'error' => trans('auth.email_not_found')
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse() {
        return response()->json([
            'data' => trans('auth.email_reset_send')
        ], Response::HTTP_OK);
    }

    
    
}