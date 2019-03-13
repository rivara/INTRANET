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



}