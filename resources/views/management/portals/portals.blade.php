
@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración de portales</h4>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-1 marginBottom20px">
                <form id="logout-form" action="{{ route('backAdmin') }}" method="GET">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn  btnE btn-outline-primary ">
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
                <th>Id</th>
                <th>Icono</th>
                <th>Nombre</th>
                <th>URL</th>
                <th>Target</th>
                <th>
                    <form class="floatRight" method="GET" action="{{ route('createPortal') }}">
                        <button type="submit" name="submit" value="Edit" class="btn btn-link btnE floatRight"><i
                                    class="fa fa-plus fa-lg"></i></button>
                        <input type="hidden" value="">
                    </form>
                </th>
                </thead>
                <tbody>
                <?php   $portales = DB::table('portales')->get(); ?>
                @foreach($portales as $portal)

                    <form class="floatLeft" action="{{route('goUpdatePortal')}}"  method="GET" >
                    <tr>
                        <td>{{$portal->id}} </td>
                        <td> <i class="fa {{$portal->icono}} fa-lg" ></i> &nbsp; {{$portal->icono}}</td>
                        <td>{{$portal->nombre}}</td>
                        <td>{{$portal->url}}</td>

                        <td>
                            @if($portal->target==0)
                                <p>_blank</p>
                            @endif
                            @if($portal->target==1)
                                <p>_self</p>
                            @endif
                            @if($portal->target==2)
                                <p>_parent</p>
                            @endif
                            @if($portal->target==3)
                                <p>_top</p>
                            @endif
                        </td>
                        <td>
                            <button type="submit" name="submit" value="Edit" class="btn btn-link btnE "><i
                                        class="fa fa-pencil fa-lg"></i>
                            </button>

                            <button class="delete btn btn-link btnE " type="button" data-toggle="modal"
                                    data-target="#confirm" value="{{$portal->id}}">
                                <i class="fa fa-trash fa-lg"></i>
                            </button>

                        </td>
                    </tr>
                        <input type="hidden" name="portalId" value="{{$portal->id}}">
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
                    <p>¿Desea borrar este usuario?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                    <form action="{{ route('deletePortal') }}" method="GET">
                        <button type="submit" name="submit" value="Delete" class="btn btn-danger ">
                            borrar
                        </button>
                        <input type="hidden" name="portalId" id="id">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection