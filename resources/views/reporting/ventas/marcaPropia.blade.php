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
        <p>Codigo de cliente</p>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <select name="opcion" class="form-control" id="single">
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
        <input class="form-control" type="text" name="tipoCliente">
    </div>
    <div class="col-md-4">

    </div>

    <!--- -->
    <div class="col-md-4">
        <p>Codigo de cliente</p>
    </div>

    <div class="col-md-4">
        <input class="form-control" type="text" name="codigoCliente">
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
    <div class="col-md-4">
        <p>Código de articulo</p>
    </div>
    <div class="col-md-4">
        <input class="form-control" type="text" name="articulo" id="codArt" style="visibility: hidden">
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
            <button type="submit" name="submit" value="Delete"
                    class="btn btn-light">
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
    function displayVals() {
        var singleValues = $( "#single" ).val();
        var codArt = $( "#codArt" ).val();

        // When using jQuery 3:
        // var multipleValues = $( "#multiple" ).val();
        /*$( "p" ).html( "<b>Single:</b> " + singleValues +
            " <b>Multiple:</b> " + multipleValues.join( ", " ) );*/
       // alert(singleValues);
        if ("ARTICULOS" === singleValues){
            ("#codArt").css('visibility', 'visible');
            alert("*");
        }
    }

    $( "select" ).change( displayVals );
    displayVals();
</script>