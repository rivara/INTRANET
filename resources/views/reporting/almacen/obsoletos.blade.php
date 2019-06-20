@csrf
@extends('layouts.app')

<div class="row wrapperReporting center">
    <div class="col-md-6">
        <u><h3>Obsoletos</h3></u>
    </div>
    <div class="col-md-6">
        <small>ventas a</small>
        <small>
            <?php
            // rvr formatear la fecha
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
        <input class="form-control" type="text" name="articulo">
    </div>
    <div class="col-md-4"></div>
    <!--- -->
    <div class="col-md-4">
        <p>Código de proveedor</p>
    </div>
    <div class="col-md-4">
        <input class="form-control" type="text" name="proveedor">
    </div>
    <div class="col-md-4"></div>
    <!--- -->
    <div class="col-md-5">
        <input class="form-check-input" type="checkbox" name="stockmedio">
        <small>Realizar calculo del stock medio</small>
    </div>
    <div class="col-md-7"></div>
    <!-- -->
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

    <!-- -->
</div>
