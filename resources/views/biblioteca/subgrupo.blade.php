@extends('layouts.app')
@section('content')
    <div class="container">
    <div class="row">
        <div class="col-md-1" >
            <form  method="GET" action="{{route('goSubCarpeta')}}" >
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-primary btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >

            </form>
        </div>
        <div class="col-md-10 paddingLeft30" >
            <h1>
            <?php
                $var = DB::table('subgrupos')->where('id', $id_subgrupo)->pluck('nombre');
                $var = substr($var, 2, count($var) - 3);
                echo $var;
                ?>
            </h1>


        </div>
        <div  class="col-md-1">
            <form class="floatRight" method="GET">
                <button class="delete btn btn-primary btnE  " type="button" data-toggle="modal"
                        data-target="#confirm" value="1">
                    <i class="fa fa-minus fa-lg"></i>
                </button>
            </form>
        </div>
        <div class="col-md-6">
                <form>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Busca fichero" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>
        </div>
        <div class="col-md-6"></div>
    </div>

                <table class="table-bordered table bg-white">
                    <thead>
                    <th></th>
                    <th>Descripción</th>
                    <th>Fichero</th>
                    <th>
                        <form class="floatRight" method="GET" action="{{route('goAddFile')}}">
                            <button type="submit" name="submit" value="Edit" class="btn  btnE floatRight"><i
                                        class="fa fa-plus fa-lg"></i></button>
                            <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                            <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >
                        </form>
                    </th>
                    </thead>
                    <?php
                    $ficheros = DB::table('archivos')->where('id_subgrupo',$id_subgrupo)->get();
                    ?>
                    @if(! is_null($ficheros))
                    @foreach($ficheros as $fichero)
                        <tr>
                            <td>
                                <i class="{{$fichero->formato}} fa-3x marginLeft20" aria-hidden="true"></i>
                            </td>
                            <td>
                                <b>{{$fichero->descripcion}}</b>
                            </td>
                            <td>
                                <b>{{$fichero->nombre}}</b><br>
                                ({{$fichero->otros}})
                            </td>
                            <td>
                                <form class="floatLeft" method="GET" action="{{route('deleteFile')}}">
                                    <button type="submit" class="btn btnE ">
                                        <i class="fa fa-trash fa-2x"></i></button>


                                    <input type="hidden" name="id" value={{$fichero->id}} >
                                    <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                                    <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                                    <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >
                                </form>

                                <form class="floatLeft" method="GET" action="{{route('download')}}">
                                    <button type="submit"  class="btn  btnE">
                                        <i class="fa fa-download fa-2x" aria-hidden="true"></i>
                                        <input type="hidden" name="id" value={{$fichero->id}} >
                                        <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                                        <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                                        <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </table>
            </div>
        </div>
</div>
    <!-- Modal Dialog -->
    <div class="modal fade" id="confirm" aria-labelledby="confirmDeleteLabel">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>¿Desea borrar este subgrupo {{$id_subgrupo}} ?</h6>
                    <p>ATENCION!! se eliminaran todos los archivos adjuntos</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                    <form method="GET" action="{{route('subGroupDelete')}}">
                        <button type="submit" name="submit" value="Delete" class="btn btn-danger ">
                            borrar
                        </button>
                        <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                        <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                        <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection