@extends('layouts.app')
@section('content')
    <div class="title">
        <div></div>
        <h4>Añadir Grupos</h4>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-1">
                <form class="floatLeft marginBottom20px" method="GET" action="{{route('goUpdateGroup')}}">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i>
                    </button>
                    <input type="hidden" name="grupoId" value="{{$grupoId}}">
                </form>
            </div>
            <div class="col-sm-3 marginBottom20px">
                <input type="text" class="form-control busca" placeholder="Buscar..." aria-label="Recipient's username">
            </div>
            <div class="col-sm-8"></div>
        </div>

        <div class="row justify-content-center">
            <table class="table table-striped table-bordered table-info" border="1px solid black">
                <form method="GET" action="{{ route('addGrupoPortal') }}">
                    <thead>
                    <th>Nombre</th>
                    <th>Url</th>
                    <th style="width: 150px;">
                        <button type="submit" name="submit" value="New" class="btn btn-light btnE floatRight"><i
                                    class="fa fa-floppy-o fa-lg"></i></button>
                    </th>
                    </thead>
                    <tbody>
                    @foreach($portales as $portal)
                        <tr>
                            <td><i class="fa {{$portal->icono}}"></i>{{$portal->nombre}}</td>
                            <td>{{$portal->url}}</td>
                            <td><input type="checkbox" name="portal[]" value={{$portal->id}}></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <input type="hidden" name="grupoId" value="{{$grupoId}}">
                    <input type="hidden" name="nombre" value="{{$nombre}}">
                </form>
            </table>
        </div>
    </div>
@endsection