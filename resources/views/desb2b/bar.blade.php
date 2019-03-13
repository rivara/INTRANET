<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel headerC">
        <div class="container ">

            <span class="logoComafe"></span>

            <div class="collapse navbar-collapse ">
                <ul class="navbar-nav  ml-auto">
                    <li class="nav-item active">
                        <form action="{{ route('backHome') }}" method="GET">
                            <button class="btn btn-link colorC"><i class="fa fa-home fa-lg"></i>home</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>


<div class="submenu2">


    <?php
    $id = substr($id_usuario, 1, strlen($id_usuario) - 2);
    //Grupos del usuraio id
    $grupos_id = DB::table('usuarios_grupos')->where('id_usuario', 1)->get();
    $cuenta = DB::table('usuarios_grupos')->where('id_usuario', 1)->count();

    foreach ($grupos_id as $grupo_id) {

        $paginas_id[] = DB::table('menus_b2b')->where('id_b2b', $grupo_id->id_grupo)->pluck('id_b2b');
    }


    ?>

    @foreach($paginas_id as $pagina_id)
    <?php
            $categorias = DB::table('b2b')->where(['subcategoria1' => 0, 'subcategoria2' => 0,'id' => rvrQUITAR[]($pagina_id->id)])->get();?>
    @foreach($categorias as $categoria)
        <div class="floatLeft" >
            <li class="dropdown">
                <button class=" btn" data-toggle="dropdown" role="button">{{$categoria->texto}}</button>
                <?php  $subcategorias1 = DB::table('b2b')->where([
                    'categoria' => $categoria->categoria,
                    'subcategoria1' => 1,
                    'subcategoria2' => 0
                ])->get();?>
            @foreach($subcategorias1 as $subcategoria1)
                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                        <form action="{{ route($subcategoria1->accion)}}" method="get">
                            <input class="btn" type="submit" value="{{$subcategoria1->texto}}"/>
                            <input type="hidden" name="oAccion" value="inicio">
                            <input type="hidden" name="id_usuario" value="{{$id_usuario}}">
                        </form>
                        <?php  $subcategorias2 = DB::table('b2b')->where(['categoria' => $categoria->categoria, 'subcategoria1' => 1, 'subcategoria2' => 1])->get();?>
                        @foreach($subcategorias2 as $subcategoria2)
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route($subcategoria1->accion)}}" method="get">
                                        <input class="btn" type="submit" value=" {{$subcategoria2->texto}}"/>
                                        <input type="hidden" name="oAccion" value="inicio">
                                        <input type="hidden" name="id_usuario" value="{{$id_usuario}}">
                                    </form>
                                </li>
                            </ul>
                        @endforeach
                    </li>
                </ul>
            @endforeach
        </div>
@endforeach
        @endforeach
<div class="floatLeft" role="navigation">
  <form action="{{route('Index')}}" method="GET">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn ">
                index
            </button>
            <input type="hidden" name="oAccion" value="listado">
            <input type="hidden" name="id_usuario" value="{{$id_usuario}}">
    </form>
</div>
</div>
<br><br/>










