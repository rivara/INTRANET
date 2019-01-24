@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <form  class="floatLeft" action="{{ route('portals') }}" method="GET">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-light btnE ">
                <i class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="nombre">
        </form>
        <h4 class="floatLeft paddingtop10px">Modificar Portales</h4>
    </div>
    <br />
    <div class="container wrapper mitad ">
        <?php $portales = DB::table('portales')->where('id',$portalId)->get() ?>
        <form id="logout-form" action="{{route('updatePortal')}}" method="get">
            <h3>PORTAL</h3>
            <label for="Niombre">Nombre</label>
            <input type="text" class="form-control width300px" name="nombre" value="{{$portales[0]->nombre}}">
            @if ($errors->has('nombre'))
                <span class="error">
                    <strong>{{ $errors->first('nombre') }}</strong>
                </span>
            @endif
            <br>
            <label for="url">URL</label>
            <input type="text" class="form-control width300px" name="url" value="{{$portales[0]->url}}">
            @if ($errors->has('url'))
                <span class="error">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif
            <br>
            <label for="Icono">Icono</label>
            <div class="page">
                <input type="text" class="input1 input form-control width300px" name="icono" value="{{$portales[0]->icono}}"/>
            </div>
            <br><br/>
            <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                grabar
            </button>
               <input type="hidden" name="id" value="{{$portalId}}" />
        </form>
    </div>
@endsection

