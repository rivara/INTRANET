@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/calendar/bootstrap.min.css') }}" rel="stylesheet">

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>
            <div class="col-md-3">
                <h2>Reserva de salas</h2>
            </div>


            <div class="col-md-3">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Salas
                    </button>
                    <div class="dropdown-menu" >
                    <?php   $salas=DB::table('salas')->get();?>
                      @foreach($salas as $sala)
                            <form action="{{ route('goIndexSala') }}" method="GET">
                                <button class="dropdown-item" href="#">{{$sala->nombre}}</button>
                                <input type="hidden" name="salaOpcion" value={{$sala->nombre}}>
                            </form>
                        @endforeach
                      </div>
                </div>
            </div>
            <div class="col-md-2">
                <form action="{{ route('goEditSala') }}" method="GET">
                    <button class="btn btn-primary  floatRight">editar</button>
                    <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
                </form>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="panel panel-primary">
                    <div class="panel-heading"></div>
                    <div class="panel-body"> {!! $calendar->calendar() !!} {!! $calendar->script() !!} </div>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>


@endsection
