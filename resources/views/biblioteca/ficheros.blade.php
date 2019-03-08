@extends('layouts.app')
@section('content')

    <div class="paddingLeft50px container">
               <form  method="GET" action="{{route('goSubGroup')}}">
                   <button class="btn btn-info btnE" value="{{$id_subgrupo}}">
                       <i class="fa fa-arrow-left fa-lg"></i>
                   </button>
                   <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                   <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}}>
                   <input type="hidden" name="id_grupo" value={{$id_grupo}}>
               </form>
           </div>

    <div class="container wrapper mitad agora">
        <form action="{{ url('upload') }}" method="post" enctype="multipart/form-data">
            <button class="btn btn-info btnE floatRight refresh" type="reset"><i class="fa fa-refresh fa-lg"
                                                                               aria-hidden="true"></i>
            </button>
            <input name="file" class="file" type="file" name="file">
            <b>Descripcion</b>
            <textarea rows="4" cols="50" type="text" class="form-control" name="descripcion"></textarea>
            <br><br/>
            <?php ?>

            <br><br/>
            <div class="floatRight">
                {{ csrf_field() }}
                <button class="btn btn-info floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                    grabar
                </button>
            </div>
            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
            <input type="hidden" name="id_grupo" value={{$id_grupo}}>
            <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >
        </form>
    </div>


@endsection
