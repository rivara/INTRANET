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
        <div class="bar">
            <!-- ALMACEN-->
            <li class="dropdown floatLeft">
                <button data-toggle="dropdown" class="btn" role="button">Almacen</button>
                <ul class="dropdown-menu">

                    <li class="dropdown-submenu">
                        <form action="{{ route('reportingRedirect')}}" method="get">
                            <button class="" type="submit"><p>**.- Indice de rotacion</p></button>
                            <input type="hidden" name="option" value="IndiceDeRotacion">
                        </form>
                    </li>
                </ul>
            </li>
        </div>
    </div>
</div>
<br>
