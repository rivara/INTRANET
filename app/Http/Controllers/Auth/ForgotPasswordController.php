<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 31/01/2019
 * Time: 10:07
 */

namespace App\Http\Controllers\Auth;
use App\Notifications\TemplateEmail;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use DB;

class ForgotPasswordController  extends Controller

{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function showLinkRequestForm() {

        return view('auth/passwords/email');
    }

    //defining which password broker to use, in our case its the admins
    protected function broker() {
        return Password::broker('admins');
    }

    public function sendResetLinkEmail(Request $request){
        $mail=$request['email'];
        $existe=DB::table('usuarios')->where('email',$mail)->count();

        if ($existe== 1) {
            $user = new User();
            $user->email = $mail;   // This is the email you want to send to.
            $token = str_random(32);
            $user->notify(new TemplateEmail($token));
            return back()->with('statusOk', 'Solicitud enviada rivise su email!');
        }else{

            return back()->with('statusFail','Este mail no existe en la bbdd');
        }
    }



    //funcion para envio de correos
  /*  public function envioMail(Request $request){
        $messageBody ="";
        $mail=$request['email'];
        Mail::raw($messageBody,function ($message,$mail){
            $message->from($mail, 'Learning Laravel');
            $message->to('prueba');
            $message->subject('');
        });
        if (Mail::failures()) {
            return back()->with('fail', 'error');
        }
        return back()->with('success', 'ok');
    }*/



}