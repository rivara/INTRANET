@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración de menus</h4>
    </div>
    <div class="container">

        <form   action="{{ route('goMenu') }}" method="GET">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                <i class="fa fa-arrow-left fa-lg "></i></button>
            <input type="hidden" name="id" value=1>
            <input type="hidden" name="name" value="" style="display:none;">
        </form>
        <div class="container wrapper mitad">
            <div class="row">
                <form action="{{ route('recordMenu') }}" method="GET">
                    @csrf
                    <div class="col-md-12">
                        <h3>MENU</h3>
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control width300px" name="nombre" placeholder="Introduce Nombre">
                        @if ($errors->has('nombre'))
                            <span class="error">
                        <strong>{{ $errors->first('nombre') }}</strong>
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