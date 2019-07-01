<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 12/06/2019
 * Time: 8:30
 */
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use MaddHatter\LaravelFullcalendar\Calendar;



class SalasController
{

    public function actionGoEditSala(Request $request){
        $nombre= $request['nombre'];
       // return view('salas/edit',['nombre' => $request['nombre']]);
        // recoge
        $events[] = Calendar::event(
            "Evento1", //event title
            true, //full day event?
            new \DateTime('2019-06-14'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2019-06-14'), //end time (you can also use Carbon instead of DateTime)
            null ,
            [
                'color' => '#f05050',
                'url' => 'salas/edit',
            ],

            "Evento2", //event title
            true, //full day event?
            new \DateTime('2019-06-16'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2019-06-16'), //end time (you can also use Carbon instead of DateTime)
            null ,
            [
                'color' => '#f05050',
                'url' => 'salas/edit',
            ]

        );

        $calendar = \Calendar::addEvents($events);
        return view('salas/edit', compact('calendar','nombre'));


    }


    public function actionGoIndexSala(Request $request){
        $nombre= $request['nombre'];
       // return view('salas/index',['nombre' => $request['nombre']]);

        $events[] = Calendar::event(
            "Evento1", //event title
            true, //full day event?
            new \DateTime('2019-06-14'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2019-06-14'), //end time (you can also use Carbon instead of DateTime)
            null ,
            [
                'color' => '#f05050',
                'url' => 'salas/edit',
            ]);


        $events[] =  Calendar::event( "Evento2", //event title
            true, //full day event?
            new \DateTime('2019-06-16'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2019-06-16'), //end time (you can also use Carbon instead of DateTime)
            null ,
            [
                'color' => '#f05050',
                'url' => 'salas/edit',
            ]);

            // graba
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



        $calendar = \Calendar::addEvents($events);
        return view('salas/index', compact('calendar','nombre'));
    }
}