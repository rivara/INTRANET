@extends('layouts.app')
@section('content')
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
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle width100px" data-toggle="dropdown"
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
                    <form action="{{ route('goRecordSala') }}" method="GET">
                        <button class="btn btn-primary  floatRight">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <input type="hidden" name="nombre"value={{$nombre}}>
                        <input type="hidden" name="salaOpcion" value={{$salaOpcion}}>
                    </form>
                @endif
            </div>
        </div>

        @if(isset($salaOpcion))

            <div class="panel panel-primary">
              <div class="panel-heading"></div>
                <!-- Hay que inicializar --->
                <script>
                $('#calendar').fullCalendar({
                events: [
                {
                    title: 'All Day Event',
                    start: '2020-01-25',
                    end: '2020-01-25',
                    url: 'http://google.com'
                }
                ]});
                </script>
              <div class="panel-body"> {!! $calendar->calendar() !!} {!! $calendar->script() !!}

              </div>
            </div>
        @endif
    </div>
@endsection

