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
                <h4>categoria</h4>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="wrapper ">

                    <form action="{{ route('deleteCategoria') }}" method="GET">

                                <label>categoria</label>
                                <?php  $categorias = DB::table('b2bcategorias')->where(['subcategoria1'=>null])->get();

                                ?>
                                <select class="form-control " name="categoria">
                                    @foreach($categorias as $categoria)
                                    <option value="{{$categoria->categoria}}">{{$categoria->texto}}</option>
                                    @endforeach
                                </select>




                        <br>
                        <button class="btn btn-primary floatRight"><i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                            borrar
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
@endsection
