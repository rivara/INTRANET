
@extends('layouts.app')
@section('content')
    <div class="row">
        <div  class="col-md-2 paddingLeft50px" >
            <form   action="{{ route('redirect') }}" method="POST">
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                <input type="hidden" name="id" value=1>
                <input type="hidden" name="name" value="" style="display:none;">
            </form>
        </div>
        <div  class="col-md-2" ></div>
        <div  class="col-md-3" >
            <h1 class="paddingtop10px">&nbsp;Crear Portales</h1>
        </div>
        <div   class="col-md-5"></div>
    </div>
    <br />
    <div class="container wrapper mitad">
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
            @if ($errors->has('icono'))
                <span class="error">
                    <strong>{{ $errors->first('icono') }}</strong>
                </span>
            @endif

            <br>
            <br>
            <label for="Target">target</label>
            <div>
                <input type="radio"  name="target" value="0" checked>
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
