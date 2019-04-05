@extends('layouts.desb2b')
@include('desb2b.bar')
<p class="breadcrumb-item sp">logs</p>
<div class="headerComafe">
    <h6>DAR DE BAJA LÍNEAS PENDIENTE DE SERVIR</h6>
</div>

<body>

    <!-- CAMPOS DEL FORMULARIO -->
<!--<input id="oNumObj" name="oNumObj" type="hidden" value=" />-->
    <p>Introduzca el número de socio:</p><input  id="pIdMagento" name="pIdMagento">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button">Button</button>
        </div>
    </div>

    <!-- FIN CAMPOS DEL FORMULARIO -->
    <form id="frmDatos" name="frmDatos" method="post" action="">


    <div class="box-title"  style="display: flex; flex-flow: row wrap;">
            <div style="display: flex: 1 auto;"><h2 class="box-title"></h2>
            </div>

            <div id="dvBotones" style="flex: 1 auto;">
                <div style="height: 100%; display: flex; flex-flow: row wrap; justify-content: flex-end;">

                    <i class="fa fa-times-circle fa-icono-header"
                       title="Elimina las lineas marcadas"></i>
                    <i class="fa fa-file-excel-o fa-icono-header"
                       title="Exportar a fichero Excel"</i>


                    <i class="fa fa-print fa-icono-header"
                       title="Imprimir la cartera de pendientes"></i>

                    <i class="fa fa-question fa-icono-header" style="cursor: none"
                       title=""></i>

                    <i class="fa fa-exclamation-triangle fa-icono-header"
                       title="VACIAR completamente la cartera de pedidos pendientes"></i>

                </div>
            </div>

        </div>




    <div id="dvContenido">
        <div class="table-flex" style="border: 1px solid">
            <div class="tr-flex th-flex">
                    <div class="td-flex" style="justify-content: center"> Sel.</div>
                    <div class="td-flex" style="justify-content: flex-start">PEDIDO</div>
                    <div class="td-flex" style="justify-content: center">SUC</div>
                    <div class="td-flex" style="justify-content: center">FECHA</div>
                    <div class="td-flex" style="justify-content: center">ALMACEN</div>
                    <div class="td-flex" style="justify-content: flex-start">REF.CLIENTE</div>
                    <div class="td-flex texto-con-saltos" style="flex-grow: 5; justify-content: flex-start">ARTICULO</div>
                    <div class="td-flex" style="justify-content: center">CANTIDAD</div>
                    <div class="td-flex" style="justify-content: center">STOCK</div>
                    <div class="td-flex texto-con-saltos" style="flex-grow: 3; justify-content: flex-start">COMENTARIO</div>
                </div>
            </div>
    </div>
</form>

</body>
</html>


