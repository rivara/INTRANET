@extends('layouts.app')
@section('content')
   <!-- https://github.com/kartik-v/bootstrap-fileinput -->
    <div class="paddingLeft50px">
        <form action="{{route('backAgora')}}" method="GET">
            <button class="btn btn-warning  "><i class=" fa fa-arrow-left fa-lg"></i></button>
        </form>
    </div>
    <div class="container wrapper mitad agora">
    <form action="{{ route('upload') }}" method="GET" >
        <input name="file" class="file" type="file"  >
        <b>Descripcion</b>
        <textarea rows="4" cols="50" type="text" class="form-control" name="descripcion"></textarea>
        <br><br />
        <div class="floatRight">
            <button class="btn btn-warning">Submit</button>
            <button class="btn btn-outline-dark" type="reset">Reset</button>
        </div>
    </form>
    </div>
@endsection
