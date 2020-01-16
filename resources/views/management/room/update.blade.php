@extends('layouts.app')
@section('content')


    <div class=" paddingLeft50px" >
        <form   action="{{ route('goPortals') }}" method="GET">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                <i class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id" value=1>
            <input type="hidden" name="name" value="" style="display:none;">
        </form>
    </div>
    <h1 style="text-align: center">&nbsp;Modificar Portales</h1>
    <br>
    <div class="container wrapper mitad ">
        <?php $salas = DB::table('salas')->where('id',$salaId)->get(); ?>
        <form id="logout-form" action="{{route('updateRoom')}}" method="get">
            <h3>PORTAL</h3>
            <label for="Nombre">Nombre</label>
            <input type="text" class="form-control width300px" name="nombre" value="{{$salas[0]->nombre}}">
            @if ($errors->has('nombre'))
                <span class="error">
                    <strong>{{ $errors->first('nombre') }}</strong>
                </span>
            @endif
            <br>
            <input type="text" class="form-control width300px" name="capacidad" value="{{$salas[0]->capacidad}}">
            @if ($errors->has('capacidad'))
                <span class="error">
                    <strong>{{ $errors->first('capacidad') }}</strong>
                </span>
            @endif
            <br>
            <input type="text" class="form-control width300px" name="datos" value="{{$salas[0]->datos}}">
            @if ($errors->has('datos'))
                <span class="error">
                    <strong>{{ $errors->first('datos') }}</strong>
                </span>
            @endif
            <br>

            <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                grabar
            </button>
            <input type="hidden" name="id" value="{{$salaId}}" />
        </form>
    </div>
@endsection

