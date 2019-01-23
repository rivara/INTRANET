
@extends('layouts.app')
@section('content')

    <div class="subtitle">
        <h4>Administración usuarios @</h4>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-1 marginBottom20px">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control busca" placeholder="Buscar..." aria-label="Recipient's username">
                </div>
            <div class="col-sm-2">
                <form class="floatLeft marginBottom20px" method="GET" action="{{route('groups')}}" >
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-users"></i>
                        <span>editar grupos</span>
                    </button><input type="hidden" name="nombre" value='{{$nombre}}'>
                </form>
            </div>
            <div class="col-sm-2">
            <form class="floatLeft" action="{{route('portals')}}" method="GET">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa  fa-desktop"></i>
                    <span>editar Portales</span>
                </button>
            </form>
            </div>
            <div class="col-sm-4"></div>
            </div>
        <div class="row justify-content-center">
            <table class="table table-striped table-bordered" border="1px solid black">
                <thead></thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>Grupos</th>
                <th>E-mail</th>
                <th>
                    <form class="floatRight" method="GET" action="{{ route('createUser') }}">
                        <button type="submit" name="submit" value="Edit" class="btn btn-light btnE floatRight"><i
                                    class="fa fa-plus fa-lg"></i></button>
                        <input type="hidden" value="">
                    </form>
                </th>
                <tbody>
                <?php   $usuarios = DB::table('usuarios')->get(); ?>
                @foreach($usuarios as $value)
                    <tr>
                        <form action="{{ route('goUpdateUser') }}" method="GET">
                            @csrf
                            <td>{{$value->id}}</td>
                            <td>{{$value->nombre}}</td>
                            <td>
                                <?php  $paginas = DB::table('usuarios_grupos')->where('id_usuario',
                                    $value->id)->pluck('id_grupo');

                                foreach ($paginas as $pagina) {
                                    $nombre = DB::table('grupos')->where('id', $pagina)->pluck('nombre');
                                    echo "<p class='titleE'>" . $nombre[0] . "</p>";
                                }
                                ?>
                            </td>
                            <td>{{$value->email}}</td>

                            <td>
                                <button type="submit" name="submit" value="Edit" class="btn btn-light btnE "><i
                                            class="fa fa-pencil fa-lg"></i></button>



                                <button type="button" class="delete btn btn-light btnE "  data-toggle="modal"  data-target="#confirm" value="{{$value->id}}">
                                    <i class="fa fa-trash fa-lg"></i>
                                </button>

                            </td>
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <input type="hidden" name="nombre" value="{{$nombre}}">
                        </form>
                    </tr>
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
                    <p>¿Desea borrar este usuario?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                    <form action="{{route('deleteUser')}}" method="GET">
                        <button type="submit" name="submit" value="Delete" class="btn btn-danger ">
                            borrar
                        </button>
                        <input type="hidden" name="usuarioId"   id="id">
                        <input type="hidden" name="nombre" value="{{$nombre}}">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection