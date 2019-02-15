<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 06/02/2019
 * Time: 13:56
 */

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Storage;
use App\Upload;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use Webpatser\Uuid\Uuid;
class AgoraController
{

    public function actionGoAddFile()
    {
        return view('agora/carga2');
    }

    public function  actionBackAgora()
    {
        return view('agora/docu');
    }

    public function upload(Request $request)
    {
        $descripcion=$request['descripcion'];
        Storage::disk('local')->put($request['file'], 'Contents');
        $size = Storage::size($request['file']);
        $todayDate = date("Y-m-d");
        $otros= 'Fecha:'.$todayDate.' Peso:'.$size." Kb";
        DB::table('archivos')->insert(array('descripcion' =>strtoupper($descripcion),'nombre'=>$request['file'],'otros'=>$otros));
        return view('agora/docu');
    }


    public function actionDeleteFile(Request $request)
    {
        echo $request['id'];
        Storage::delete('file.jpg');
        DB::table('archivos')->where(['id' => $request["id"]])->delete();
        //eliminar fichero

        return view('agora/docu');
    }




}