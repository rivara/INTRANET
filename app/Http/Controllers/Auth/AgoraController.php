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
use Response;
use Session;
use Webpatser\Uuid\Uuid;
class AgoraController
{

    public function actionGoAddFile(Request $request)
    {
        return view('agora/carga', ['id_usuario' => $request['id_usuario']]);
       // return view('agora/prueba');
    }

    public function  actionBackAgora(Request $request)
    {
        return view('agora/docu', ['id_usuario' => $request['id_usuario']]);
    }

    public function upload(Request $request)
    {

        $descripcion=$request['descripcion'];
        Storage::disk('local')->put($request['file'], 'Contents');
        $size = Storage::size($request['file']);
        $todayDate = date("Y-m-d");
        $otros= 'Fecha:'.$todayDate.' Peso:'.$size." Kb";
        //icon + color
        $ext = pathinfo($request['file'], PATHINFO_EXTENSION);
        $formato="fa fa-file-o text-info";
        switch ($ext) {
            //formato imagen
            case "jpg":
                $formato="fa fa-file-image-o text-danger";
                break;
            case "png":
                $formato="fa fa-file-image-o text-danger";
                break;
            case "bmp":
                $formato="fa fa-file-image-o text-danger";
                break;
            //formato video
            case "mp4":
                $formato="fa fa-file-video-o text-warning";
                break;
            case "avi":
                $formato="fa fa-file-video-o text-warning";
                break;

            //formato texto
            case "txt":
                $formato="fa fa-file-text-o text-primary";
                break;
            case "doc":
                $formato="fa fa-file-word-o  text-primary";
                break;
            case "docx":
                $formato="fa fa-file-word-o text-primary";
                break;

            //formato xls
            case "xls":
                $formato="fa fa-file-excel-o  text-success";
                break;
            //formato pdf
            case "pdf":
                $formato="fa fa-file-pdf-o  text-danger";
                break;
            //formato zip
            case "zip":
                $formato="fa fa-file-archive-o text-secondary";
                break;
            case "rar":
                $formato="fa fa-file-archive-o  text-secondary";
                break;
        }




        DB::table('archivos')->insert(array('descripcion' =>strtoupper($descripcion),'nombre'=>$request['file'],'otros'=>$otros,'formato'=>$formato));
        return view('agora/docu', ['id_usuario' => $request['id_usuario']]);
    }


    public function actionDeleteFile(Request $request)
    {
        $name = DB::table('archivos')->where('id', $request["id"])->value('nombre');
        Storage::disk('local')->delete($name, 'Contents');
        DB::table('archivos')->where(['id' => $request["id"]])->delete();
        //Eliminar fichero
        return view('agora/docu', ['id_usuario' => $request['id_usuario']]);
    }



    public function actionDownload(Request $request)
    {
        $name = DB::table('archivos')->where('id', $request["id"])->value('nombre');
        $file= Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($name);
        return Response::download($file, $name);

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