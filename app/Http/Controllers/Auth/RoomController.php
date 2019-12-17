<?php


namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;

class RoomController
{
    public function actionGoRoom(Request $request)
    {
        return view("/management/room/room");
    }



    public function actionGoUpadeteRoom(Request $request)
    {
        return view("/management/room/update");
    }



    public function actionGoCreateRoom(Request $request)
    {
        return view("/management/room/create");
    }


    /*
    public function actionRecord(Request $request){

        $nombreError = array('nombre' => ' ');
        $urlError = array('url' => ' ');
        $iconoError = array('icono' => ' ');

        if (is_null($request['nombre'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['url'])) {
            $urlError = array('url' => 'No debe ser vacio');
        }
        if (is_null($request['icono'])) {
            $iconoError = array('icono' => 'No debe ser vacio');
        }
        if (is_null($request['nombre']) || is_null($request['url']) || is_null($request['icono'])) {
            return redirect()->back()->withErrors(array_merge($nombreError, $urlError,$iconoError));
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
*/
}
