<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use MaddHatter\LaravelFullcalendar\Calendar;
use Illuminate\Pagination\LengthAwarePaginator;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function verifica(Request $request)
    {
        //Buscamos en la bbdd el mail
        $claveDB = DB::table('usuarios')->where('email', $request['email'])->pluck('clave');
        $portales = [];
        //Si no existe lanzamos mensaje de error
        if ($claveDB == "[]") {
            return redirect()->back()->withErrors(['email' => 'No exsite este Mail']);
        }


        //Si no existe lanzamos mensaje de error¡
        if ($request['password'] == decrypt($claveDB)) {
            //recoger datos
            $usuarioId = DB::table('usuarios')->where('email', $request['email'])->pluck('id');


            $nombre = DB::table('usuarios')->where('email', $request['email'])->pluck('nombre');
            //saco los grupos que pertenece el usuario
            $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $usuarioId)->pluck('id_grupo');
            //Saco los portales que puede ver en los grupos en los que esta ese usuario
            $i = 0;



            foreach ($gruposId as $grupoId) {
                $portalesId = DB::table('grupos_portales')->where('id_grupo', $grupoId)->pluck('id_portal');
                foreach ($portalesId as $portalId) {
                    $portales[$i] = DB::table('portales')->where('id', $portalId)->get();
                    $i++;
                }
            }
            //Elimina duplicados
            $portales = array_unique($portales);
            //Mete el email en una variable de sesión
            session()->put('mail', $request['email']);

            return view('/home', ['nombre' => $nombre,'id_usuario' => $usuarioId, 'portales' => $portales]);
        }
        else {
            //Si no lanzo mensaje de error
            return redirect()->back()->withErrors(['password' => 'Clave erronea']);
        }
    }


    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function redirect(Request $request)
    {
        $nombre = $request['nombre'];
        $url = DB::table('portales')->where('id', $request['id'])->pluck('url');
        $claveDB = DB::table('usuarios')->where('nombre', $request['nombre'])->pluck('clave');
        $usuarios = DB::table('usuarios')->get();
        if ($url[0] == "admin") {
            //Aqui pagino
            $request['oAccion'] = "listado";
            $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
            $usuarios = DB::table('usuarios')->get();
            $collection = collect($usuarios);
            $page = $request['page'];
            $perPage = 10;
            $paginat = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage, $page, ['path' => url('management/users')]);
            return view('management/users/admin', ['nombre' => $nombre, 'paginado' => $paginat]);
        }
        if ($url[0] == "desb2b") {
            return view('/desb2b/index',['oAccion' => $request['oAccion'],'id_usuario' => $request['id_usuario']]);
        }

        if ($url[0] == "ferrcash") {
            return view('/desb2b-Ferrcash/index', ['oAccion' => $request['oAccion']]);

        }
        if ($url[0] == "biblioteca") {
             return view('/biblioteca/carpetas', ['id_usuario' => $request['id_usuario']]);

        }
        if ($url[0] == "reporting") {
            return view('/reporting/index', ['id_usuario' => $request['id_usuario']]);

        }

        if ($url[0] == "mantenimiento") {
            return view('/mantenimiento', ['id_usuario' => $request['id_usuario']]);

        }

        if ($url[0] == "sat") {
            return redirect()->away('http://sat.comafe.es/login.php'.'?nombre='.$nombre.'&password='.decrypt($claveDB));

        }
        if ($url[0] == "reserva") {

            ///////////////////////////////////////////////////////////////////////////////////////////////
// return view('salas/index',['nombre' => $request['nombre']]);

            $calendario = DB::table('reservas')->get();

            foreach($calendario as $calendar) {
                $events[] = Calendar::event(
                    $calendar->descripcion, //event title
                    false, //full day event?
                    new \DateTime($calendar->fecha." ".$calendar->hora_inicio), //start time (you can also use Carbon instead of DateTime)
                    new \DateTime($calendar->fecha." ".$calendar->hora_fin), //end time (you can also use Carbon instead of DateTime)
                    null,
                    [
                        'color' => $calendar->color,
                        'url' => 'salas/edit',
                    ]);
            }

            $nombre = $request['nombre'];
            $calendar = \Calendar::addEvents($events);
            return view('salas/index', compact('calendar','nombre'));



          //  return view('salas/index');

        }


        return redirect()->away($url[0]."?nombre=".$nombre."&password=".decrypt($claveDB));

    }


    public function backHome()
    {
        $usuarioId =    DB::table('usuarios')->where('email', session('mail'))->pluck('id');
        $nombre =       DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
        $nombreId=      DB::table('usuarios')->where('email', session('mail'))->pluck('id');
        //saco los grupos que pertenece el usuario
        $gruposId =     DB::table('usuarios_grupos')->where('id_usuario', $usuarioId)->pluck('id_grupo');
        //Saco los portales que puede ver en los grupos en los que esta ese usuario
        $i = 0;

        foreach ($gruposId as $grupoId) {
        $portalesId = DB::table('grupos_portales')->where('id_grupo', $grupoId)->pluck('id_portal');

        foreach ($portalesId as $portalId) {
                $portales[$i] = DB::table('portales')->where('id', $portalId)->get();
                $i++;
            }
        }
        //Elimina duplicados
        $portales = array_unique($portales);

        return view('/home', ['nombre' => $nombre,'id_usuario' => $nombreId, 'portales' => $portales]);
    }


    public function actionBackAdmin(Request $request)
    {
        $request['oAccion'] = "listado";
        $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
        $usuarios = DB::table('usuarios')->get();
        $collection = collect($usuarios);
        $page = $request['page'];
        //Aqui pagino
        $perPage = 10;
        $paginat = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage, $page, ['path' => url('management/users')]);
        return view('management/users/admin', ['nombre' => $nombre, 'paginado' => $paginat]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function actionDescarga(Request $request){


        $filename="ayuda.exe";
        //PDF file is stored under project/public/download/info.pdf
        $file_path = public_path("storage/").$filename;

        if (file_exists($file_path))
        {
            // Send Download
            return FacadeResponse::download($file_path, $filename, [
                'Content-Length: '. filesize($file_path)
            ]);
        }
        else
        {
            // Error
            exit('¡Este fichero no existe!');
        }


    }
}
