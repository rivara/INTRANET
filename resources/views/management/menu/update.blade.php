@extends('layouts.app')
@section('content')
    <div class="container">
    <div class="row">
        <div class="col-md-2 paddingLeft50px">
            <form action="{{ route('goMenu') }}" method="GET">
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                <input type="hidden" name="id" value={{$id}}>
                <input type="hidden" name="name" value="" style="display:none;">
            </form>
        </div>

        <div class="col-md-2"></div>
        <div class="col-md-3">
            <h4 class="paddingLeft100px width600px"> Eliminar subgrupos de
            <?php
                $title = DB::table('menus')->where('id', $id)->pluck('nombre');
                $title = substr($title, 2, strlen($title) - 4);
                echo   $title;
                ?>
            </h4>
        </div>
        <div class="col-md-5"></div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-4" >
            <li class="list-group-item list-group-item-primary height60px">
                <form action="{{ route('goMenuAdd') }}" method="GET">
                    <button class="btn floatRight" type="submit">
                        <i class="fa fa-plus btn-link fa-lg" aria-hidden="true"></i>
                    </button>
                    <input type="hidden" name="id" value="{{$id}}">
                </form>

            </li>
            <?php

            $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
            $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => NULL])->whereIn('id', $ids)->get();


            ?>
            @foreach($categorias as $categoria)
                <li class="list-group-item bg-lightBlue">
                    <div class="row ">
                        <div class="col-md-2"></div>
                        <div class="col-md-4"><h4>{{$categoria->texto}}</h4></div>
                        <div class="col-md-4">
                           <!-- <form action="{# route('deleteMenuCategoria') }}" method="GET">
                                <button class="btn floatRight"><i class="fa fa-trash-o fa-lg btn-link"
                                                                  aria-hidden="true"></i></button>
                                <input type="hidden" name="id_categoria" value={#$categoria->id}}>
                                <input type="hidden" name="id" value="{#$id}}">
                            </form>-->

                            <form method="GET" action="{{ route('goDropSubCategoria') }}">
                                <button class="btn floatRight"><i class="fa fa-list fa-lg btn-link" aria-hidden="true"></i></button>
                                <input type="hidden" name="id_categoria" value={{$categoria->categoria}}>
                                <input type="hidden" name="id" value="{{$id}}">
                            </form>


                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </li>
            @endforeach
        </div>
        <div class="col-md-8">

            @if(isset($id_categoria))
                <?php
                $title = DB::table('b2bcategorias')->where('categoria', $id_categoria)->where('subcategoria1',null)->pluck('texto');
                $title = substr($title, 2, strlen($title) - 4);
                ?>
                <h4><u>{{$title}}</u></h4>
                <?php
                 $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
                 $subcategorias = DB::table('b2bcategorias')->where('subcategoria1','!=',null)->where('categoria',$id_categoria)->wherein('id', $ids)->get();
                ?>
                @foreach($subcategorias as $subcategoria)
                    <li class="list-group-item bg-lightBlue2">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><h5>{{$subcategoria->texto}}</h5></div>
                            <div class="col-md-4">
                                <form action="{{ route('deleteMenuSubCategoria') }}" method="GET">
                                    <button class="btn  floatRight"><i class="fa fa-trash-o fa-lg btn-link" aria-hidden="true"></i></button>
                                    <input type="hidden" name="id_categoria" value="{{$id_categoria}}">
                                    <input type="hidden" name="id" value="{{$id}}">
                                    <input type="hidden" name="id_b2b" value="{{$subcategoria->id}}">

                                </form>
                            </div>
                        </div>
                    </li>

                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection


