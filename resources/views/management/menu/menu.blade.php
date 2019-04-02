@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración de menus</h4>
    </div>

    <div class="container" >
        <div class="row">
        <div class="col-md-4">
            <form action="{{ route('redirect') }}" method="POST">
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                    <input type="hidden" name="id" value=1>
            </form>
        </div>
            <div class="col-md-8" > </div>

            <div class="col-md-8"></div>
            <div class="col-md-1">
                <form  method="GET" action="{{ route('goCreateCategoria') }}">
                    <button class="btn btn-primary"><i class="fa fa-plus fa-lg" aria-hidden="true"></i>
                         categoria</button>
                    <input type="hidden" name="id" value=1>
                </form>
            </div>
            <div class="col-md-1">
                <form  method="GET"  action="{{ route('goDeleteCategoria') }}">
                    <button class="btn btn-primary">
                        <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                        categoria</button>
                    <input type="hidden" name="id" value=1>
                </form>
            </div>

            <div class="col-md-1">
                <form  method="GET" action="{{ route('goCreateSubcategoria') }}">
                    <button class="btn btn-info"><i class="fa fa-plus fa-lg" aria-hidden="true"></i>

                         subcateg</button>
                    <input type="hidden" name="id" value=1>
                </form>
            </div>
            <div class="col-md-1">
                <form  method="GET"  action="{{ route('goDeleteSubCategoria') }}">
                    <button class="btn btn-info">
                        <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                         subcateg</button>
                    <input type="hidden" name="id" value=1>
                </form>
            </div>



        </div>
        <br>
        <div class="row justify-content-center">
            <table class="table table-striped table-bordered table-info" border="1px solid black">
                <thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>
                    <form class="floatRight" method="GET" action="{{ route('createMenu') }}">
                        <button type="submit" name="submit" value="Edit" class="btn btn-link btnE floatRight"><i
                                    class="fa fa-plus fa-lg"></i></button>
                        <input type="hidden" value="">
                    </form>
                </th>
                </thead>
                <tbody>
                <?php   $cuenta = DB::table('menus')->count(); ?>
                @if($cuenta>0)
                <?php   $menus = DB::table('menus')->get(); ?>
                @foreach($menus as $menu)
                    <tr>
                        <form method="GET" action="{{ route('updateMenu') }}">
                            @csrf
                            <td>{{$menu->id}}</td>
                            <td>{{$menu->nombre}}</td>
                            <td>
                                <button type="submit" name="submit" value="Edit" class="btn btn-link btnE "><i
                                            class="fa fa-pencil fa-lg"></i></button>

                                <button type="button" class="delete btn btn-link btnE " data-toggle="modal"
                                        data-target="#confirm" value="{{$menu->id}}">
                                    <i class="fa fa-trash fa-lg"></i>
                                </button>
                            </td>
                            <input type="hidden" name="id" value="{{$menu->id}}">
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
                    <p>¿Desea borrar este menu?</p>
                    <small><B>Atención!!</B> al borrar este grupo afectara al acceso de los susuarios que esten
                        asociados a este grupo
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                    <form action="{{route('deleteMenu')}}" method="GET">
                        <button type="submit" name="submit" value="Delete" class="btn btn-danger ">
                            borrar
                        </button>
                        <input type="hidden" name="usuarioId" id="id">
                        <input type="hidden" name="menu_id" value="{{$menu->id}}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection