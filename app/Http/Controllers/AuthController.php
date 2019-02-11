<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Mail\PasswordResetConfirmation;
use Illuminate\Http\Request;
use App\User;
use Auth, Mail, Hash, Lang;
use App\Events\UserWasRegistered;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signin(Request $request)
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $id = Auth::id();
            $user = User::whereId($id)->with(['billing_address','delivery_address'])->first();
            return response()->json([
                'user' => $user,
                'token' => Auth::user()->createToken('BackToSchool')->accessToken
            ], 200);
        }   

        return response()->json([
                'response' => Lang::get('messages.error_signin')
            ], 400);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users|max:255|email',
            'password' => 'required|confirmed'
        ]);   
        $user = User::addUser($request);

         UserWasRegistered::dispatch($user);

       	return response()->json([
    		'success' => true,
    		'message' => Lang::get('messages.account_created')
    	], 200);
    }
    
    /**
     * [email confirmation ]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function registerConfirmation(Request $request)
    {
        User::where('confirmation_token', $request->token)
            ->firstOrFail()
            ->confirm();
        
        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.account_confirm')
        ], 200);
    }
    
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPasswordResetEmail()
    {
        $user = User::where('email', request('email'))->firstOrFail();
            
        Mail::to($user)->send(new PasswordReset($user));
        
        return response()->json([
            'success'   =>  true,
            'message' => Lang::get('messages.password_reset')
        ], 200);
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $request->validate([
            'email' => 'required',
            'password' => 'required|confirmed',
            'hash' => 'required'
        ]);
        
        if ( $request->hash === $user->generateHash() )
        {
            $user->password = $request->password;
            $user->save();
            Mail::to($user)->send(new PasswordResetConfirmation($user));

            return response()->json([
                'success'=>true,
                'message' => Lang::get('messages.password_changed')
            ], 200);
        } else {
            return response()->json([
                'success'=> false,
                'message' => 'link broken',//Lang::get('messages.password_changed')
            ], 200);
        }

    }

    /**
     * [manager login]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password'] ])) {
            
            $user_roles = auth()->user()->roles()->pluck('name')->toArray();
            
            if(in_array('ADMIN',$user_roles)||in_array('LOGISTICMANAGER',$user_roles) ){
                return response()->json([
                    'user' => auth()->user(),
                    'role' => $user_roles,
                    'token' => auth()->user()->createToken('BackToSchool')->accessToken
                ], 200);
            
            } else {
                return response()->json([
                    'success'  => false,
                    'message'  => Lang::get('messages.permission_issue')
                ],400);
            }
        }

        return response()->json([
            'success'  => false,
            'message' => Lang::get('messages.error_signin')
        ], 400);
    }
}