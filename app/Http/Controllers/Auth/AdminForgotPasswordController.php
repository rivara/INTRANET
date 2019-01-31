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
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;


class AdminForgotPasswordController  extends Controller

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
        return view('auth.passwords.admin-email');
    }

    //defining which password broker to use, in our case its the admins
    protected function broker() {
        return Password::broker('admins');
    }

    public function sendResetLinkEmail(Request $request){
        $mail=$request['email'];
        $user = new User();
        $user->email = $mail;   // This is the email you want to send to.
        $user->notify(new TemplateEmail());
        return back()->with('status', 'solicitud enviada rivise su email!');
    }

    // funcion
    public function envioMail(Request $request){
        $messageBody ="";
        Mail::raw($messageBody,function ($message){
            $message->from('rvalle@comafe.es', 'Learning Laravel');
            $message->to('prueba');
            $message->subject('');
        });

        if (Mail::failures()) {
            return back()->with('fail', 'error');
        }

        return back()->with('success', 'ok');
    }



}