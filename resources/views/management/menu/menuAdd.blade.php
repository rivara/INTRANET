@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Añadir Paginasss</h4>
        <form method="GET" action="{{ route('updateMenu') }}">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE "><i
                        class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id" value="{{$id}}">
        </form>
    </div>
    <!--<div class="input-group buscador">
            <input type="text" class="form-control busca" placeholder="Buscar..." aria-label="Recipient's username" aria-describedby="basic-addon2">
        </div> -->
    <div class="container mitad">

        <ul class="list-group list-group-flush">
            <li class="list-group-item list-group-item-primary">
                <form method="GET" action="{{ route('sddCategorias') }}">
                <button type="submit" name="submit" value="New" class="btn btn-link btnE floatRight"><i class="fa fa-floppy-o fa-lg"></i></button>

            </li>
            <?php
            $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
            $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => NULL])->whereNotIn('id', $ids)->get();
            ?>
            @foreach($categorias as $categoria)
                <li class="list-group-item bg-lightBlue">
                    <div class="row ">
                        <div class="col-md-2"></div>
                        <div class="col-md-4"><h4>{{$categoria->texto}}</h4></div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="categoria[]" value={{$categoria->id}}>
                                <input type="hidden" name="id" value="{{$id}}">
                            </div>

                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </li>
                <?php
                $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
                $subcategorias = DB::table('b2bcategorias')->where('subcategoria1', '!=',null)->where(['categoria' => $categoria->categoria])->whereNotIn('id', $ids)->get();

                ?>
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
            @endforeach
        </ul>
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


                    </div>
                    <div class="col-md-2"></div>
                </div>
            </li>
            <?php
            $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
            $subcategorias = DB::table('b2bcategorias')->where('subcategoria1', '!=',null)->where(['categoria' => $categoria->categoria])->whereNotIn('id', $ids)->get();

            ?>
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
                @endforeach
        </form>
    </div>


@endsection