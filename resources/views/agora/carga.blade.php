@extends('layouts.app')
@section('content')
   <!-- https://github.com/kartik-v/bootstrap-fileinput -->
    <div class="paddingLeft50px">
        <form action="{{route('backAgora')}}" method="GET">
            <button class="btn"><i class=" fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
        </form>
    </div>
    <div class="container wrapper mitad agora">
    <form action="{{ route('upload') }}" method="GET" >
        <button class="btn btn-warning floatRight refresh"  type="reset" ><i class="fa fa-refresh fa-lg" aria-hidden="true"></i>
        </button>
        <input name="file" class="file" type="file"  >
        <b>Descripcion</b>
        <textarea rows="4" cols="50" type="text" class="form-control" name="descripcion"></textarea>
        <br><br />
        <div class="floatRight">
            <button class="btn btn-warning floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                grabar
            </button>
        </div>
        <input type="hidden" name="id_usuario" value={{$id_usuario}} >
    </form>
    </div>
@endsection
