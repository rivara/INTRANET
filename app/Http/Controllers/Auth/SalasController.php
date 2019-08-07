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

    public function actionGoRecordSala(Request $request)
    {

        return view('salas/record', compact('calendar','nombre'),['salaOpcion' => $request['salaOpcion'],'nombre' => $request['nombre']]);
    }

    public function actionGoEditSala(Request $request)
    {
        var_dump($request);
        die();
        return view('salas/edit', compact('calendar','nombre'),['salaOpcion' => $request['salaOpcion'],'nombre' => $request['nombre']]);
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

             $events[] =  Calendar::event( $evento->titulo, //event title
                 true, //full day event?
                 new \DateTime($evento->fecha), //start time (you can also use Carbon instead of DateTime)
                 new \DateTime($evento->fecha), //end time (you can also use Carbon instead of DateTime)
                 null ,
                 [
                     'color' => $evento->color,
                     'url' => '../salas/editSala',
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
                    'url' => '../salas/editSala',
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
            'id_mails'=>1,
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



}