@extends('layouts.app')
@section('content')
    <?php
    // se cargan los datos si estamos en edicion
    $usuario = "";
    $email = "";

    // si exsite el post entonces
    if(isset($id)){
        $usuario = DB::table('usuarios')->where('id', $id)->pluck('nombre');
        echo $nombre;
    }else{
     $usuarioUltimo=DB::table('usuarios')->orderBy('id', 'desc')->first();
     $id=intval($usuarioUltimo->id)+1;
    }
     ?>

    <div class="subtitle">
        <form  class="floatLeft" action="{{ route('redirect') }}" method="POST">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-light btnE ">
                <i class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id" value=1>
            <input type="hidden" name="name" value="" style="display:none;">
        </form>
        <h4  class="paddingtop10px">Crear usuarios</h4>
    </div>
    <div class="container wrapper mitad ">

            <form  id="logout-form" action="{{ route('recordUser') }}" method="GET">
                <h3>USUARIO</h3>
                <label for="Name">Nombre</label>
                <input type="text" class="form-control width400px" name="usuario" placeholder="Introduce Nombre" value={{$usuario}}>

                    @if ($errors->has('usuario'))
                        <span class="error">
                                        <strong>{{ $errors->first('usuario') }}</strong>
                                    </span>
                    @endif
                <br>

                <label for="Email1">E-mail</label>
                <input type="email" class="form-control width400px" name="email" placeholder="Introduce E-mail"
                       value="">
                    @if ($errors->has('email'))
                        <span class="error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                <br>
                <label for="Password">Contraseña(*)</label>
                <input type="password" class="form-control width400px" name="password" placeholder="Contrseña">

                <br>
                <label for="PasswordRep">Repite Contraseña</label>
                <input type="password" class="form-control width400px" name="passwordR" placeholder="Repite contrseña">
                    @if ($errors->has('passwordR'))
                        <span class="error">
                                        <strong>{{ $errors->first('passwordR') }}</strong>
                                    </span>
                    @endif
                <br>

                <label for="Password">idEmpresa</label>
                <input type="text" class="form-control width400px" name="idEmpresa" placeholder="id Empresa">



                <br><br />
                <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                    grabar
                </button>
                    <input type="hidden" name="id" value="{{$id}}">
                    <input type="hidden" name="nombre" value="{{$nombre}}">

            </form>
    </div>
    </div>
@endsection

