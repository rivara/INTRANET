<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 12/06/2019
 * Time: 8:30
 */
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MaddHatter\LaravelFullcalendar\Calendar;



class SalasController
{

    /////////////////////////////////////////////// GESTION salas

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

    public function actionCreateRoom(Request $request)
    {
        $nombreError = array('nombre' => ' ');
        $capacidadError  = array('capacidad' => ' ');
        $datosError = array('datos' => ' ');

        if (is_null($request['nombre'])) {
            $nombreError = array('nombre' => 'No debe ser vacio');
        }
        if (is_null($request['capacidad'])) {
            $capacidadError = array('capacidad' => 'No debe ser vacio');
        }
        if (is_null($request['datos'])) {
            $datosError = array('datos' => 'No debe ser vacio');
        }
        if (is_null($request['nombre']) || is_null($request['capacidad']) || is_null($request['datos'])) {
            return redirect()->back()->withErrors(array_merge($nombreError, $capacidadError,$datosError));
        }

        $nombreError = array('nombre' => 'Existe este portal');
        // si el nombre ya existe no se podara grabar por lo que reenviara un mensaje de error
        $dato=DB::table('salas')->where('nombre',strtoupper($request['nombre']))->get();


        if(count($dato)== 0) {
            DB::table('salas')->insert(array('nombre' =>strtoupper($request['nombre']),'capacidad'=>$request['capacidad'],'datos'=>$request['datos']));
            return view("/management/room/room");
        }else{
            return redirect()->back()->withErrors(array_merge($nombreError));
        }

    }



//  Update pending

//???????????????????????????????????????????????????????????????????????????????????????????

    public function actionUpdateRoom(Request $request)
    {
     //   DB::table('portales')->where('id', $request['id'])->update(['url' => $request['url'],'nombre'=>$request['nombre'],'icono'=>$request['icono'],'target'=>$request['target']]);
      //  return view("/management/portals/portals");

    }


    public function actionDeleteRoom(Request $request)
    {

        DB::table('salas')->where('id',$request["salaId"])->delete();
        return view("/management/room/room");
    }




    ////////////////////////////////////////////////////// GESTION citas

    public function actionGoRecordSala(Request $request)
    {
        return view('salas/record', compact('calendar','nombre'),['salaOpcion' => $request['salaOpcion'],'nombre' => $request['nombre']]);
    }

    public function actionGoEditSala(Request $request)
    {
        $fecha=DB::table('reservas')->where('id',$request['id'])->pluck('fecha');
        $desde=DB::table('reservas')->where('id',$request['id'])->pluck('hora_inicio');
        $hasta=DB::table('reservas')->where('id',$request['id'])->pluck('hora_fin');
        $titulo=DB::table('reservas')->where('id',$request['id'])->pluck('titulo');
        $descripcion=DB::table('reservas')->where('id',$request['id'])->pluck('descripcion');
        $color=DB::table('reservas')->where('id',$request['id'])->pluck('color');
        $mails=DB::table('reservas')->where('id',$request['id'])->pluck('mails');


        return view('salas/editSala', compact('calendar','nombre'),[
            'salaOpcion' => $request['salaOpcion'],
            'nombre' => $request['nombre'],
            'id' => $request['id'],
            'fecha'=>$fecha[0],
            'desde'=>$desde[0],
            'hasta'=>$hasta[0],
            'titulo'=>$titulo[0],
            'descripcion'=>$descripcion[0],
            'mails'=>$mails[0],
            'color'=>$color[0],
        ]);
    }






