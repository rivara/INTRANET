@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/calendar/bootstrap.min.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>
            <div class="col-md-4 ">
                <h2>Reserva de salas</h2>
            </div>
            <div class="col-md-4 ">
                <select class="form-control" id="exampleFormControlSelect1">
                    <option>sala1</option>
                    <option>sala2</option>
                    <option>sala3</option>
                </select>
            </div>

            <div class="col-md-2">
                <form action="{{ route('goEditSala') }}" method="GET">
                    <button class="btn btn-primary  floatRight">editar</button>
                    <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
                </form>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"> </div>
            <div class="panel-body"> {!! $calendar->calendar() !!} {!! $calendar->script() !!} </div>
        </div>
    </div>


@endsection
