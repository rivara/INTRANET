
@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración de menus</h4>
    </div>

    <div class="container">
        <form   action="{{ route('redirect') }}" method="POST">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                <i class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id" value=1>
            <input type="hidden" name="name" value="" style="display:none;">
        </form>
          <br>

        <div class="row justify-content-center">
            <table class="table table-striped table-bordered table-info" border="1px solid black">
                <thead>
                <th>ID</th>
                <th>Nombre</th>

                <th>
                    <form class="floatRight" method="GET" action="{{ route('createMenu') }}">
                        <button type="submit" name="submit" value="Edit" class="btn btn-light btnE floatRight"><i
                                    class="fa fa-plus fa-lg"></i></button>
                        <input type="hidden" value="">
                    </form>
                </th>
                </thead>
                <tbody>
                <?php   $menus = DB::table('menu')->get(); ?>
                @foreach($menus as $menu)
                    <tr>
                        <form method="GET" action="{{ route('updateMenu') }}">
                            @csrf
                            <td>{{$menu->id}}</td>
                            <td>{{$menu->nombre}}</td>
                            <td>
                                <button type="submit" name="submit" value="Edit" class="btn btn-light btnE "><i
                                            class="fa fa-pencil fa-lg"></i></button>

                                <button type="button" class="delete btn btn-light btnE " data-toggle="modal"
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


@endsection