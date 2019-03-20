@extends('layouts.desb2b')

@include('desb2b.bar')


<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <h4>
            Hola,<b>...</b>

            Desde Mi Cuenta usted puede ver un resumen de sus actividades recientes y actualizar la información de su
            cuenta. Seleccione un enlace inferior para ver o editar información.
        </h4>
        <h5> editar

            Información de contacto
            Editar

            Demo .
            demosocio@comafe.es
            Cambiar la contraseña de la cuenta

        </h5>
        <img src="{{URL::asset('img/index.png')}}" alt="profile Pic">

    </div>
    <div class="col-md-2"></div>
</div>