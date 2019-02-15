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
                    <button class="btn btn-warning collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                      <b>Documentacion</b>
                    </button>
                </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">

                    <div class="row">
                        <table class="table table-striped table-bordered table-warning" border="1px solid black">
                            <thead>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Fichero</th>
                            <th>
                                <form class="floatRight" method="GET" action="{{ route('goAddFile') }}">
                                    <button type="submit" name="submit" value="Edit" class="btn  btnE floatRight"><i
                                                class="fa fa-plus fa-lg"></i></button>
                                    <input type="hidden" value="">
                                </form>
                            </th>
                            </thead>

                            <?php $ficheros = DB::table('archivos')->get(); ?>
                            @foreach($ficheros as $fichero)
                                <form class="floatLeft" method="GET"  action="{{ route('deleteFile') }}">

                                    <tr>
                                        <td>
                                            {{$fichero->id}}
                                        </td>
                                        <td>
                                            {{$fichero->descripcion}}
                                        </td>
                                        <td>
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                            {{$fichero->nombre}}
                                            {{$fichero->otros}}
                                        </td>
                                        <td>

                                            <button type="submit" name="id" value="{{$fichero->id}}" class="btn btn-light btnE "><i
                                                        class="fa fa-pencil fa-lg"></i></button>


                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
