@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2 paddingLeft50px">
                <form method="GET" action="{{ route('updateMenu') }}">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i></button>
                        <input type="hidden" name="id" value="{{$id}}">
                </form>
            </div>

            <div class="col-md-2"></div>
            <div class="col-md-3">
                <h4 class="paddingLeft100px width600px"> Añadir subgrupos al
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
            <div class="col-md-4">
                <?php
                $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
                $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => NULL])->whereIn('id', $ids)->get();
                ?>
                @foreach($categorias as $categoria)
                    <li class="list-group-item bg-lightBlue">
                        <div class="row ">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><h4>{{$categoria->texto}}</h4></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <form method="GET" action="{{ route('goAddSubCategoria') }}">
                                    <button class="btn floatRight"><i class="fa fa-list fa-lg btn-link"
                                                                      aria-hidden="true"></i></button>
                                    <input type="hidden" name="id_categoria" value={{$categoria->categoria}}>
                                    <input type="hidden" name="id" value="{{$id}}">
                                </form>
                            </div>
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

                    <form method="GET" action="{{ route('sddCategorias') }}">

                    <?php
                    $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
                    $subcategorias = DB::table('b2bcategorias')->where('subcategoria1', '!=',null)->where(['categoria' => $id_categoria])->whereNotIn('id', $ids)->get();
                    $num= count($subcategorias);
                    ?>
                    @if($num>0)
                            <div class="height60px">
                                <button class="btn floatRight "><i class="fa fa-floppy-o fa-2x text-primary" aria-hidden="true"></i></button>
                            </div>
                    @endif
                    @foreach($subcategorias as $subcategoria)
                        <li class="list-group-item bg-lightBlue2">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4"><h5>{{$subcategoria->texto}}</h5></div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="categoria[]" value={{$subcategoria->id}}>
                                        <input type="hidden" name="id" value="{{$id}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php $subcategoria2 = DB::table('b2bsubcategorias')->where(['subcategoria1' => $subcategoria->subcategoria1])->get();?>
                    @endforeach
                    </form>
                @endif
            </div>
        </div>
@endsection