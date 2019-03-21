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

    <div class="row">
        <div  class="col-md-2 paddingLeft50px" >
        <form   action="{{ route('redirect') }}" method="POST">
            @csrf
            <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                <i class="fa fa-arrow-left fa-lg"></i></button>
            <input type="hidden" name="id" value=1>
            <input type="hidden" name="name" value="" style="display:none;">
        </form>
        </div>
        <div  class="col-md-3" ></div>
        <div  class="col-md-3" >
        <h1 class="paddingtop10px">Crear usuarios</h1>
        </div>
        <div   class="col-md-4"></div>
    </div>
    <br>
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

                @if ($errors->has('idEmpresa'))
                    <span class="error">
                                        <strong>{{ $errors->first('idEmpresa') }}</strong>
                                    </span>
                @endif


                <br>
                <label>Menu</label>
                <div>
                    <select class="form-control width400px" name="id_menu">
                        <?php


                        $menu_id = DB::table('usuarios')->pluck('id_menu');



                            $menus = DB::table('menus')->get();
                            $selected = DB::table('menus')->first();
                        ?>
                        <option value="{{$selected->id}}" selected="selected">{{$selected->nombre}}</option>
                        @foreach($menus as $menu)
                            <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                        @endforeach
                    </select>
                </div>


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

