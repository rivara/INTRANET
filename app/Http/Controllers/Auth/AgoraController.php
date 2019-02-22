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
        return view('agora/carga');
       // return view('agora/prueba');
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
        $name = DB::table('archivos')->where('id', $request["id"])->value('nombre');
        Storage::disk('local')->delete($name, 'Contents');
        DB::table('archivos')->where(['id' => $request["id"]])->delete();
        //Eliminar fichero
        return view('agora/docu');
    }


    public function actionDownload(Request $request)
    {
        $name = DB::table('archivos')->where('id', $request["id"])->value('nombre');
        return Storage::download($name);
    }




    public function multifileupload()
    {
        return view('dropzoneJs');
    }

    public function store(Request $request)
    {
        $image = $request->file('file');
        $imageName = time() . $image->getClientOriginalName();
        $upload_success = $image->move(public_path('images'), $imageName);

        if ($upload_success) {
            return response()->json($upload_success, 200);
        } // Else, return error 400
        else {
            return response()->json('error', 400);
        }

    }


















}