<?php ini_set('MAX_EXECUTION_TIME',1000); ?>
@csrf
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row wrapperReporting center marginBottom20px">
    <div class="col-md-8">
        <u><h3>Indice de rotacion</h3></u>
    </div>
    <div class="col-md-4">
        <small><b>datos actualizados</b></small>
        <small>
            <?php
            // rvr formnatear la fecha
            use Illuminate\Support\Facades\DB;
            $primerafecha=DB::connection('reporting')->table('historico_ventas')->orderBy('fecha','asc')->value("fecha");
            $ultimafecha=DB::connection('reporting')->table('historico_ventas')->orderBy('fecha','desc')->value("fecha");
            // echo ($primerafecha);
            echo(date("d-m-Y", strtotime($primerafecha)));
            echo ("&nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;");
            echo(date("d-m-Y", strtotime($ultimafecha)));
            ?>
        </small>
    </div>
    <!--- -->
    <div class="col-md-12"><br></div>
    <!--- -->
    <div class="col-md-4">
        <p>Sacar informe del almacen</p>
    </div>
    <div class="col-md-4">
        <select name="almacen" class="form-control">
            <option value="PRINCIPAL">MADRID</option>
            <option value="ALICANTE">ALICANTE</option>
        </select>
    </div>
    <div class="col-md-4"></div>
    <!--- -->
    <div class="col-md-4">
        <p>Periodo a analizar</p>
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
        <p>Proveedor</p>
    </div>
    <div class="col-md-4">
        <input class="form-control" type="text" name="proveedor">
    </div>
    <div class="col-md-4"></div>
    <!--- -->
    <div class="col-md-4">
        <p>Familia analizar</p>
    </div>
    <div class="col-md-4">
        <input class="form-control" type="text" name="familia">
    </div>
    <div class="col-md-4">
        <input class="form-check-input" type="checkbox" name="niveles" >
        <small>Incluir niveles inferiores (solo si esta rellena la familias)</small>
    </div>
    <!---
    <div class="col-md-5">
        <input class="form-check-input" type="checkbox" name="calculo">
        <small>Realizar calculo del stock medio</small>
    </div>
    <div class="col-md-7"></div>
      -->
    <div class="col-md-12">&nbsp;</div>
    <!-- -->
    <div class="col-md-12">
        @include('reporting.generador')
    </div>
    <!--- -->
    <div class="col-md-12">&nbsp;</div>
    <!-- -->
    <div class="col-md-12 paddingLeft40">

        <button type="submit" name="submit" value="informe" id="submit"
                data-toggle="modal"
                data-target="#myModal"
                class="btn btn-light">
            Realizar informe
        </button>
        <br>
    </div>
  </div>
</div>
</form>

