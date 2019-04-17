@extends('layouts.desb2b')
@include('desb2b.bar')

<div class="container">
    <div class="headerComafe">


        <h6 class="floatLeft">DAR DE BAJA LÍNEAS PENDIENTE DE SERVIR</h6>

        <form class="floatRight" name="frmDatos" method="post" action="">
            <button class="btn">
                <i class="fa fa-times-circle fa-2x "
                   title="Elimina las lineas marcadas"></i>
            </button>
            <button class="btn">
                <i class="fa fa-file-excel-o  fa-2x "
                   title="Exportar a fichero Excel"></i>
            </button>

            <button class="btn">
                <i class="fa fa-question  fa-2x "
                   title=""></i>
            </button>
            <button class="btn">
                <i class="fa fa-exclamation-triangle  fa-2x "
                   title="VACIAR completamente la cartera de pedidos pendientes"></i>
            </button>
        </form>
    </div>


    <div class="row">
        <div class="col-md-4">
            <p>Introduzca el número de socio:</p>
            <form action="{{ route('WebClientesCart') }}" method="get">
                <div class="input-group mb-3">

                    <input type="text" name="socio" class="form-control" value="2">
                    <div class="input-group-append">
                        <button class="btn" type="submit">
                            <i class="fa fa-search fa-lg"></i>
                        </button>
                    </div>

                </div>
                <input type="hidden" name="oAccion" value="otro">
                <input type="hidden" name="id_usuario" value="{{$id_usuario}}">
            </form>
        </div>
        <div class="col-md-8"></div>


        <div class="col-md-12">
            <table class="table table-stripedComafe table-comafe borderComafe">
                <thead>
                <th> Sel.</th>
                <th>PEDIDO</th>
                <th>FECHA</th>
                <th>ALMACEN</th>
                <th>REF.CLIENTE</th>
                <th>ARTICULO</th>
                <th>CANTIDAD</th>
                <th>STOCK</th>
                <th>COMENTARIO</th>
                </thead>
                <tr>

                @if($oAccion=="listado")
                    @foreach($datosClientesCart as $datoClientesCart)
                        <tr>
                            <td>
                                <input type="checkbox" name="vehicle" value="Bike">
                            </td>
                            <td>
                                {{$datoClientesCart->pedido()}}
                            </td>
                            <td>
                                {{$datoClientesCart->fecha()}}
                            </td>
                            <td>
                                {{$datoClientesCart->almacen()}}
                            </td>
                            <td>
                                {{$datoClientesCart->nlinea()}}
                            </td>
                            <td>
                                {{$datoClientesCart->descrip()}}

                            </td>
                            <td>
                                {{$datoClientesCart->cantidad()}}
                            </td>
                            <td>
                                <?php

                                $datosArticulos->limpiar();
                                $datosArticulos->codigo($datoClientesCart->cdarti());





                                    $lnArtAct = $datosArticulos->indAct();
                                    if ($datoClientesCart->almacen() == 'PRINCIPAL') {
                                        $lcFechaProxRepo = $datosArticulos->recepMadAux();
                                        $lnStockActual   = $datosArticulos->stock();
                                    } else {
                                        if ($datoClientesCart->almacen() == 'ALICANTE') {
                                            $lcFechaProxRepo = $datosArticulos->recepAliAux();
                                            $lnStockActual   = $datosArticulos->stockAli();
                                        }
                                    }




                                if ($datosArticulos->stock() == 0) {
                                    $lcStockActual = '<span style="color: red;">BAJA</span>';
                                    echo $lcStockActual;
                                } else {
                                    if ($datosArticulos->stock() <> 0) {
                                        $lcStockActual = number_format($datosArticulos->stock(), 2, ',', '.');
                                        echo $lcStockActual;
                                    }

                              }
                                ?>
                            </td>
                            <td>
                                    {{$lcFechaProxRepo}}
                                -- falta --
                            </td>

                        </tr>
                        @endforeach
                        @endif()

                        </tr>
            </table>
        </div>
    </div>
</div>


