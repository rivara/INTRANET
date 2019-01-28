<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\Reset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Notifications\TestMessage;
use Illuminate\Mail\Message;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use App\User;



/**
 * @method notify(PasswordReset $param)
 */
class ForgotPasswordController extends Controller
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
    //use Notifiable;
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showLinkRequestForm(){
        return view('auth.passwords.reset');
    }


    public function changePassword(Request $request){
        //buscamos en la bbdd el mail
        $claveDB = DB::table('usuarios')->where('email',$request['email'])->pluck('clave');
        // si no existe lanzamos mensaje de error
        if($claveDB=="[]"){
            return Redirect::back()->withErrors( [
                'email' => 'No existe este Mail'
            ]);
        }

        if($request['password']==$request['passwordNew']){
            //grabamos la modificaion encriptado la password
            $id = DB::table('usuarios')->where('email',$request['email'])->pluck('id');
            DB::table('usuarios')->where('id', $id)->update(array('clave' =>encrypt($request['password'])));
            return view('auth.login')->with('successMsg','Mail modificado.');
        }else{
            // lanzar error
            return Redirect::back()->withErrors( [
                'passwordNew' => 'La clave es distinta'
            ]);
        }
    }



    public function sendResetLinkEmail(){
        return view('auth.passwords.reset');
    }


    public function toMail(Request $request) {


        //$link = url( "/password/email/?token=".$request->token);

        /*return ( new MailMessage )
            //->view('reset.emailer')
            ->from('info@example.com')
            ->subject( 'Reset your password' )
            ->line( "Hey, We've successfully changed the text " )
            ->action( 'Reset Password', $link )
           // ->attach('reset.attachment')
            ->line( 'Thank you!' );*/

        // Your your own implementation.
       // new ResetPasswordNotification($request->token);
        //instanciar toMail
        return (new SlackMessage)->content('One of your invoices has been paid!');

    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // user 2 sends a message to user 1
        $message = new Message;
        $message->setAttribute('from', 2);
        $message->setAttribute('to', 1);
        $message->setAttribute('message', 'Demo message from user 2 to user 1.');
        $message->save();

        $fromUser = User::find(2);
        $toUser = User::find(1);

        // send notification using the "user" model, when the user receives new message
        $toUser->notify(new NewMessage($fromUser));

        //send notification using the "Notification" facade
        Notification::send($toUser, new NewMessage($fromUser));
        //Mail::to(request()->email)->send(new newpassword($token));
    }

}
