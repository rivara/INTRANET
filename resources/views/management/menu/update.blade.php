@extends('layouts.app')
@section('content')
    <div class="row">
            <div  class="col-md-2 paddingLeft50px" >
                <form action="{{ route('goMenu') }}" method="GET">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i></button>
                    <input type="hidden" name="id" value=1>
                    <input type="hidden" name="name" value="" style="display:none;">
                </form>
            </div>

            <div  class="col-md-2" ></div>
            <div  class="col-md-3" >
                <h2 class="paddingtop10px"> Administración del menu <?php echo DB::table('menus')->where('id', $id)->pluck('nombre'); ?></h2>
            </div>
            <div   class="col-md-5"></div>
        </div>
        <br/>



    <div class="container mitad">


        <ul class="list-group list-group-flush">
            <li class="list-group-item list-group-item-primary">
                <button class="btn btn-primary floatRight"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
            </li>
            <?php $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => NULL])->get();?>
            @foreach($categorias as $categoria)
                <li class="list-group-item bg-lightBlue">
                    <div class="row ">
                        <div class="col-md-2"></div>
                        <div class="col-md-4"><h4>{{$categoria->texto}}</h4></div>
                        <div class="col-md-4"><input type="checkbox" name="grupoCategoria[]" value={{$categoria->id}}></div>
                        <div class="col-md-2"></div>
                    </div>
                </li>
                <?php $subcategorias = DB::table('b2bcategorias')->where('subcategoria1', '!=',
                    '')->where(['categoria' => $categoria->categoria])->get();?>
                @foreach($subcategorias as $subcategoria)
                    <li class="list-group-item bg-lightBlue2">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><h5>{{$subcategoria->texto}}</h5></div>
                            <div class="col-md-4"><input type="checkbox" name="grupoSubcategoria1[]" value={{$categoria->subcategoria1}}></div>
                        </div>
                    </li>
                    <?php $subcategoria2 = DB::table('b2bsubcategorias')->where(['subcategoria1' => $subcategoria->subcategoria1])->get();?>
                @endforeach
            @endforeach
        </ul>
    </div>
    </div>
@endsection