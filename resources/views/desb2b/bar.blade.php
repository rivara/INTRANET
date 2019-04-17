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
    $id_usuario = substr($id_usuario, 1, strlen($id_usuario) - 2);
    $id_menu = DB::table('usuarios')->where('id', $id_usuario)->pluck('id_menu');
    $id_menu = substr($id_menu, 1, strlen($id_menu) - 2);
    $ids = DB::table('menus_b2b')->where('id_menu', $id_menu)->pluck('id_b2b');
    $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => NULL])->whereIn('id', $ids)->get();
    ?>



    @foreach($categorias as $categoria)
        <div class="floatLeft bar">
            <li class="dropdown">
                <button data-toggle="dropdown" class="btn" role="button">{{$categoria->texto}}</button>
                <?php
                $ids = DB::table('menus_b2b')->where('id_menu', $id_menu)->pluck('id_b2b');
                $subcategorias = DB::table('b2bcategorias')->where('subcategoria1', '!=',
                    null)->where(['categoria' => $categoria->categoria])->whereIn('id', $ids)->get();
                ?>
                @if((count($subcategorias))>1)
                    <ul class="dropdown-menu">
                        @foreach($subcategorias as $subcategoria)
                            <li class="dropdown-submenu">
                                <form action="{{ route($subcategoria->accion)}}" method="get">
                                    <button class="" type="submit"><p>{{$subcategoria->texto}}</p></button>
                                    <input type="hidden" name="oAccion" value="inicio">
                                    <input type="hidden" name="id_usuario" value="[{{$id_usuario}}]">
                                </form>

                                <!-- <form>
                                     <input class="btn" type="submit" value=""/>
                                     <input type="hidden" name="oAccion" value="inicio">
                                     <input type="hidden" name="id_usuario" value="">
                                 </form>

                                     <ul class="dropdown-menu">
                                         <li>
                                             <form>
                                                 <input class="btn" type="submit" value=""/>
                                                 <input type="hidden" name="oAccion" value="inicio">
                                                 <input type="hidden" name="id_usuario" value="">
                                             </form>
                                         </li>
                                     </ul>-->
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                            <form action="{{ route($categoria->accion)}}" method="get">
                                <input class="btn" type="submit" value="{{$categoria->texto}}"/>
                                <input type="hidden" name="oAccion" value="inicio">
                                <input type="hidden" name="id_usuario" value="{{$id_usuario}}">
                            </form>
                        </li>
                    </ul>
            @endif

        </div>
    @endforeach
</div>
<br>







