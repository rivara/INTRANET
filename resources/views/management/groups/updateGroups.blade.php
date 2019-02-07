@extends('layouts.app')
@section('content')
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
        <div  class="col-md-2" ></div>
        <div  class="col-md-3" >
            <h2 class="paddingtop10px">&nbsp;Modificacion del grupo
                de {{str_replace(array('["', '"]'), '',DB::table('grupos')->where('id', $grupoId)->pluck('nombre'))}}</h2>
        </div>
        <div   class="col-md-5"></div>
    </div>
    <br/>
    <div class="container wrapper mitad2">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('goAddGrupoPortal') }}" method="GET">
                    <button type="submit" class="btn btn-link floatRight">
                        <i class="fa fa-plus fa-3x"></i>
                    </button>

                    <input type="hidden" name="grupoId" value={{$grupoId}} >
                </form>
            </div>
            <div class="col-md-12 marginBottom10px">
                <form action="{{ route('updateGroups') }}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control " type="text" name="grupoNombre"
                                   value="{{str_replace(array('["', '"]'), '',DB::table('grupos')->where('id', $grupoId)->pluck('nombre'))}}"
                                   style="width:100% !important;">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary floatLeft">
                                <i class="fa fa-floppy-o fa-lg"></i>
                            </button>
                        </div>
                        <input type="hidden" name="grupoId" value={{$grupoId}} >
                        <div class="col-md-5"></div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-info" border="1px solid black">
                    @foreach(DB::table('grupos_portales')->where('id_grupo',$grupoId)->pluck('id_portal') as $portal)

                        <form action="{{ route('deleteGroupPortal') }}" method="GET">
                            <tr>
                                <td><?php
                                    $icono = str_replace(array('["', '"]'), '',
                                        DB::table('portales')->where('id', $portal)->pluck('icono'));

                                    echo "<i class='fa $icono' aria-hidden='true'></i>";
                                    echo "&nbsp;";
                                    echo "<b>" . str_replace(array('["', '"]'), '',
                                            DB::table('portales')->where('id', $portal)->pluck('nombre')) . "</b>";
                                    echo "&nbsp; -> &nbsp;";
                                    echo str_replace(array('["', '"]'), '',
                                        DB::table('portales')->where('id', $portal)->pluck('url'));
                                    echo "<br>";
                                    ?>
                                </td>
                                <td>
                                    <input type="hidden" name="portalId" value={{$portal}} >
                                    <input type="hidden" name="grupoId" value={{$grupoId}} >
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
@endsection

