<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 17/12/2018
 * Time: 14:13
 */

namespace App\Http\Controllers\Auth;
use DB;
use Illuminate\Http\Request;

class PortalsController
{

    public function admin(Request $request)
    {
        return view("/management/portals/portals");
    }

    public function actionCreatePortal()
    {
        return view("/management/portals/create");
    }


    public function actionRecord(Request $request){



        $nombreError = array('nombre' => ' ');
        $urlError = array('url' => ' ');


        if (is_null($request['nombre'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['url'])) {
            $urlError = array('url' => 'No debe ser vacio');
        }
        if (is_null($request['nombre']) || is_null($request['url'])) {
            return redirect()->back()->withErrors(array_merge($nombreError, $urlError));
        }


        $nombreError = array('nombre' => 'Existe este portal');
        // si el nombre ya existe no se podara grabar por lo que reenviara un mensaje de error
        $dato=DB::table('portales')->where('nombre',strtoupper($request['nombre']))->get();

        if(count($dato)== 0) {

            DB::table('portales')->insert(array('nombre' =>strtoupper($request['nombre']),'url'=>$request['url'],'icono'=>$request['icono'],'target'=>$request['target']));
            return view("/management/portals/portals",['nombre' => $request['nombre']]);
        }else{
            return redirect()->back()->withErrors(array_merge($nombreError));
        }
    }



    public function actionUpdatePortal(Request $request)
    {
        DB::table('portales')->where('id', $request['id'])->update(['url' => $request['url'],'nombre'=>$request['nombre'],'icono'=>$request['icono'],'target'=>$request['target']]);
        return view("/management/portals/portals");
    }



    public function actionGoUpdatePortal(Request $request)
    {
        return view("/management/portals/update",['portalId'=>$request['portalId']]);
    }

    public function actionDeletePortal(Request $request){
        //Borrar grupos
        DB::table('portales')->where('id',$request["portalId"])->delete();
        //Borrar usuarios-grupos
        DB::table('grupos_portales')->where('id_portal',$request["portalId"])->delete();
        return view("/management/portals/portals");

    }


}