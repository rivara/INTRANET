
@extends('layouts.app')
@section('content')
    <div class="row">
        <div  class="col-md-2 paddingLeft50px" >
            <form   action="{{route('backDocu')}}" method="POST">
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-outline-warning  btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                    <input type="hidden" name="id_usuario" value="{{$id_usuario}}">
            </form>
        </div>
        <div  class="col-md-2" ></div>
        <div  class="col-md-3" >
            <h1 class="marginLeft30px">&nbsp;Crear Subgrupo en</h1>
            <h5 class="marginLeft60px">{{$nombre_grupo}}</h5>
        </div>
        <div   class="col-md-5"></div>
    </div>
    <br />
    <div class="container wrapper agora  mitad">
        <form id="logout-form" action="{{route('subGroupRecord')}}" method="GET">
            <h3>SUBGRUPO</h3>
            <label for="Nombre">Nombre</label>
            <input type="text" class="form-control width300px" name="nombre" placeholder="Introduce Nombre">
            @if ($errors->has('nombre'))
                <span class="error">
                    <strong>{{ $errors->first('nombre') }}</strong>
                </span>
            @endif
            <br>
            <button class="btn btn-warning floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                grabar
            </button>

            <input type="hidden" name="id_usuario" value="{{$id_usuario}}">
            <input type="hidden" name="id_grupo" value="{{$id_grupo}}">
        </form>
    </div>

@endsection
