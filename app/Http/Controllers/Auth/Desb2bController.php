<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 12/12/2018
 * Time: 10:59
 */

namespace App\Http\Controllers\Auth;

use App\Library\WebAdmLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

class desb2BController
{
    public function actionWebAdmLog(Request $request)
    {

        $admLog = new WebAdmLog();
        $fechaDesde = $request['fechaDesde'];
        $fechaHasta = $request['fechaHasta'];
        $empresa = $admLog->empresa($request['empresa']);
        $cdclien = $admLog->cdclien($request['cdclien']);
        $cdsucur = $admLog->cdsucur($request['cdsucur']);
        $seccion = $admLog->seccion($request['seccion']);
        $des = $admLog->des($request['des']);
        $userMag = $admLog->userMag($request['userMag']);
        $logs = $admLog->getRegistros($fechaDesde, $fechaHasta, null, null);
        $collection = collect($logs);
        $page = $request['page'];
        $perPage = 10;
        $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage,
            $page, ['path' => url('/WebAdmLog?oAccion=listado&id_usuario=' . $request['id_usuario'])]);
        return view('/desb2b/WebAdmLog', [
            'oAccion' => $request['oAccion'],
            'logs' => $paginate,
            'email' => $request['email'],
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
            'empresa' => $empresa,
            'cdclien' => $cdclien,
            'cdsucur' => $cdsucur,
            'seccion' => $seccion,
            'des' => $des,
            'userMag' => $userMag,
            'id_usuario' => $request['id_usuario'],
            'id' => $request['id']
        ]);

    }

    public function actionEjemplo(Request $request)
    {
        return view('/desb2b/Ejemplo', ['oAccion' => 'inicio', 'id_usuario' => $request['id_usuario']]);
    }

    public function actionindex(Request $request)
    {
        return view('/desb2b/Index', ['oAccion' => 'inicio', 'id_usuario' => $request['id_usuario']]);
    }

    public function actionGoMenu(Request $request)
    {
        return view("/management/menu/menu");
    }

    public function actionCreateMenu(Request $request)
    {
        return view("/management/menu/create");
    }

    public function actionUpdateMenu(Request $request)
    {
        return view("/management/menu/update", ['id' => $request['id']]);
    }

    public function actionGoDropSubcategoria(Request $request)
    {
        return view("/management/menu/update",
            ['id' => $request['id'], 'id_b2b' => $request['id_b2b'], 'id_categoria' => $request['id_categoria']]);
    }

    public function actionGoAddSubcategoria(Request $request)
    {
        return view("/management/menu/menuAdd",
            ['id' => $request['id'], 'id_b2b' => $request['id_b2b'], 'id_categoria' => $request['id_categoria']]);
    }


    public function actionGoCreateCategoria(Request $request)
    {
        return view("/management/menu/createCategoria", ['id' => $request['id']]);
    }

    public function actionGoCreateSubcategoria(Request $request)
    {
        return view("/management/menu/createSubCategoria", ['id' => $request['id']]);
    }

    public function actiongoDeleteCategoria(Request $request)
    {
        return view("/management/menu/deleteCategoria", ['id' => $request['id']]);
    }


    public function actionGoMenuAdd(Request $request)
    {
        return view("/management/menu/menuAdd", ['id' => $request['id']]);
    }

    /*BORRAR grupo*/
    public function actionDeleteMenu(Request $request)
    {
        DB::table('menus')->where(['id' => $request["menu_id"]])->delete();
        return view("/management/menu/menu", ['id' => $request['id']]);
    }

    /*BORRAR categoria*/
    public function actionDeleteMenuCategoria(Request $request)
    {
        //borra categoria
        DB::table('menus_b2b')->where(['id_menu' => $request["id"], 'id_b2b' => $request["id_b2b"]])->delete();
        return view("/management/menu/update", ['id' => $request['id']]);
    }

    public function actionDeleteMenuSubCategoria(Request $request)
    {
        DB::table('menus_b2b')->where(['id_menu' => $request["id"], 'id_b2b' => $request["id_b2b"]])->delete();
        return view("/management/menu/update", ['id' => $request['id'], 'id_categoria' => $request['id_categoria']]);
    }


    public function actionAddCategorias(Request $request)
    {
        if ($request['categoria'] != null) {
            foreach ($request['categoria'] as $categorialId) {
                DB::table('menus_b2b')->insert(['id_menu' => $request["id"], 'id_b2b' => $categorialId]);
            }
        }
        return view("/management/menu/update", ['id' => $request['id']]);

    }


    public function actionRecordMenu(Request $request)
    {
        if (is_null($request['nombre'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['nombre'])) {
            return redirect()->back()->withErrors(array_merge($nombreError));
        }

        $nombreError = array('nombre' => 'Existe este grupo');
        //Si el nombre ya existe no se podara grabar por lo que reenviara un mensaje de error
        $dato = DB::table('menus')->where('nombre', strtoupper($request['nombre']))->get();

        if (count($dato) == 0) {
            DB::table('menus')->insert(array('nombre' => strtoupper($request['nombre'])));
            // asociar todos las categorias al nuevo grupo
            $id_grupo = DB::table('menus')->where('nombre', strtoupper($request['nombre']))->pluck('id');
            $categorias = DB::table('b2bcategorias')->pluck('id');
            $id_grupo = substr($id_grupo, 1, strlen($id_grupo) - 2);
            foreach ($categorias as $categoria) {
                DB::table('menus_b2b')->insert(array('id_menu' => $id_grupo, 'id_b2b' => $categoria));
            }


            return view("/management/menu/menu", ['nombre' => $request['nombre']]);
        } else {
            $nombreError = array('nombre' => 'Nombre repetido');
            return redirect()->back()->withErrors(array_merge($nombreError));
        }
    }

    public function actionSaveCategoria(Request $request)
    {
        if (is_null($request['texto'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['accion'])) {
            $accion = "Ejemplo";
        } else {
            $accion = $request['accion'];
        }
        $categoria = DB::table('b2bcategorias')->orderBy('categoria', 'DESC')->take(1)->pluck('categoria');
        $categoria = substr($categoria, 1, strlen($categoria) - 2);
        if ($categoria==""){
            $cat = 1;
        }else{
            $cat = $categoria + 1;
        }


        $dato = DB::table('b2bcategorias')->where([
            'texto' => $request['texto'],
            'subcategoria1' => null
        ])->pluck('categoria');

        if (count($dato) == 0) {
            DB::table('b2bcategorias')->insert(array(
                'categoria' => $cat,
                'subcategoria1' => null,
                'texto' => $request['texto'],
                'accion' => $accion
            ));

            //asociacion a todos los menus creados
            $id = DB::table('b2bcategorias')->where(['categoria' => $cat, 'subcategoria1' => null])->pluck('id');
            $id = substr($id, 1, strlen($id) - 2);
            $id_grupos = DB::table('grupos')->pluck('id');
            foreach ($id_grupos as $id_grupo) {
                DB::table('menus_b2b')->insert(array('id_menu' => $id_grupo, 'id_b2b' => $id));
            }
            return view("/management/menu/menu");


        } else {
            $nombreError = array('nombre' => 'Nombre repetido');
            return redirect()->back()->withErrors(array_merge($nombreError));
        }


    }

    public function actionSaveSubCategoria(Request $request)
    {
        if (is_null($request['texto'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['accion'])) {
            $accion = "Ejemplo";
        } else {
            $accion = $request['accion'];
        }

        $cuentaCat = DB::table('b2bcategorias')->where(['categoria' => $request['categoria']])->count();


        DB::table('b2bcategorias')->insert(array(
            'categoria' => $request['categoria'],
            'subcategoria1' => $cuentaCat,
            'texto' => $request['texto'],
            'accion' => $accion
        ));
        //asociacion a todos los menus creados
        $id = DB::table('b2bcategorias')->where([
            'categoria' => $request['categoria'],
            'subcategoria1' => $cuentaCat
        ])->pluck('id');
        $id = substr($id, 1, strlen($id) - 2);
        $id_grupos = DB::table('grupos')->pluck('id');

        foreach ($id_grupos as $id_grupo) {
            DB::table('menus_b2b')->insert(array('id_menu' => $id_grupo, 'id_b2b' => $id));
        }
        return view("/management/menu/menu");

    }


    public function actionDeleteCategoria(Request $request)
    {
        //se borran vinculaciones a menu
        $ids = DB::table('b2bcategorias')->where(['categoria' => $request['categoria']])->pluck('id');
        foreach ($ids as $id) {
            DB::table('menus_b2b')->where('id_b2b', $id)->delete();
        }
        DB::table('b2bcategorias')->where(['categoria' => $request["categoria"]])->delete();
        return view("/management/menu/menu");
    }


}