@extends('layouts.app')
@section('content')
    <div class="paddingLeft50px">
        <form action="{{ route('backAgora') }}" method="GET">
            <button class="btn btn-warning  "><i class=" fa fa-arrow-left fa-lg"></i></button>
        </form>
    </div>
    <div class="container wrapper  agora ">

        <!--<form id="logout-form">
            <h3>Descarga</h3>
            <div class="cuadro">
                <div>
                    <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                    <br>
                    <label for="file-upload" class="custom-file-upload">
                        Custom Upload
                    </label>
                    <input id="file-upload" type="file" name="file"/>
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
        </form> -->
            <!-------------------------------------------------------------------------------- -->
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


                            <form action="{{route('upload')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file"
                                           id="avatar" name="avatar"
                                           accept="image/png, image/jpeg">
                                    <input type="file"  name="fileToUpload" id="exampleInputFile" >
                                    <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------------------------------------------------------------->
    </div>
    </div>
@endsection
