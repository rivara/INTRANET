@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración de menus</h4>
    </div>
    <div class="container" >

            <div class="row">
                <div class="col-md-4">
                    <form action="{{ route('goRoom') }}" method="get">
                        @csrf
                        <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                            <i class="fa fa-arrow-left fa-lg"></i></button>
                        <input type="hidden" name="id" value=1>
                    </form>
                </div>
    </div>


        <div class="container wrapper mitad">
        <form action="{{route('createRoom')}}" method="POST">
            @csrf
            <div class="col-md-12">
                <h3>SALA</h3>
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control width300px" name="nombre" placeholder="Introduce Nombre">
                @if ($errors->has('nombre'))
                    <span class="error">
                        <strong>{{ $errors->first('nombre') }}</strong>
                    </span>
                @endif
                <br>
                <label for="capacidad">Capacidad</label>
                <input type="number" class="form-control width100px" name="capacidad" placeholder="">
                @if ($errors->has('capacidad'))
                    <span class="error">
                    <strong>{{ $errors->first('capacidad') }}</strong>
                </span>
                @endif
                <br>
                <label for="datos">Datos</label>
                <input type="text" class="form-control width300px" name="datos" placeholder="Introduce datos">
                @if ($errors->has('datos'))
                    <span class="error">
                    <strong>{{ $errors->first('datos') }}</strong>
                </span>
                @endif
                <br>
                <button class="btn btn-primary floatLeft"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                    grabar
                </button>
            </div>
        </form>
        </div>


    </div>
@endsection
