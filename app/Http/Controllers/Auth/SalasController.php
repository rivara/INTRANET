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
            "Evento", //event title
            true, //full day event?
            new \DateTime('2019-06-14'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2019-06-14'), //end time (you can also use Carbon instead of DateTime)
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
            "Evento", //event title
            true, //full day event?
            new \DateTime('2019-06-14'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2019-06-14'), //end time (you can also use Carbon instead of DateTime)
            null ,
            [
                'color' => '#f05050',
                'url' => 'salas/edit',
            ]

        );

        $calendar = \Calendar::addEvents($events);
        return view('salas/index', compact('calendar','nombre'));
    }
}