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
        $fechaDesde= $request['fechaDesde'];
        $fechaHasta= $request['fechaHasta'];
        $empresa=$admLog->empresa($request['empresa']);
        $cdclien=$admLog->cdclien($request['cdclien']);
        $cdsucur=$admLog->cdsucur($request['cdsucur']);
        $seccion=$admLog->seccion($request['seccion']);
        $des=$admLog->des($request['des']);
        $userMag=$admLog->userMag($request['userMag']);
        $logs=$admLog->getRegistros($fechaDesde, $fechaHasta,null, null);
        $collection = collect($logs);
        $page = $request['page'];
        $perPage = 10;
        $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage, $page, ['path'=>url('/WebAdmLog?oAccion=listado&id_usuario=[1]')]);
        return view('/desb2b/WebAdmLog', ['oAccion' =>  $request['oAccion'],'logs'=>$paginate,'email'=>$request['email'],'fechaDesde'=>$fechaDesde,
            'fechaHasta'=>$fechaHasta,'empresa'=>$empresa,'cdclien'=>$cdclien,'cdsucur'=>$cdsucur,'seccion'=>$seccion,'des'=>$des,'userMag'=>$userMag,'id_usuario'=>$request['id_usuario']]);

    }

    public function actionindex(Request $request)
    {
       return view('/desb2b/Index',['oAccion' => 'inicio','id_usuario'=>$request['id_usuario']]);
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
        return view("/management/menu/update",['id'=>$request['id']]);
    }

    public function actionGoMenuAdd(Request $request)
    {
        return view("/management/menu/menuAdd",['id'=>$request['id']]);
    }

    /*BORRAR grupo*/
    public function actionDeleteMenu (Request $request)
    {
        return "borra el elemento ".$request["id"];
        //Borrar grupos
       // DB::table('portales')->where('id',$request["portalId"])->delete();
        //Borrar usuarios-grupos
       // DB::table('grupos_portales')->where('id_portal',$request["portalId"])->delete();
       // return view("/management/portals/portals");
    }

    /*BORRAR categoria*/
    public function actionDeleteMenuCategoria (Request $request)
    {
        return "borra el elemento " . $request["id"];
    }

    /*BORRAR subcategoria*/
    public function actionDeleteMenuSubCategoria (Request $request)
    {
        return "borra el elemento " . $request["id"];
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
        $dato = DB::table('menu')->where('nombre', strtoupper($request['nombre']))->get();



        if (count($dato) == 0) {
            DB::table('menu')->insert(array('nombre' => strtoupper($request['nombre'])));
            return view("/management/menu/menu", ['nombre' => $request['nombre']]);
        } else {
            return redirect()->back()->withErrors(array_merge($nombreError));
        }
    }






}