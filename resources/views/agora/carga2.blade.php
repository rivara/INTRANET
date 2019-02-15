@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">Upload File Example</div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif



                        <form  id="logout-form" action="{{ route('upload') }}" method="GET">
                            <h3>USUARIO</h3>
                            <label for="Name">Nombre</label>
                            <input type="file" class="form-control-file" name="file" id="file" aria-describedby="fileHelp">

                            <br>

                            <label for="Email1">Descripcionaaaaa</label>

                            <textarea rows="4" cols="50" type="text" class="form-control width400px" name="descripcion"
                                      value=""></textarea>
                            @if ($errors->has('email'))
                                <span class="error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif

                            <br><br />
                            <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                                grabar
                            </button>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection