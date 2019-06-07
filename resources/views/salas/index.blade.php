<!doctype html>

<html lang="en">

<head>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

</head>

<body>

<div class="container">

    <div class="panel panel-primary">

        <div class="panel-heading">

            MY Calender

        </div>

        <div class="panel-body" >

            {!! $calendar->calendar() !!}

            {!! $calendar->script() !!}

        </div>

    </div>

</div>


@extends('layouts.app')
@section('content')





    <div class="container">
        <div class="row">
            <div class="col-md-1">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>
            <div class="col-md-11 paddingLeft30">
                <h2>Reserva de salas</h2>
            </div>
        </div>
    </div>
    <br><br/>
    <div class="container">

            <div class="row">
                <div class="col-md-4" style="background-color: red">
                    <select class="form-control" id="exampleFormControlSelect1">
                        <option>sala1</option>
                        <option>sala2</option>
                        <option>sala3</option>
                    </select>

                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Example multiple select</label>
                        <select multiple class="form-control" id="exampleFormControlSelect2">
                            <option>persona1@persona.com</option>
                            <option>person2@persona.com</option>
                            <option>persona3@persona.com</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-8" >

                    <div id="schedule-demo"></div>
                </div>
            </div>

    </div>
@endsection
