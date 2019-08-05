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




    public function actionGoIndexSala(Request $request){
        $nombre= $request['nombre'];


        $events[] =  Calendar::event( "Evento2", //event title
            true, //full day event?
            new \DateTime('2019-08-10'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2019-08-10'), //end time (you can also use Carbon instead of DateTime)
            null ,
            [
                'color' => '#f05050',
                'url' => 'salas/edit',
            ]);


        /*
         *  DB::table('usuarios')->insert(array(
                'id' => $id,
                'nombre' => $request['usuario'],
                'email' => $request['email'],
                'clave' => $clave,
                'id_empresa' => $request['idEmpresa'],
                'id_menu' => $request["id_menu"]
            ));
         * */

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

        echo $request['titulo'];
        echo $request['descricion'];
        echo $request['color'];
        echo $request['mails'];

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
        //die("llega");
        return view('salas/index', compact('calendar','nombre'),['salaOpcion' => $request['salaOpcion'],'nombre' => $request['nombre']]);

    }



}