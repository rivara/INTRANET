
@extends('layouts.app')
@section('content')
    <div class="title">
        <form class="floatLeft" action="{{ route('portals') }}" method="GET">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-light btnE ">
                <i class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="nombre">
        </form>
        <h4 class="floatLeft paddingtop10px">Crear Portales</h4>
    </div>
    <br>

    <div class="container wrapper mitad floatLeft">
        <form id="logout-form" action="{{route('recordPortal')}}" method="GET">
            <h3>PORTAL</h3>
            <label for="Nombre">Nombre</label>
            <input type="text" class="form-control width300px" name="nombre" placeholder="Introduce Nombre">
            @if ($errors->has('nombre'))
                <span class="error">
                    <strong>{{ $errors->first('nombre') }}</strong>
                </span>
            @endif
            <br>
            <label for="url">URL</label>
            <input type="text" class="form-control width300px" name="url" placeholder="Introduce url">
            @if ($errors->has('url'))
                <span class="error">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif
            <br>
            <label for="Icono">Icono</label>
            <div class="page">
                <input type="text" class="input1 input form-control width200px" name="icono"/>
            </div>
            <br>
            <label for="Target">target</label>
            <div>
                <input type="radio"  name="target" value="0">
                <label for="type">_blank</label>
            </div>
            <div>
                <input type="radio"  name="target" value="1">
                <label for="type">_self</label>
            </div>
            <div>
                <input type="radio"  name="target" value="2">
                <label for="type">_parent</label>
            </div>
            <div>
                <input type="radio"  name="target" value="3">
                <label for="type">_top</label>
            </div>
             <br>
            <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                grabar
            </button>
        </form>
    </div>

@endsection
