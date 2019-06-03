<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 06/02/2019
 * Time: 13:56
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use App\Upload;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Response;
use Session;
use Webpatser\Uuid\Uuid;

class BibliotecaController
{

    public function actionGoAddFile(Request $request)
    {
        return view('biblioteca/ficheros', [
            'id_usuario' => $request['id_usuario'],
            'id_subgrupo' => $request['id_subgrupo'],
            'id_grupo' => $request['id_grupo']
        ]);
    }

    public function actionBackAgora(Request $request)
    {
        return view('biblioteca/carpeta', ['id_usuario' => $request['id_usuario']]);
    }


    public function actionGoAddSubGroup(Request $request)
    {
        return view('biblioteca/subgrupoAdd', [
            'id_usuario' => $request['id_usuario'],
            'id_grupo' => $request['id_grupo'],
            'id_subgrupo' => $request['id_subgrupo']
        ]);
    }


    public function actionBackCarpeta(Request $request)
    {
        return view('biblioteca/carpetas', ['id_usuario' => $request['id_usuario']]);
    }

    public function actionGoSubGroup(Request $request)
    {
        return view('biblioteca/subgrupo', [
            'id_usuario' => $request['id_usuario'],
            'id_grupo' => $request['id_grupo'],
            'id_subgrupo' => $request['id_subgrupo']
        ]);
    }


    public function actionGoSubCarpeta(Request $request)
    {
        return view('biblioteca/subcarpetas', ['id_usuario' => $request['id_usuario'], 'id_grupo' => $request['id_grupo']]);
    }


    public function actionUpload(Request $request)
    {
        //get file extension
        $ext = $request->file('file')->getClientOriginalExtension();

        //filename to store
        $filename =$request->file('file')->getClientOriginalName();

        $fileLength=$request->file('file')->getSize();
       /* if($fileLength>30){
            die("no llega");
        }*/
        //Upload File to external server
        Storage::disk('local')->put( $filename, fopen($request->file('file'), 'r+'));
        $path = Storage::disk('local')->getAdapter()->getPathPrefix();
        $descripcion="";

        if (! is_Null($request['descripcion'])){
            $descripcion = $request['descripcion'];
        }



        $size = $request->file('file')->getsize();
        $todayDate = date("Y-m-d");
        $otros = 'Fecha:' . $todayDate . ' Peso:' . $size . " Kb";
        //icon + color
        $formato = "fa fa-file-o text-info";
        switch ($ext) {
            //formato imagen
            case "jpg":
                $formato = "fa fa-file-image-o text-danger";
                break;
            case "png":
                $formato = "fa fa-file-image-o text-danger";
                break;
            case "bmp":
                $formato = "fa fa-file-image-o text-danger";
                break;
            //formato video
            case "mp4":
                $formato = "fa fa-file-video-o text-warning";
                break;
            case "avi":
                $formato = "fa fa-file-video-o text-warning";
                break;
            //formato texto
            case "txt":
                $formato = "fa fa-file-text-o text-primary";
                break;
            case "doc":
                $formato = "fa fa-file-word-o  text-primary";
                break;
            case "docx":
                $formato = "fa fa-file-word-o text-primary";
                break;

            //formato xls
            case "xls":
                $formato = "fa fa-file-excel-o  text-success";
                break;
            //formato pdf
            case "pdf":
                $formato = "fa fa-file-pdf-o  text-danger";
                break;
            //formato zip
            case "zip":
                $formato = "fa fa-file-archive-o text-secondary";
                break;
            case "rar":
                $formato = "fa fa-file-archive-o  text-secondary";
                break;
        }
        // descripcion vacia
         if(empty($descripcion)){
             $descripcion = " ";
         }
        DB::table('archivos')->insert(array(
            'descripcion' => $descripcion,
            'nombre' => $request->file('file')->getClientOriginalName(),
            'otros' => $otros,
            'formato' =>$formato,
            'id_subgrupo' => $request['id_subgrupo']
        ));


        return view('biblioteca/subgrupo', [
            'id_usuario' => $request['id_usuario'],
            'id_grupo' => $request['id_grupo'],
            'id_subgrupo' => $request['id_subgrupo']
        ]);
    }


    public function actionDeleteFile(Request $request)
    {
        $name = DB::table('archivos')->where('id', $request["id"])->value('nombre');
        Storage::disk('local')->delete($name, 'Contents');
        DB::table('archivos')->where(['id' => $request["id"]])->delete();
        //Eliminar fichero
        return view('biblioteca/subgrupo', [
            'id_usuario' => $request['id_usuario'],
            'id_grupo' => $request['id_grupo'],
            'id_subgrupo' => $request['id_subgrupo']
        ]);
    }


    public function actionDownload(Request $request)
    {
        $name = DB::table('archivos')->where('id', $request["id"])->value('nombre');
        //recoje mal la extension del fichero
         $myFile = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($name);
        return Response::download($myFile);

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


    public function actionSubGroupRecord(Request $request)
    {
        $nombreError = array('nombre' => ' ');
        if (is_null($request['nombre'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['nombre'])) {
            return redirect()->back()->withErrors(array_merge($nombreError));
        }
        $nombreError = array('nombre' => 'Existe este portal');
        // si el nombre ya existe no se podara grabar por lo que reenviara un mensaje de error
        $id_subgrupo = DB::table('subgrupos')->where('nombre', strtoupper($request['nombre']))->pluck('id');

        if (count($id_subgrupo) == 0) {
            //grabo el subgrupo
            DB::table('subgrupos')->insert(array('nombre' => $request['nombre']));
            $id_subgrupo = DB::table('subgrupos')->where('nombre', $request['nombre'])->pluck('id');
            // lo relaciono con el subgrupo
            DB::table('grupos_subgrupos')->insert(array(
                'id_grupo' => $request['id_grupo'],
                'id_subgrupo' => $id_subgrupo[0]
            ));
            return view('biblioteca/subcarpetas', [
                'id_usuario' => $request['id_usuario'],
                'id_grupo' => $request['id_grupo'],
                'id_subgrupo' => $request['id_subgrupo']
            ]);
        } else {
            return redirect()->back()->withErrors(array_merge($nombreError));
        }
    }


    public function actionSubGroupDelete(Request $request)
    {
        //Elimino tabla grupo_subgrupo
        DB::table('grupos_subgrupos')->where('id_subgrupo',$request["id_subgrupo"])->delete();
        //Elimino la tabla grupo
        DB::table('subgrupos')->where('id', $request["id_subgrupo"])->delete();
        //Elimino archivos -> esto podriamos tenerlo en una carpeta old
        DB::table('archivos')->where('id_subgrupo', $request["id_subgrupo"])->delete();
        return view('biblioteca/subcarpetas', [
            'id_usuario' => $request['id_usuario'],
            'id_grupo' => $request['id_grupo'],
            'id_subgrupo' => $request['id_subgrupo']
        ]);
    }
}