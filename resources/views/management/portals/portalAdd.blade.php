@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Añadir Grupos</h4>
        <form id="logout-form" action="{{ route('actionEditDelete') }}" method="POST">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-light btnE "><i
                        class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id" value="{{$usuarioId}}">
        </form>
    </div>
    <div class="input-group buscador">
        <input type="text" class="form-control busca" placeholder="Buscar..." aria-label="Recipient's username" aria-describedby="basic-addon2">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <table class="table table-striped table-bordered" border="1px solid black">
                <form action="{{ route('actionAddGroup') }}" method="GET">
                    <thead>
                    <th>Grupos</th>
                    <th style="width: 150px;">
                        <button type="submit" name="submit" value="New" class="btn btn-light btnE floatRight"><i
                                    class="fa fa-floppy-o fa-lg"></i></button>
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