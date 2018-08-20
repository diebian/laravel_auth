<?php

namespace DMZ\Http\Controllers\Auth;

use Illuminate\Http\Request;
use DMZ\Http\Controllers\Controller;
use DMZ\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use DMZ\User;

class ChangePasswordController extends Controller
{
    public function process(ChangePasswordRequest $request) {
       //return $this->getPasswordResetTableRow($request)->get();
       return $this->getPasswordResetTableRow($request)->count() > 0 ? $this->changePassword($request) : $this->tokenNotFoundResponse();

    }

    private function getPasswordResetTableRow($request) {
        return DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->resetToken
        ]); 
    }
    private function tokenNotFoundResponse(){
          
        return response()->json([
            'error' => trans('auth.token_incorrect')
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
        
    }

    private function changePassword($request) {
        $user = User::where('email', $request->email)->first();
        //return $user;
        $user->update([
            'password' => $request->password
        ]);
        $this->getPasswordResetTableRow($request)->delete();
        return response()->json([
            'data' => trans('auth.password_changed')
        ], Response::HTTP_CREATED);
    }

}
