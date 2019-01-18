@extends('layouts.app')
@section('content')
    <form class="floatLeft" method="GET" action="{{ route('groups') }}">
        <button type="submit" class="btn btn-light btnE ">
            <i class="fa fa-arrow-left fa-lg"></i>
        </button>
    </form>
    <div class="title paddingtop10px">
        <h4>Nuevo grupo</h4>
    </div>
    <div class="container wrapper mitad">
        <div class="row">
            <form action="{{ route('recordGroup') }}" method="POST">
                @csrf
                <div class="col-md-12">
                    <h3>GRUPO</h3>
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

