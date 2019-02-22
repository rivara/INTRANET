@extends('layouts.app')

@section('content')


    <div class="paddingLeft50px">
        <form action="{{route('backAgora')}}" method="GET">
            <button class="btn btn-warning  "><i class=" fa fa-arrow-left fa-lg"></i></button>
        </form>
    </div>
    <div class="container   agora ">
        <div class="row justify-content-center">


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

            <form id="logout-form" action="{{ route('upload') }}" method="GET">
                <br><br />
                <!---------------------  PENDIENTE DE FUNCIONALIDAD --------------------->
                <div id="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <i class="fa fa-cloud-upload fa-4x " style="color:#ffed4a;padding-left:40%;padding-top:30px"></i>
                <input type="file" id="file" name="file"/>
                </div>
                <p id="demo"></p>
                <!-------------------------------------------------------------------------------------->
                <b>Descripcion</b>
                <textarea rows="4" cols="50" type="text" class="form-control width400px" name="descripcion"></textarea>
                @if ($errors->has('email'))
                    <span class="error">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <br><br/>
                <button class="btn btn-warning floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                    grabar
                </button>
            </form>
        </div>
    </div>
@endsection



<script>
    function dragStart(e) {
        event.dataTransfer.setData("Text", e.target.id);
    }

    function allowDrop(e) {
        e.preventDefault();
        // document.getElementById("demo").innerHTML = "The p element is OVER the droptarget.";
        e.target.style.border = " 3px dashed yellow";
        e.target.style.backgroundColor = " #ffffe6";
    }

    function drop(e) {
        alert("-->"+e.dataTransfer.files[0].name);
        //fileInput.files[0].name =e.dataTransfer.files[0].name;
        //SE SUPONE QUE TENDRIA QUE TRANSMITIR AQUI EL FICHERO
         //fileInput.files = e.dataTransfer.files;
        e.preventDefault();
    }


    dropContainer.ondragover = dropContainer.ondragenter = function(evt) {
        evt.preventDefault();
    };

    dropContainer.ondrop = function(e) {
        // pretty simple -- but not for IE :(
        fileInput.files = e.dataTransfer.files;
        e.preventDefault();
    };








</script>