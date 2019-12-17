@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración de menus</h4>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <form action="{{ route('redirect') }}" method="POST">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i></button>
                    <input type="hidden" name="id" value=1>
                </form>
            </div>
            <div class="col-md-8" ></div>
        </div>
        <br>
        <div class="row justify-content-center">
            <table class="table table-striped table-bordered table-info" border="1px solid black">
                <thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>Capacidad</th>
                <th>Datos</th>
                <th>
                    <form class="floatRight" method="GET" action="{{ route('goCreateRoom') }}">
                        <button type="submit" name="submit" value="Edit" class="btn btn-link btnE floatRight"><i
                                class="fa fa-plus fa-lg"></i></button>
                        <input type="hidden" value="">
                    </form>
                </th>
                </thead>
                <tbody>

                <?php   $cuenta = DB::table('salas')->count(); ?>
                @if($cuenta>0)
                    <?php   $salas = DB::table('salas')->get(); ?>
                    @foreach($salas as $sala)
                        <form class="floatLeft" method="GET" action="{{ route('goUpdateRoom') }}">
                        <tr>
                            <td>
                                {{$sala->id}}
                            </td>
                            <td>
                                {{$sala->nombre}}
                            </td>
                            </td>
                            <td>
                                {{$sala->capacidad}}
                            </td>
                            </td>
                            <td>
                                {{$sala->datos}}
                            </td>
                            <td>
                                <button type="submit" name="submit" value="Edit" class="btn btn-link btnE "><i
                                        class="fa fa-pencil fa-lg"></i></button>

                                <button type="button" class="delete btn btn-link btnE " data-toggle="modal"
                                        data-target="#confirm" value="{{$sala->id}}">
                                    <i class="fa fa-trash fa-lg"></i>
                                </button>
                                <input type="hidden" name="grupoId" value={{$sala->id}}>
                            </td>
                        </tr>
                        </form>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <!-- Modal Dialog -->
    <div class="modal fade" id="confirm" aria-labelledby="confirmDeleteLabel">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>¿Desea borrar este menu?</p>
                    <small>Afectara a todos los miembros de este grupo</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                    <form method="GET">
                        <button type="submit" name="submit" value="Delete" class="btn btn-danger ">
                            borrar
                        </button>
                        <input type="hidden" name="usuarioId" id="id">
                        <input type="hidden" name="grupoId" value={{$sala->id}}>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endif
@endsection
