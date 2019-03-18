@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-2 paddingLeft50px">
            <form action="{{ route('goMenu') }}" method="GET">
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                <input type="hidden" name="id" value=1>
                <input type="hidden" name="name" value="" style="display:none;">
            </form>
        </div>

        <div class="col-md-2"></div>
        <div class="col-md-3">
            <h2 class="paddingLeft100px width600px"> Administración del
                <?php
                $title = DB::table('menus')->where('id', $id)->pluck('nombre');
                $title = substr($title, 2, strlen($title) - 4);
                echo $title;
                ?></h2>
        </div>
        <div class="col-md-5"></div>
    </div>
    <br/>
    <div class="container mitad">
        <ul class="list-group list-group-flush">
            <li class="list-group-item list-group-item-primary">
                <form action="{{ route('goMenuAdd') }}" method="GET">
                    <button class="btn btn-primary floatRight" type="submit">
                        <i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                    <input type="hidden" name="id" value="{{$id}}">
                </form>
            </li>
            <?php

            $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
            $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => NULL])->whereIn('id', $ids)->get();?>
            @foreach($categorias as $categoria)
                <li class="list-group-item bg-lightBlue">
                    <div class="row ">
                        <div class="col-md-2"></div>
                        <div class="col-md-4"><h4>{{$categoria->texto}}</h4></div>
                        <div class="col-md-4">
                            <form action="{{ route('deleteMenuCategoria') }}" method="GET">
                                <button class="btn btn-primary floatRight"><i class="fa fa-trash-o fa-lg"
                                                                              aria-hidden="true"></i></button>
                                <input type="hidden" name="id" value={{$categoria->id}}>

                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </li>
                <?php $subcategorias = DB::table('b2bcategorias')->where('subcategoria1', '!=',
                    '')->where(['categoria' => $categoria->categoria])->get();?>
                @foreach($subcategorias as $subcategoria)
                    <li class="list-group-item bg-lightBlue2">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><h5>{{$subcategoria->texto}}</h5></div>
                            <div class="col-md-4">
                                <form action="{{ route('deleteMenuSubCategoria') }}" method="GET">
                                    <button class="btn btn-primary floatRight"><i class="fa fa-trash-o fa-lg"
                                                                                  aria-hidden="true"></i></button>
                                    <input type="hidden" name="id" value={{$subcategoria->id}}>
                                </form>
                            </div>
                        </div>
                    </li>
                    <?php $subcategoria2 = DB::table('b2bsubcategorias')->where(['subcategoria1' => $subcategoria->subcategoria1])->get();?>
                @endforeach
            @endforeach
        </ul>
    </div>
    </div>
@endsection


