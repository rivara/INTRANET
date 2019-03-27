@extends('layouts.app')
@section('content')
    <div class="container">
    <div class="row">
    <div class="col-md-5 paddingLeft50px">
        <form action="{{ route('goMenu') }}" method="GET">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                <i class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id" value={{$id}}>
            <input type="hidden" name="name" value="" style="display:none;">
        </form>
    </div>

    <div class="col-md-7">
        <h4>Categoria</h4>
    </div>
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="container wrapper ">

                <form action="{{ route('saveCategoria') }}" method="GET">
                    <div class="row">
                            <div class="col-md-6">
                                <label>Texto</label>
                                <input type="text" class="form-control"  name="texto">
                                @if ($errors->has('nombre'))
                                    <span class="error">
                                     <strong>{{ $errors->first('nombre') }}</strong>
                                    </span>
                                @endif
                            </div>
                        <div class="col-md-6">
                            <label>Accion</label>
                            <input type="text" class="form-control" name="accion">
                        </div>
                    </div>

                <br>
                <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                    grabar
                </button>
                </form>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    </div>
@endsection