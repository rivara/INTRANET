@extends('layouts.app')
@section('content')
    <?php
    // se cargan los datos si estamos en edicion
    $nombre = "";
    $email = "";

    foreach ($usuarios as $usuario) {
        $nombre = $usuario->nombre;
        $email = $usuario->email;
    }
    ?>

    <div class="row">
        <div class="col-md-2 paddingLeft50px">
            <form action="{{ route('redirect') }}" method="POST">
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                <input type="hidden" name="id" value=1>
                <input type="hidden" name="name" value="" style="display:none;">
            </form>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3">
            <h1 class="paddingtop10px">&nbsp;Modificación usuarios</h1>
        </div>
        <div class="col-md-5"></div>
    </div>
    <br/>
    <div class="container wrapper">
        <h1>USUARIO</h1>
        <div class="row greyBlueC">
            <div class="col-md-4">
                <div class="title2">
                    <h5><u>DATOS</u></h5>
                </div>
                <form id="logout-form" action="{{ route('updateUser') }}" method="GET">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control " name="nombre" placeholder="Introduce Nombre"
                           value="<?php echo $nombre ?>">
                    @if ($errors->has('nombre'))
                        <span class="error">
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                    </span>
                    @endif
                    <br>
                    <label for="Email">E-mail</label>
                    <input type="email" class="form-control " name="email" placeholder="Introduce E-mail"
                           value={{$email}}>
                    @if ($errors->has('email'))
                        <span class="error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                    <br>
                    <label for="Password">Contraseña</label>
                    <input type="password" class="form-control " name="password" placeholder="Contrseña">

                    <br>
                    <label for="PasswordRep">Repite Contraseña</label>
                    <input type="password" class="form-control " name="passwordR"
                           placeholder="Repite contrseña">
                    @if ($errors->has('passwordR'))
                        <span class="error">
                                        <strong>{{ $errors->first('passwordR') }}</strong>
                                    </span>
                    @endif
                    <br>
                    <label>Menu</label>
                    <div>
                        <select class="form-control" name="id_menu">
                            <?php


                            $menu_id = DB::table('usuarios')->where('id', $id)->pluck('id_menu');


                            if ($menu_id == "[null]") {
                                $menus = DB::table('menus')->get();
                                $selected = DB::table('menus')->first();
                            } else {
                                $menus = DB::table('menus')->where('id', '!=', $menu_id)->get();
                                $selected = DB::table('menus')->where('id', "=", $menu_id)->first();
                            }
                            ?>
                            <option value="{{$selected->id}}" selected="selected">{{$selected->nombre}}</option>
                            @foreach($menus as $menu)
                                <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('passwordR'))
                        <span class="error">
                                        <strong>{{ $errors->first('passwordR') }}</strong>
                                    </span>
                    @endif
                    <br>
                    <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                        grabar
                    </button>
                    <input type="hidden" name="usuarioId" value={{$usuario->id}} >
                </form>
            </div>

            <div class="col-md-4">
                <form action="{{ route('goAddUserGroup') }}" method="GET">
                    <div class="title2">
                        <button type="submit" class="btn btn-link floatLeft">
                            <i class="fa fa-plus"></i>
                        </button>
                        <h5 class="floatLeft">GRUPOS</h5>
                        <input type="hidden" name="usuarioId" value={{$usuario->id}} >
                    </div>
                    <input type="hidden" name="id" value="{{$id}}">
                </form>
                <div class="row justify-content-center">
                    <table class="table table-striped table-bordered" border="1px solid black">
                        <?php $grupos = DB::table('usuarios_grupos')->where('id_usuario', $usuario->id)->get(); ?>
                        @foreach($grupos as $grupo)
                            <form action="{{ route('deleteUserGroup') }}" method="GET">
                                <tr>
                                    <td>
                                        {{ str_replace(array('["','"]'), '',DB::table('grupos')->where('id',$grupo->id_grupo)->pluck('nombre'))}}
                                        <input type="hidden" name="usuarioId" value={{$usuario->id}} >
                                        <input type="hidden" name="grupoId" value={{$grupo->id_grupo}} >
                                        <input type="hidden" name="id" value="{{$id}}">
                                    </td>
                                    <td>
                                        <button type="submit" name="submit" value="Delete"
                                                class="btn btn-link btnE ">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </form>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="col-md-4" >
                <div class="title2">
                    <h5>PORTALES CON ACCESO </h5>
                </div>
                <div class="portales">
                    <?php
                    $array = array();
                    $i = 0;
                    $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $usuario->id)->pluck('id_grupo');


                    foreach ($gruposId as $grupoId) {
                        $portalesId = array(
                            DB::table('grupos_portales')->where('id_grupo', $grupoId)->pluck('id_portal')
                        );
                        $array = array_merge($array, $portalesId);
                        $i++;
                    }

                    $i = 0;
                    foreach ($array as $field) {
                        foreach ($field as $num) {
                            $array2[$i] = $num;
                            $i++;
                        }
                    }

                    //Elimino valoeres repetidos
                    if (!empty($array2)) {
                        $array2 = array_unique($array2);
                        foreach ($array2 as $i) {
                            echo $i . ".  ";
                            echo str_replace(array('["', '"]'), '',
                                    DB::table('portales')->where('id', $i)->pluck('nombre')) . '<br>';
                            echo '<br>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
@endsection

