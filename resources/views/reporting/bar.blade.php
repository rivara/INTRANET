@include('layouts.desb2b')
<br>
<div class="row container center">
    <div class="col-md-1"></div>

    <div class="col-md-1">
        <form action="{{ route('backHome') }}" method="GET">
            <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
        </form>
    </div>
    <div class="col-md-1">
        <h1>REPORTING(beta)</h1>
    </div>
    <div class="col-md-9"></div>
    <div class=" col-md-12">
        <div class=" bar">
            <!-- ARTICULOS -->
            <li class="dropdown floatLeft">
                <button data-toggle="dropdown" class="btn" role="button">Articulos</button>
                <ul class="dropdown-menu">

                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>1.- Familias - Resumen Familias</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>2.- Familias - Rancking for articulos</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>3.- Familias- Rancking por clientes</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>4.- Importaciones</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>5.- Atributos</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>6.- Movimientos</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>7.- Listado completo</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                </ul>
            </li>

            <!-- CLIENTES-->
            <li class="dropdown floatLeft">

                <button data-toggle="dropdown" class="btn" role="button">Clientes</button>
                <ul class="dropdown-menu">
                    <li class="dropdown-submenu">
                    </li>
                </ul>
            </li>

            <!-- PROVEEDORES-->
            <li class="dropdown floatLeft">

                <button data-toggle="dropdown" class="btn" role="button">Proveedores</button>
                <ul class="dropdown-menu">
                    <li class="dropdown-submenu">
                    </li>
                </ul>
            </li>


            <!-- COMPRAS-->
            <li class="dropdown floatLeft">

                <button data-toggle="dropdown" class="btn" role="button">Compras</button>
                <ul class="dropdown-menu">

                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>1.- Resumen Devoluciones</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>2.- Hojas demanda</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                </ul>
            </li>

            <!-- VENTAS-->
            <li class="dropdown floatLeft">
                <button data-toggle="dropdown" class="btn" role="button">Ventas</button>
                <ul class="dropdown-menu">
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>1.- Articulos Catalogo</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>2.- Resumen mensual GFK</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>3.- Resumen mensual</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>

                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>4.- Gastos transparentes</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>5.- Rappels Socios </p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>6.- Detalles Facturas</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>7.- Numero de clientes</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>8.- Marca Propia</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                </ul>
            </li>

            <!-- ALMACEN-->
            <li class="dropdown floatLeft">

                <button data-toggle="dropdown" class="btn" role="button">Almacen</button>
                <ul class="dropdown-menu">
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>1.- Obsoletos</p></button>
                            <input type="hidden" name="option" value="obsoletos">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>2.- Indice de rotacion</p></button>
                            <input type="hidden" name="option" value="IndiceDeRotacion">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>3.-Seguimientos envio</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>4.- Inventario Navision</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>
                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>5.- Inventario por almacen</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li><li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>6.- Inventario x tiempo de movimiento</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li><li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>7.- Mermas</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li><li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>8.- Marca Propia</p></button>
                            <input type="hidden" name="option" value="otro">
                        </form>
                    </li>



                </ul>
            </li>


        </div>
    </div>
</div>
<br>
