@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-1">
                <form method="GET" action="{{route('goSubCarpeta')}}">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-primary btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i></button>
                    <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                    <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                    <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >

                </form>
            </div>
            <div class="col-md-10 paddingLeft30">
                <h1>
                    <?php
                    $var = DB::table('subgrupos')->where('id', $id_subgrupo)->pluck('nombre');
                    $var = substr($var, 2, count($var) - 3);
                    echo $var;
                    ?>
                </h1>
            </div>
            <div class="col-md-1">
                <form class="floatRight" method="GET">
                    <button class="delete btn btn-primary btnE  " type="button" data-toggle="modal"
                            data-target="#confirm" value="1">
                        <i class="fa fa-trash fa-lg"></i>
                    </button>
                </form>
            </div>


            <div class="col-md-6">
                <div class="paddingBottom10px">
                    <input type="text" class="form-control busca" placeholder="Buscar..."
                           aria-label="Recipient's username">
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <table class="table-bordered table bg-white">
            <thead>
            <th></th>
            <th>
                Descripción <i class="fa fa-caret-up "></i>
            </th>

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
            $ficheros = DB::table('archivos')->orderBy('descripcion', 'asc')->where('id_subgrupo', $id_subgrupo)->get();
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
                            <div class="row" style="margin-top: -22px!important;">
                                <div class="col-md-2">
                                    <button class="btn width60px " type="button" data-toggle="modal"
                                            data-target="#confirm2" value={{$fichero->id}}>
                                        <i class="fa fa-trash fa-2x"></i>
                                        <input type="hidden" name="id" value="{{$fichero->id}}">
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <form method="GET" action="{{route('download')}}">
                                        <button type="submit" class="btn width60px">
                                            <i class="fa fa-download fa-2x" aria-hidden="true"></i>
                                            <input type="hidden" name="id" value={{$fichero->id}} >
                                            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                                            <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                                            <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-2">
                                    <form method="GET" action="{{route('goEditFile')}}">
                                        <button type="submit" class="btn width60px">
                                            <i class="fa fa-pencil fa-2x" aria-hidden="true"></i>
                                            <input type="hidden" name="id_fichero" value={{$fichero->id}} >
                                            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                                            <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                                            <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
    </div>
    </div>
    <!-- Modal Dialog -->

    @csrf
    <div class="modal fade" id="confirm" aria-labelledby="confirmDeleteLabel">
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
    <div class="modal fade" id="confirm2" aria-labelledby="confirmDeleteLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>¿Desea borrar el fichero?</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                    <form method="GET" action="{{route('deleteFile')}}">
                        <button type="submit" name="submit" value="Delete" class="btn btn-danger ">
                            borrar
                        </button>
                        <input type="hidden" name="fichero_id" id="id">
                        <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                        <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                        <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}} >

                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

<!--FALTA IDENTIFICAR EL NOMBRE  -->
<script>
    // data-* attributes to scan when populating modal values
    var ATTRIBUTES = ['myvalue', 'myvar', 'bb'];

    $('[data-toggle="confirm2"]').on('click', function (e) {
        // convert target (e.g. the button) to jquery object
        var $target = $(e.target);
        // modal targeted by the button
        var modalSelector = $target.data('target');

        // iterate over each possible data-* attribute
        ATTRIBUTES.forEach(function (attributeName) {
            // retrieve the dom element corresponding to current attribute
            var $modalAttribute = $(modalSelector + ' #confirm2-' + attributeName);
            var dataValue = $target.data(attributeName);

            // if the attribute value is empty, $target.data() will return undefined.
            // In JS boolean expressions return operands and are not coerced into
            // booleans. That way is dataValue is undefined, the left part of the following
            // Boolean expression evaluate to false and the empty string will be returned
            $modalAttribute.text(dataValue || '');
        });
    });
</script>