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
            <div class="col-md-7"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4 wrapper">
                <form action="{{ route('deleteSubCategoria') }}" method="GET">
                    <div class="form-group">

                        <?php  $categorias = DB::table('b2bcategorias')->where(['subcategoria1' => null])->get();  ?>
                        <select name="categoria" class="form-control input-lg dynamic width200px" data-dependent="subcategoria">
                            @foreach($categorias as $categoria)
                                <option value="{{$categoria->categoria}}">{{$categoria->texto}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br/>
                    <div class="form-group">
                        <select name="subcategoria" id="subcategoria" class="form-control input-lg dynamic width200px"
                                data-dependent="city">
                            <option value="">Subcategoria</option>
                        </select>
                    </div>
                    <button class="btn btn-primary floatRight"><i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                        borrar
                    </button>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
        <script>


            $('.dynamic').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('chargeCategoria') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token, dependent: dependent},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        },

                        error: function (result) {
                            alert("error");
                        }
                    })
                }
            });

        </script>

@endsection
