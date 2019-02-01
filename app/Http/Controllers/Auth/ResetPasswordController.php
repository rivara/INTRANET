<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 31/01/2019
 * Time: 10:14
 */

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Password;
use Auth;
use  DB;

class ResetPasswordController
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset requests
        | and uses a simple trait to include this behavior. You're free to
        | explore this trait and override any methods you wish to tweak.
        |
        */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {

    }

    public function showResetForm() {
        return view('/auth/passwords/reset');//->with(['token' => $token, 'email' => $request->email]);
    }

    public function actionModificaPassword(Request $request){
       if($request['password']==$request['password_confirmation']){
           DB::table('usuarios')->where('email', $request['email'])->update(['clave' => encrypt($request['password'])]);
           return view('auth/login')->with(array('successMsg'=>'Mail cambiado'));
      }else{
            return back()->with('fail', 'no grabado');
       }
    }


    //defining which guard to use in our case, it's the admin guard
    protected function guard()
    {
        return Auth::guard('admin');
    }

    //defining our password broker function
    protected function broker() {
        return Password::broker('admins');
    }
}