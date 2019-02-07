@extends('layouts.app')

@section('content')
    <div class="subtitle">
        <h4>Añadir Grupos</h4>
    <div class="container">
        <div class="row">
            <div class="col-sm-1">
                <form class="floatLeft marginBottom20px" method="GET" action="{{route('goUpdateUser')}}" >
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i>
                    </button>
                    <input type="hidden" name="id" value="{{$usuarioId}}">
                </form>

            </div>
            <div class="col-sm-3 marginBottom20px">
                <input type="text" class="form-control busca" placeholder="Buscar..." aria-label="Recipient's username">
            </div>
            <div class="col-sm-8"></div>
        </div>

        <div class="row justify-content-center">
            <table class="table table-striped table-bordered" border="1px solid black">
                <form action="{{ route('addUserGroup') }}" method="GET">
                <thead>
                <th>Grupos</th>
                <th style="width: 150px;">
                        <button type="submit" name="submit" value="New" class="btn btn-light btnE floatRight">
                            <i class="fa fa-floppy-o fa-lg"></i>
                        </button>
                </th>
                </thead>
                <tbody>
                    @foreach($grupos as $grupo)
                        <tr>
                            <td>{{$grupo->nombre}}</td>
                            <td><input type="checkbox" name="grupo[]" value={{$grupo->id}}></td>
                        </tr>
                    @endforeach
                </tbody>
                    <input type="hidden" name="usuarioId" value="{{$usuarioId}}">
                </form>
            </table>
        </div>
    </div>
@endsection