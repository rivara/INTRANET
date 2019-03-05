@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-1">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-info btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>
            <div class="col-md-11 paddingLeft30">
                <h2>Documentación</h2>
            </div>
        </div>
    </div>
    <br><br/>
    <div class="agora container wrapper">
        <div class="center">
            <div class="row table">
                <?php   $id = substr($id_usuario, 1, 1);
                $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $id)->pluck('id_grupo');
                $grupos = [];
                foreach ($gruposId as $grupoId) {
                    $grupos[] = DB::table('grupos')->where('id', $grupoId)->get();
                }
                ?>
                @foreach($grupos as $grupo)
                    <div class="col-sm-4">
                        <form action="{{ route('goSubCarpeta') }}" method="GET">
                            <span class="files">
                                <p>
                                    <?php
                                    $num = DB::table('grupos_subgrupos')->where('id_grupo', $grupo[0]->id)->count();
                                    echo "<small>".$num."&nbsp;</small>";
                                    echo "<i class='fa fa-folder' aria-hidden='true'></i>";
                                    ?>
                                </p>
                            </span>
                            <button type="submit" class="btn btn-outline-info">
                                <span class="fa fa-folder fa-3x"></span><br>
                                <small> <?php echo($grupo[0]->nombre); ?></small>
                            </button>
                            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                            <input type="hidden" name="id_grupo" value={{$grupo[0]->id}} >
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
