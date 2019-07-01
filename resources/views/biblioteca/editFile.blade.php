@extends('layouts.app')
@section('content')
    <div class="container mitad">
        <div class="row">
            <div class="col-md-2 paddingTop10px">
                <form method="GET" action="{{route('goSubGrupo')}}">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-primary  btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i></button>
                    <input type="hidden" name="id_usuario" value={{$id_usuario}}>
                    <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}}>
                    <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                </form>
            </div>
            <div class="col-md-10">
                &nbsp;<h4>Editar nombre fichero
                    <?php
                   // $val = substr($id_usuario, 1, strlen($id_usuario) - 2);
                    $val = DB::table('archivos')->where('id',$id_fichero)->pluck('descripcion');
                    $val = substr($val, 2, strlen($val) - 4);
                    echo $val;
                    ?>
                </h4>
            </div>
        </div>
        <br>
        <div class="container wrapper agora ">
            <form id="logout-form" action="{{route('updateFile')}}" method="GET">
                <label for="Nombre">Nombre</label>
                <input type="text" class="form-control width300px" name="descripcion" placeholder="Introduce Nombre" value=<?php echo $val; ?> >
                @if ($errors->has('nombre'))
                    <span class="error">
                    <strong>{{ $errors->first('nombre') }}</strong>
                </span>
                @endif
                <br>
                <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                    grabar
                </button>
                <input type="hidden" name="id_fichero" value={{$id_fichero}}>
                <input type="hidden" name="id_usuario" value={{$id_usuario}}>
                <input type="hidden" name="id_subgrupo" value={{$id_subgrupo}}>
                <input type="hidden" name="id_grupo" value={{$id_grupo}}>
            </form>
        </div>
    </div>
@endsection