    public function actionGoIndexSala(Request $request){
        $nombre= $request['salaOpcion'];
        $sala_id=DB::table('salas')->where('nombre',$nombre)->pluck('id');
        $n_eventos=DB::table('reservas')->where('sala',$sala_id)->count();
        $eventos=DB::table('reservas')->where('sala',$sala_id)->get();

        /////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////
        // añadir hora
        if($n_eventos != 0 ){
        foreach($eventos as $evento) {

             $events[] =  Calendar::event($evento->titulo, //event title
                 true, //full day event?
                 new \DateTime($evento->fecha), //start time (you can also use Carbon instead of DateTime)
                 new \DateTime($evento->fecha), //end time (you can also use Carbon instead of DateTime)
                 null ,
                 [
                     'color' => $evento->color,
                     'url' => '../salas/editSala?id='.$evento->id."&salaOpcion=".$request['salaOpcion'],

                 ]);
         }
        }else{
            $events[] = \Calendar::event("Evento2", //event title
                true, //full day event?
                new \DateTime('2019-08-10'), //start time (you can also use Carbon instead of DateTime)
                new \DateTime('2019-08-10'), //end time (you can also use Carbon instead of DateTime)
                null ,
                [
                    'color' => '#f05050',


                ]);
        }


       // busco
       $calendar = \Calendar::addEvents($events);
       return view('salas/index', compact('calendar','nombre'),['salaOpcion' => $request['salaOpcion'],'nombre' => $request['nombre']]);
    }



////////////////////////////////////// EDITAR AGENDA
    public function actionRecordSala(Request $request){
      $fecha=DB::table('reservas')->where('fecha',$request['fecha'])->pluck('id');

        // si el dia coincide y la hora desde y hasta se solapa con otra saltar error
        if(!empty($fecha)){
                $horaDesde = DB::table('reservas')->where('fecha',$request['fecha'])->pluck('hora_inicio');
                $horaHasta = DB::table('reservas')->where('fecha',$request['fecha'])->pluck('hora_fin');

                if(($horaDesde>=$request['fecha'])and($horaHasta<=$request['fecha'])){
                     // devolver error esa fecha ya esta pillada
                    //     Acabar el lunes
                }
            }

        $sala=DB::table('salas')->where('nombre',$request['salaOpcion'])->pluck('id');
        DB::table('reservas')->insert(array(
            'fecha' =>$request['fecha'],
            'hora_inicio'=>$request['horaDesde'],
            'hora_fin'=>$request['fechaHasta'],
            'titulo'=>$request['titulo'],
            'descripcion'=>$request['descripcion'],
            'mails'=>$request['mails'],
            'otros'=>'',
            'sala'=>print_r($sala),
            'color'=>'#F64F2C',
        ));

        ////////////////////////////////////////// calendario
        $nombre= $request['salaOpcion'];
        $sala_id=DB::table('salas')->where('nombre',$nombre)->pluck('id');
        $n_eventos=DB::table('reservas')->where('sala',$sala_id)->count();
        $eventos=DB::table('reservas')->where('sala',$sala_id)->get();

        /////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////
        // añadir hora
        if($n_eventos != 0 ){
            foreach($eventos as $evento) {

                $events[] =  Calendar::event( $evento->titulo, //event title
                    true, //full day event?
                    new \DateTime($evento->fecha), //start time (you can also use Carbon instead of DateTime)
                    new \DateTime($evento->fecha), //end time (you can also use Carbon instead of DateTime)
                    null ,
                    [
                        'color' => $evento->color,
                        'url' =>"/salas/edit",
                        'id'=>$evento->id,
                    ]);
            }
        }else{
            $events[] =  Calendar::event( "Evento2", //event title
                true, //full day event?
                new \DateTime('2019-08-10'), //start time (you can also use Carbon instead of DateTime)
                new \DateTime('2019-08-10'), //end time (you can also use Carbon instead of DateTime)
                null ,
                [
                    'color' => '#f05050',
                    'url' =>"/salas/edit",
                ]);
        }

        // busco
        $calendar = \Calendar::addEvents($events);
        return view('salas/index', compact('calendar','nombre'),['salaOpcion' => $request['salaOpcion'],'nombre' => $request['nombre']]);

    }


    public function actionCreateSala(Request $request){
      die("llega");
    }



}
