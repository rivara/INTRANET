@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-1">
                <form action="{{ route('backCarpeta') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-arrow-left  fa-lg"></i></button>
                    <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                </form>
            </div>
            <div class="col-md-11 paddingLeft30">

                <h2>Documentación</h2>
                <h6><?php
                    $val = DB::table('grupos')->where('id', $id_grupo)->pluck('nombre');
                    $value = substr($val, 2, strlen($val) - 4);
                    echo $value;
                    ?>
                </h6>
            </div>
        </div>
    </div>
    <br><br/>

    <div class=" container wrapper">


        <div>
            <button type="submit" class="btn btn-link floatRight" style="position: relative;bottom:50px;">
                <span class="fa fa-plus fa-3x"></span>
            </button>
        </div>


        <form class="float-right" method="GET" action="{{route('goAddSubGroup')}}">
            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
            <input type="hidden" name="id_grupo" value={{$id_grupo}}>

        </form>
        <?php $subgrupos = DB::table('grupos_subgrupos')->where('id_grupo', $id_grupo)->get(); ?>
        @if(count($subgrupos) > 0)
            <div class="row table">
                @foreach($subgrupos as $subgrupo)
                    <div class=" col-sm-4">
                        <form method="GET" action="{{route('goSubGroup')}}">
                            <span class="files">
                                    <?php
                                    $num = DB::table('archivos')->where('id_subgrupo', $subgrupo->id_subgrupo)->count();
                                    echo "<small>".$num."&nbsp;</small>";
                                    echo "<i class='fa fa-file ' aria-hidden='true'></i>";
                                    ?>
                            </span>
                            <button type="submit" class="btn btn-outline-info ">
                                <span class="fa fa-folder fa-3x"></span><br>
                                <small>
                                    <?php
                                    $var = DB::table('subgrupos')->where('id', $subgrupo->id_subgrupo)->pluck('nombre');
                                    $var = substr($var, 2, strlen($var) - 4);
                                    echo $var;
                                    ?>
                                </small>
                            </button>
                            <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                            <input type="hidden" name="id_grupo" value={{$id_grupo}}>
                            <input type="hidden" name="id_subgrupo" value={{$subgrupo->id_subgrupo}}>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    </div>
@endsection
