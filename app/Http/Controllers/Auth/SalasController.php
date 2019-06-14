<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 12/06/2019
 * Time: 8:30
 */
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;




class SalasController
{

    public function actionGoEditSala(Request $request){
        $nombre= $request['nombre'];
       // return view('salas/edit',['nombre' => $request['nombre']]);
        // recoge
        $events = [];
        $calendar = \Calendar::addEvents($events);
        return view('salas/edit', compact('calendar','nombre'));


    }


    public function actionGoIndexSala(Request $request){
        $nombre= $request['nombre'];
       // return view('salas/index',['nombre' => $request['nombre']]);
        $events = [];
        $calendar = \Calendar::addEvents($events);
        return view('salas/index', compact('calendar','nombre'));
    }
}