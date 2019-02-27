@extends('layouts.app')
@section('content')
  <div class="subtitle">
      <h4>Documentación</h4>

  </div>
      <div class="row paddingLeft20px">
          <div class="col-sm-2">
              <form action="{{ route('backHome') }}" method="GET">
                  <button class="btn btn-warning btnE "><i class=" fa fa-home  fa-lg"></i></button>
              </form>
          </div>
          <div class="col-sm-10"></div>
      </div>
    <div class="accordion" id="accordionExample"style="padding:1%">
        <div>
            <div class="card-header agora">
                <h5 class="mb-0">
                    <?php   $id= substr($id_usuario, 1, 1);
                        $gruposId = DB::table('usuarios_grupos')->where('id_usuario', $id)->pluck('id_grupo');
                            $grupos=[];
                            foreach($gruposId as $grupoId)
                            {
                                $grupos[] = DB::table('grupos')->where('id', $grupoId)->get();
                            }
                    ?>
                    @foreach($grupos as $grupo)
                            <div class="btn-group">
                                   <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                       {{$grupo[0]->nombre}}
                                       <span class="caret"></span>
                                   </a>
                                   <ul class="dropdown-menu">
                                       <button class="btn btn-warning collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                           <b>Documentacion GRUPO {{$grupo[0]->nombre}}</b>
                                       </button>
                                   </ul>
                               </div>
                    @endforeach
                </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">

                    <div class="row">
                        <table class="table-bordered table bg-white">
                            <thead>
                            <th></th>
                            <th>Descripción</th>
                            <th>Fichero</th>
                            <th>
                                <form class="floatRight" method="GET" action="{{ route('goAddFile') }}">
                                    <button type="submit" name="submit" value="Edit" class="btn  btnE floatRight"><i
                                                class="fa fa-plus fa-lg"></i></button>
                                    <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                                </form>
                            </th>
                            </thead>
                            <?php $ficheros = DB::table('archivos')->get(); ?>

                            <?php $ficheros = DB::table('archivos')->get(); ?>
                            @foreach($ficheros as $fichero)
                                    <tr>
                                        <td>
                                            <i class="{{$fichero->formato}} fa-3x marginLeft20" aria-hidden="true"></i>
                                        </td>
                                        <td>
                                            <b>{{$fichero->descripcion}}</b>
                                        </td>
                                        <td>
                                            <b>{{$fichero->nombre}}</b><br>
                                            ( {{$fichero->otros}} )
                                        </td>
                                        <td>
                                            <form class="floatLeft" method="GET"  action="{{ route('deleteFile') }}">
                                            <button type="submit" name="id" value="{{$fichero->id}}" class="btn btnE "><i
                                                        class="fa fa-trash fa-2x"></i></button>
                                                <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                                            </form>
                                            <form class="floatLeft" method="GET" action="{{route('download')}}">
                                                <button type="submit" name="id"  class="btn  btnE">
                                                    <i class="fa fa-download fa-2x" aria-hidden="true"></i>
                                                    <input type="hidden" name="id_usuario" value={{$id_usuario}} >
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
