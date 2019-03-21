<?php

namespace App\Http\Controllers\Auth;

use DB;
use Illuminate\Http\Request;

class GroupsController
{

    public function actionAdminGroups(Request $request)
    {

        return view("/management/groups/groups", ['nombre' => $request['nombre']]);
    }

    public function actionCreateGroup(Request $request)
    {
        return view("/management/groups/createGroups",
            ['$grupoId' => $request['$grupoId'], 'nombre' => $request['nombre']]);
    }


    public function actionRecordGroup(Request $request)
    {
        if (is_null($request['nombre'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['nombre'])) {
            return redirect()->back()->withErrors(array_merge($nombreError));
        }
        $nombreError = array('nombre' => 'Existe este grupo');
        //Si el nombre ya existe no se podara grabar por lo que reenviara un mensaje de error
        $dato = DB::table('grupos')->where('nombre', strtoupper($request['nombre']))->get();

        if (count($dato) == 0) {
            DB::table('grupos')->insert(array('nombre' => strtoupper($request['nombre'])));
            return view("/management/groups/groups", ['nombre' => $request['nombre']]);
        } else {
            return redirect()->back()->withErrors(array_merge($nombreError));
        }
    }


    public function actionGoUpdateGroup(Request $request)
    {
        if ($request->submit == "Edit") {
            //Envia parametros a la pantalla de grabar
            return view("/management/groups/updateGroups", ['grupoId' => $request['grupoId']]);
        }
    }

    public function actionGoAddPortalGroup(Request $request)
    {
        //Añade portales
        $portalesId = DB::table('grupos_portales')->where('id_grupo', $request['grupoId'])->pluck('id_portal');

        //Portales en los que NO estan
        $portalTotales = DB::table('portales')->whereNotIn('id', $portalesId)->get();
        return view("/management/groups/portalAdd", ['portales' => $portalTotales, 'grupoId' => $request['grupoId'], 'nombre' => $request['nombre']]);
    }


    public function actionAddPortalGroup(Request $request)
    {
        //Vinculo los portales a los grupos
        if ($request['portal']!=null){
            foreach ($request['portal'] as $portalId) {
                DB::table('grupos_portales')->insert(['id_grupo' => $request["grupoId"], 'id_portal' => $portalId]);
            }
        }
        return view("/management/groups/updateGroups", ['grupoId' => $request['grupoId']]);

    }


    public function actionDeleteGroupPortal(Request $request)
    {
        DB::table('grupos_portales')->where(['id_grupo' => $request["grupoId"], 'id_portal' => $request['portalId']])->delete();
        return view("/management/groups/updateGroups", ['grupoId' => $request['grupoId']]);
    }


    public function actionDeleteGroup(Request $request)
    {

        //Borrar grupos
        DB::table('grupos')->where('id', $request["grupoId"])->delete();
        //Borrar usuarios-grupos
        DB::table('usuarios_grupos')->where('id_grupo', $request["grupoId"])->delete();
        return view("/management/groups/groups", ['grupoId' => $request['grupoId']]);
    }


    public function actionUpdateGroups(Request $request)
    {
        DB::table('grupos')->where('id', $request['grupoId'])->update(['nombre' => $request['grupoNombre']]);
        return view("/management/groups/updateGroups", ['grupoId' => $request['grupoId']]);
    }

}