@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración grupos</h4>
    </div>

    <div class="container">

        <div class="row">
            <div class="col-sm-1 marginBottom20px">
                <form id="logout-form" action="{{ route('backAdmin') }}" method="GET">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i>
                    </button>
                </form>
            </div>
            <div class="col-sm-3">
                <input type="text" class="form-control busca" placeholder="Buscar..." aria-label="Recipient's username">
            </div>
            <div class="col-sm-1 marginBottom20px">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>

            <div class="col-sm-7"></div>
        </div>


        <div class="row">
            <table class="table table-striped table-bordered table-info" border="1px solid black">
                <thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>portales</th>
                <th>
                    <form class="floatRight" method="GET" action="{{ route('createGroup') }}">
                        <button type="submit" name="submit" value="Edit" class="btn btn-link btnE floatRight"><i
                                    class="fa fa-plus fa-lg"></i></button>
                        <input type="hidden" value="">
                    </form>
                </th>
                </thead>
                <?php $grupos = DB::table('grupos')->get(); ?>
                @foreach($grupos as $grupo)
                    <form class="floatLeft" method="GET" action="{{ route('goUpdateGroup') }}">
                        <tr>
                            <td>
                                {{$grupo->id}}
                            </td>
                            <td>
                                {{$grupo->nombre}}
                            </td>
                            <td>
                                <?php
                                $array = array();
                                $i = 0;
                                $portales_id = DB::table('grupos_portales')->where('id_grupo',
                                    $grupo->id)->pluck('id_portal');
                                foreach ($portales_id as $id_portal) {
                                    $icono = str_replace(array('["', '"]'), '',
                                        DB::table('portales')->where('id', $id_portal)->pluck('icono'));

                                    echo "<i class='fa $icono' aria-hidden='true'></i>";
                                    echo "&nbsp;";
                                    echo "<b>" . str_replace(array('["', '"]'), '',
                                            DB::table('portales')->where('id', $id_portal)->pluck('nombre')) . "</b>";
                                    echo "&nbsp; -> &nbsp;";
                                    echo str_replace(array('["', '"]'), '',
                                        DB::table('portales')->where('id', $id_portal)->pluck('url'));
                                    echo "<br>";
                                }
                                ?>
                            </td>
                            <td>
                                <button type="submit" name="submit" value="Edit" class="btn btn-link btnE "><i
                                            class="fa fa-pencil fa-lg"></i></button>

                                <button type="button" class="delete btn btn-link btnE " data-toggle="modal"
                                        data-target="#confirm" value="{{$grupo->id}}">
                                    <i class="fa fa-trash fa-lg"></i>
                                </button>
                                <input type="hidden" name="grupoId" value={{$grupo->id}}>
                            </td>
                        </tr>
                    </form>
                @endforeach
            </table>
        </div>
    </div>
    <!-- Modal Dialog -->
    <div class="modal fade" id="confirm" aria-labelledby="confirmDeleteLabel">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>¿Desea borrar este grupo?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                    <form action="{{ route('deleteGroup') }}" method="GET">
                        <button type="submit" name="submit" value="Delete" class="btn btn-danger ">
                            borrar
                        </button>
                        <input type="hidden" name="grupoId" id="id">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection