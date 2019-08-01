
@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">



    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>

            <div class="col-md-8">
                <h2>
                    @if(isset($salaOpcion))
                        Reserva de la sala {{$salaOpcion}} &nbsp;
                    @endif
                        &nbsp;
                </h2>
            </div>

            <div class="col-md-1">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"> salas
                        <span class="fa fa-caret-down"></span>
                    </button>
                    <div class="dropdown-menu">
                        <?php   $salas = DB::table('salas')->get();?>
                        @foreach($salas as $sala)
                            <form action="{{ route('goIndexSala') }}" method="GET">
                                <button class="dropdown-item" href="#">{{$sala->nombre}}</button>
                                <input type="hidden" name="salaOpcion" value={{$sala->nombre}}>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>


            <div class="col-md-1">

                @if(isset($salaOpcion))
                    <form action="{{ route('goEditSala') }}" method="GET">
                        <button class="btn btn-primary  floatRight">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
                        <input type="hidden" name="salaOpcion" value={{$salaOpcion}}>
                    </form>
                @endif
            </div>
        </div>


        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body"> {!! $calendar->calendar() !!} {!! $calendar->script() !!} </div>
        </div>
    </div>
@endsection
