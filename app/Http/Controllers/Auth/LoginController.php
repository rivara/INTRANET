<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Hash;
use Redirect;
use Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;

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


        //Si no existe lanzamos mensaje de error
       if ($request['password'] == decrypt($claveDB)) {
       // if (encrypt($request['password']) == $claveDB){
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
            return view('/home', ['nombre' => $nombre, 'portales' => $portales]);
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

    public function redirect(Request $request)
    {
        $nombre = $request['nombre'];
        $url = DB::table('portales')->where('id', $request['id'])->pluck('url');
        $usuarios = DB::table('usuarios')->get();
        if ($url[0] == "admin") {

            $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
            //Aqui pagino
            $request['oAccion'] = "listado";
            $collection = collect($usuarios);
            $page = $request['page'];
            $perPage = 10;
            $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage,
                $page, ['path' => url('admin')]);
            return view('management/users/admin', ['nombre' => $nombre, 'usuarios' => $paginate]);
        }
        if ($url[0] == "desb2b") {
            return view('/desb2b/index', ['oAccion' => $request['oAccion']]);
        }

        if ($url[0] == "ferrcash") {
            return view('/desb2b-Ferrcash/index', ['oAccion' => $request['oAccion']]);

        }
        //pendiente
        return redirect()->away($url[0]."?bla=bla");

    }


    public function backHome()
    {
        $usuarioId = DB::table('usuarios')->where('email', session('mail'))->pluck('id');
        $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
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
        return view('/home', ['nombre' => $nombre, 'portales' => $portales]);
    }


    public function actionBackAdmin(Request $request)
    {
        $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
        $request['oAccion'] = "listado";
        $usuarios = DB::table('usuarios')->get();
        $collection = collect($usuarios);
        $page = $request['page'];
        $perPage = 10;
        $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage,
            $page, ['path' => url('admin')]);
        return view('management/users/admin', ['nombre' => $nombre, 'usuarios' => $paginate]);
    }


}
