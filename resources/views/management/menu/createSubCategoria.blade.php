@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5 paddingLeft50px">
                <form action="{{ route('goMenu') }}" method="GET">
                    @csrf
                    <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                        <i class="fa fa-arrow-left fa-lg"></i></button>
                    <input type="hidden" name="id" value={{$id}}>
                    <input type="hidden" name="name" value="" style="display:none;">
                </form>
            </div>

            <div class="col-md-7">
                <h4>Subcategoria</h4>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="container wrapper ">

                    <form action="{{ route('saveSubCategoria') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label>categoria</label>
                                <?php  $categorias = DB::table('b2bcategorias')->where(['subcategoria1'=>null])->get();  ?>
                                <select class="form-control" name="categoria">
                                    @foreach($categorias as $categoria)
                                    <option value="{{$categoria->categoria}}">{{$categoria->texto}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>subcategoria</label>
                                <input type="text" class="form-control" name="texto">
                            </div>
                            <div class="col-md-4">
                                <label>Accion</label>
                                <input type="text" class="form-control" name="accion">
                            </div>
                        </div>

                        <br>
                        <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                            grabar
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
@endsection