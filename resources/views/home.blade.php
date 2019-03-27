@extends('layouts.app')
@section('content')
    <div class="title">
        <h1>Hola <?php echo $nombre[0] ?></h1>
    </div>

    <div class="center">
        <div class="row table">
            @if(! empty($portales))
          @foreach ($portales as $portal)
            <div class=" col-sm-4">
                <form action="{{ route('redirect') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary"  formtarget= <?php echo($portal[0]->target)?>>
                        <i class="fa  <?php echo($portal[0]->icono)?> fa-4x" aria-hidden="true"></i>
                        <p> <?php echo($portal[0]->nombre)  ?></p>

                        <input type="hidden" name="id" value="<?php echo($portal[0]->id) ?>">
                        <input type="hidden" name="nombre" value="<?php echo $nombre[0] ?>">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>">
                        <input type="hidden" name="oAccion" value="inicio">
                </form>
            </div>
            @endforeach
                @else
                <div class="centerLogin">
                    <h2> Este usuario no tiene asociados grupos</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
