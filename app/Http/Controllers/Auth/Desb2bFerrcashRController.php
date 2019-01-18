<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 03/01/2019
 * Time: 16:43
 */

namespace App\Http\Controllers\Auth;
use App\Library\WebAdmLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;



class Desb2bFerrcashRController
{
    public function prueba(Request $request)
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
        $request['oAccion']="listado";
        $collection = collect($logs);
        $page = $request['page'];
        $perPage = 10;
        $paginate = new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage, $page, ['path'=>url('/desb2b/prueba')]);
        return view('/desb2b-Ferrcash/prueba', ['oAccion' =>  $request['oAccion'],'logs'=>$paginate,'email'=>$request['email'],'fechaDesde'=>$fechaDesde,
            'fechaHasta'=>$fechaHasta,'empresa'=>$empresa,'cdclien'=>$cdclien,'cdsucur'=>$cdsucur,'seccion'=>$seccion,'des'=>$des,'userMag'=>$userMag]);
    }

    public function backb2b(Request $request)
    {
        return view('/desb2b-Ferrcash/prueba',['oAccion' => 'inicio']);
    }

}