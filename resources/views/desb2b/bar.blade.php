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
<!-------- rvr   selecciono el nombre del menu por medio de usuario----------------------->
<!---           -->




<?
    $ids = DB::table('menus_b2b')->where('id_menu', $id)->pluck('id_b2b');
    $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => NULL])->whereIn('id', $ids)->get();
?>





        <div class="floatLeft" >
            <li class="dropdown">
                <button class=" btn" data-toggle="dropdown" role="button"></button>


                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                        <form>
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
                            </ul>

                    </li>
                </ul>

        </div>


<div class="floatLeft" role="navigation">
  <form>
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










