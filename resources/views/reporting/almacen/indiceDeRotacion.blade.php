@csrf
<div class="row wrapperReporting center marginBottom20px">
    <div class="col-md-8">
        <u><h3>Indice de rotacion</h3></u>
    </div>
    <div class="col-md-4">
        <small><b>stock medio actualizado a</b></small>
        <small>01/06/2013 al 27/02/2019</small>
    </div>
    <!--- -->
    <div class="col-md-12"><br></div>
    <!--- -->
    <div class="col-md-4">
        <p>Sacar informe del almacen</p>
    </div>
    <div class="col-md-4">
        <select name="almacen" class="form-control">
            <option value="MADRID">MADRID</option>
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
        <input class="form-control floatLeft" type="date"  name="fechaDesde">
    </div>
    <div class="col-md-4">
        <small>Hasta</small>
        <input class="form-control floatLeft" type="date" name="fechaHasta">
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
        <small>Incluir niveles inferiores (solo si esta rellena la familiaS)</small>
    </div>
    <!--- -->
    <div class="col-md-5">
        <input class="form-check-input" type="checkbox" name="calculo">
        <small>Realizar calculo del stock medio</small>
    </div>
    <div class="col-md-7"></div>
    <!--- -->
    <div class="col-md-12">&nbsp;</div>
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
</form>

