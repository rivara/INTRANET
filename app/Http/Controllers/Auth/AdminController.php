<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 27/11/2018
 * Time: 9:07
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController
{

    public function actionAdmin(Request $request)
    {
        $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
        $request['oAccion'] = "listado";
        $usuarios = DB::table('usuarios')->get();
        $collection = collect($usuarios);
        $page = $request['page'];
        $perPage = 10;
        $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage,
            $page, ['path' => url('admin')]);
        return view('management\users\admin', ['nombre' => $nombre, 'usuarios' => $paginate]);
    }


    public function actionGoUpdateUser(Request $request)
    {

        if ($request->submit == "Edit") {
            //Envia parametros a la pantalla de grabar
            $portales[] = [""];
            $grupos[] = [""];
            $usuario = DB::table('usuarios')->where('id', $request['id'])->get();
            $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $request['id'])->pluck('id_grupo');
            $i = 0;
            foreach ($gruposId as $grupoId) {
                $grupos[$i] = DB::table('portales')->where('id', $grupoId)->get();
                $i++;
            }






            return view("management/users/update", ['usuarios' => $usuario, 'grupos' => $grupos]);
        }

    }


    public function actionCreateUser(Request $request)
    {

        $portales[] = [""];
        $usuarios[] = [""];
        $nombre = $request['nombre'];

        return view("management/users/create", ['nombre' => $nombre]);
    }


    public function actionRecordUser(Request $request)
    {

        $nombreError = array('usuario' => ' ');
        $emailError = array('email' => ' ');

        if (is_null($request['usuario'])) {
            $nombreError = array('usuario' => 'No debe ser vacio');
        }
        if (is_null($request['email'])) {
            $emailError = array('email' => 'No debe ser vacio');
        }
        $id = $request['id'];
        if (is_null($request['usuario']) || is_null($request['email'])) {
            return redirect()->back()->withErrors(array_merge($nombreError, $emailError));
        }
        //Los password tienen que ser igual
        if ($request['passwordR'] != $request['password']) {
            return redirect()->back()->withErrors(['passwordR' => 'No puede ser las claves distintas']);
        } else {
            //Si es vacio mete 1 por defecto
            if (!is_null($request['password'])) {
                $clave = encrypt(1);
            } else {
                $clave = encrypt($request['password']);
            }
        }


        $dato = DB::table('usuarios')->where('email', strtoupper($request['email']))->get();
        if (count($dato) == 0) {
            DB::table('usuarios')->insert(array(
                'id' => $id,
                'nombre' => $request['usuario'],
                'email' => $request['email'],
                'clave' => $clave,
                'id_empresa' => $request['idEmpresa']
            ));
        }
        // aqui pagino





        $usuarios = DB::table('usuarios')->get();



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


    public function actionUpdateUser(Request $request)
    {
        //Controlar que no entran vacios
        $nombreError = array('nombre' => ' ');
        $emailError = array('email' => ' ');

        if (is_null($request['nombre'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['email'])) {
            $emailError = array('email' => 'No debe ser vacio');
        }
        if (is_null($request['nombre']) || is_null($request['email'])) {
            return redirect()->back()->withErrors(array_merge($nombreError, $emailError));
        }

        //los password tienen que ser igual
        if ($request['passwordR'] != $request['password']) {
            return redirect()->back()->withErrors(['passwordR' => 'No puede ser las claves distintas']);
        } else {
            //si es vacio no hace nada
            if (!is_null($request['password'])) {
                //si no update
                DB::table('usuarios')->where('id',
                    $request['usuarioId'])->update(['clave' => Crypt::encrypt($request['password'])]);
            }
        }
        //Update lo que hay en la caja de texto
        DB::table('usuarios')->where('id', $request['usuarioId'])->update(['nombre' => $request['nombre']]);
        DB::table('usuarios')->where('id', $request['usuarioId'])->update(['email' => $request['email']]);








        $usuarios = DB::table('usuarios')->get();



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


    public function actionGoAddUserGroup(Request $request)
    {
        //Añade grupo a un usuario
        $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $request['usuarioId'])->pluck('id_grupo');
        //Grupos en los que NO estan
        $grupoTotales = DB::table('grupos')->whereNotIn('id', $gruposId)->get();
        return view("management/users/groupAdd", ['grupos' => $grupoTotales, 'usuarioId' => $request['usuarioId']]);
    }

    public function actionAddUserGroup(Request $request)
    {
        //Vinculo el grupo del usuario
        $id_usuario = $request['usuarioId'];
        $gruposC = $request->input('grupo');
        if (!empty($gruposC)) {
            foreach ($gruposC as $grup) {
                // si existe en la bbdd no grabarla
                $usuarios = DB::table('usuarios_grupos')->where([
                    'id_usuario' => $id_usuario,
                    'id_grupo' => $grup
                ])->first();
                // si no existe graba
                if ($usuarios === null) {
                    DB::table('usuarios_grupos')->insert(['id_usuario' => $id_usuario, 'id_grupo' => $grup]);
                }
            }
        }


        $usuarios = DB::table('usuarios')->where('id', $id_usuario)->get();
        $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $id_usuario)->pluck('id_grupo');
        $i = 0;
        $grupos[] = "";
        foreach ($gruposId as $grupoId) {
            $grupos[$i] = DB::table('portales')->where('id', $grupoId)->get();
            $i++;
        }

        return view("management/users/update", ['usuarios' => $usuarios, 'grupos' => $grupos]);
    }


    public function actionDeleteUserGroup(Request $request)
    {
        //Desvinculo el grupo del usuario
        $id_usuario = $request['usuarioId'];
        $id_grupo = $request['grupoId'];

        DB::table('usuarios_grupos')->where(['id_usuario' => $id_usuario, 'id_grupo' => $id_grupo])->delete();
        //Envia parametros a la pantalla de grabar
        $usuarios = DB::table('usuarios')->where('id', $id_usuario)->get();
        $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $id_usuario)->pluck('id_grupo');
        $i = 0;
        $grupos[] = "";
        foreach ($gruposId as $grupoId) {
            $grupos[$i] = DB::table('portales')->where('id', $grupoId)->get();
            $i++;
        }
        return view("management/users/update", ['usuarios' => $usuarios, 'grupos' => $grupos]);
    }


    public function actionDeleteUser(Request $request)
    {

        //Borro  de la tabla usuario y de la usuario_grupos
        DB::table('usuarios')->where('id', $request['usuarioId'])->delete();
        DB::table('usuarios_grupos')->where('id_usuario', $request['usuarioId'])->delete();

        // aqui pagino
        $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
        $request['oAccion'] = "listado";
        $usuarios = DB::table('usuarios')->get();
        $collection = collect($usuarios);
        $page = $request['page'];
        $perPage = 10;
        $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage,
            $page, ['path' => url('admin')]);
        //$usuarios = DB::table('usuarios')->get();
        return view('management/users/admin', ['nombre' => $nombre, 'usuarios' => $paginate]);
    }

    public function actionSearch(Request $request)
    {
        //Aqui pagino
        $nombre = DB::table('usuarios')->where('email', session('mail'))->pluck('nombre');
        $usuarios = DB::table('usuarios')->where('nombre', 'like', '%' . $request['search'] . '%')->orWhere('email',
            'like', '%' . $request['search'] . '%')->orWhere('id', 'like', '%' . $request['search'] . '%')->get();
        $collection = collect($usuarios);
        $page = $request['page'];
        $perPage = 10;
        $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage,
            $page, ['path' => url('admin')]);
        return view('management/users/admin', ['nombre' => $nombre, 'usuarios' => $paginate]);
    }



}