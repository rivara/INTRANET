@csrf
<div class="row wrapperReporting center marginBottom20px">
    <div class="col-md-6">
        <u><h3>Marca Propia</h3></u>
    </div>
    <div class="col-md-6">
        <small>ventas a</small>
        <small>
            <?php

            // rvr formatear la fecha
            use Illuminate\Support\Facades\DB;
            $primerafecha=DB::connection('reporting')->table('historico_ventas_detalle')->orderBy('fecha','asc')->value("fecha");
            $ultimafecha=DB::connection('reporting')->table('historico_ventas_detalle')->orderBy('fecha','desc')->value("fecha");
            // echo ($primerafecha);
            echo(date("d-m-Y", strtotime($primerafecha)));
            echo ("&nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;");
            echo(date("d-m-Y", strtotime($ultimafecha)));
            ?>
        </small>
    </div>

    <!--- -->
    <div class="col-md-4">
        <p>Filtrado por </p>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <select name="opcion" class="form-control" id="codigo">
                <option value="CLIENTE">por cliente</option>
                <option value="ARTICULOS">por articulo</option>
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>
    <!-- -->
    <div class="col-md-4">
        <p>Tipo de grupo de cliente</p>
    </div>
    <div class="col-md-4">
       <?php
        $tipos= DB::connection('reporting')->select('select tipo_cliente from clientes group by tipo_cliente');
       ?>
           <select name="tipoGrupoCliente" class="form-control">
               @foreach($tipos as $tipo)
                    @foreach($tipo as $tip)
                        <option value={{$tip}}>{{$tip}}</option>
                    @endforeach
               @endforeach
           </select>

    </div>
    <div class="col-md-4">

    </div>

    <!--- -->
    <div class="col-md-4 codCli">
        <p>Codigo de cliente <br>
        <small>(*) si no se mete valor selecciona todos por defecto</small></p>
    </div>

    <div class="col-md-4 codCli">
        <input class="form-control" type="text" name="codigoCliente" id="codigoCliente">
    </div>
    <div class="col-md-4">

    </div>
    <!-- -->


    <div class="col-md-4">
        <p>Fecha de ventas</p>
    </div>
    <div class="col-md-4">
        <small>Desde</small>
        <input class="form-control floatLeft" type="date"  name="fechaDesde" required  value="<?php echo  date("Y-m-01",strtotime( ' - 1 year'));?>">
    </div>
    <div class="col-md-4">
        <small>Hasta</small>
        <input class="form-control floatLeft" type="date" name="fechaHasta" required  value="<?php echo date('Y-m-d', strtotime('last day of previous month')); ?>">
    </div>
    <!--- -->
    <div class="col-md-4 codArt">
        <p>Código de articulo</p>
    </div>
    <div class="col-md-4 codArt">
        <input class="form-control" type="text" name="codigoArticulo" id="codigoArticulo" >
    </div>
    <div class="col-md-4"></div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">
        @include('reporting.generador')
    </div>



    <!--- -->
    <div class="col-md-12">&nbsp;</div>
    <!-- -->
    <div class="col-md-12">
        <div class="floatLeft">
            <button type="submit" name="submit" value="Delete" class="btn btn-light">
                Realizar informe
            </button>
        </div>
        <div class="floatRight">

        </div>
    </div>
<br>
    <!-- -->
</div>
<script>
   $('.codArt').css("visibility", "hidden");

    $('#codigo').change(function(){
        if($(this).val() == 'CLIENTE'){
            $('.codCli').css("visibility", "visible");
            $('.codArt').css("visibility", "hidden");
            $("#codigoArticulo").val("");

        } else if($(this).val() == 'ARTICULOS'){
            $('.codCli').css("visibility", "hidden");
            $('.codArt').css("visibility", "visible");
            $("#codigoCliente").val("");
        }
    });

</script>