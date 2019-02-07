@extends('layouts.app')
@section('content')
    <div class="paddingLeft50px">
        <form action="{{ route('backAgora') }}" method="GET">
            <button class="btn btn-warning  "><i class=" fa fa-arrow-left fa-lg"></i></button>
        </form>
    </div>
    <div class="container wrapper mitad agora ">

        <form id="logout-form">
            <h3>Descarga</h3>
            <div class="cuadro">
                <div>
                    <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                    <br>
                    <label for="file-upload" class="custom-file-upload">
                        Custom Upload
                    </label>
                    <input id="file-upload" type="file"/>
                    <br>
                    <p>también puedes arrastrarlo</p>
                </div>
            </div>
            <br>
            <label for="Name">Descripción</label>
            <textarea class="form-control" rows="3" id="comment"></textarea>

            @if ($errors->has('usuario'))
                <span class="error">
                                        <strong>{{ $errors->first('usuario') }}</strong>
                                    </span>
            @endif
            <br>
            <button class="btn btn-warning floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                grabar
            </button>
        </form>
    </div>
    </div>
@endsection
