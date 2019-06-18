@extends('layouts.desb2b')
@include('desb2b.bar')
<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h5>
                Hola,<b>...</b><br>
                Desde Mi Cuenta usted puede ver un resumen de sus actividades recientes y actualizar la información de su<br>
                cuenta. Seleccione un enlace inferior para ver o editar información.<br>
                Información de contacto<br>
                <br>
                <b>demosocio@comafe.es</b>
            </h5>
            <img src="{{URL::asset('img/index.png')}}" alt="profile Pic">

        </div>
        <div class="col-md-2"></div>
    </div>
</div>