@extends('layouts.app')
@section('content')

    <form action="{{ route('goIndexSala') }}" method="GET">
        <button class="btn btn-primary  floatRight marginRight30px">
            <i class="fa fa-arrow-left"></i>
        </button>
        <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
    </form>



    <script src="{{ asset('js/jquery2.min.js') }}"></script>
    <script src="{{ asset('js/tag-editor.js') }}"></script>
    <script src="{{ asset('js/list.js') }}"></script>
    <link href="{{ asset('css/list.css') }}" rel="stylesheet">

    <div class="container wrapper mitad2 ">
        <form action="{{ route('recordCalendar') }}" method="GET">
            <div class="row">
               <div class="col-md-8">
                    <h2>Reserva de la sala {{$salaOpcion}}</h2>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary floatRight">
                        <i class="fa fa-calendar"></i>
                    </button>
                </div>
                <div class="col-md-12"><br></div>


                <div class="col-md-2">
                    Dia/mes/año
                </div>
                <div class="col-md-6">
                    <input class="form-control floatLeft" type="date" name="fecha" required>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-12"><br></div>


                <div class="col-md-2">
                    hora
                </div>
                <div class="col-md-3">
                    <input class="form-control " type="time" name="horaDesde" required>
                </div>
                <div class="col-md-3">
                    <input class="form-control " type="time" name="fechaHasta" required>
                </div>
                <div class="col-md-3"></div>

                <div class="col-md-12"><br></div>

                <div class="col-md-12">
                    Titulo
                </div>
                <div class="col-md-12">
                    <input class="form-control floatLeft" type="text" name="titulo" required>
                </div>
                <div class="col-md-12"><br></div>
                <div class="col-md-12">
                   Decripcion
                </div>
                <div class="col-md-12">
                    <div class="form-group purple-border">
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                   Mail
                </div>
                <div class="col-md-12">

                        <textarea id="hero-demo">example tags, sortable, autocomplete, edit in place, tab/cursor navigation, duplicate check, callbacks, copy-paste, placeholder, public methods, custom delimiter, graceful degradation</textarea>

                </div>
            </div>
            <div class="floatRight">
                <button class="btn-save btn btn-primary   ">
                    <i class="fa fa-floppy-o"></i>
                </button>
            </div>
            <input type="hidden" name="salaOpcion" value={{$salaOpcion}}>
    </div>

   </div>
   </form>
    <!-- Modal HTML Markup -->
    <div id="ModalLoginForm" class="modal fade">
        <div class="modal-dialog">


            <div class="modal-content ">
                <div class="modal-header">
                    <h1 class="modal-title">Mails</h1>
                </div>


                <form role="form" method="POST" action="">


                    <div class="form-group margin5">
                        <label for="exampleFormControlSelect1">Mail</label>
                        <select class="form-control" id="exampleFormControlSelect1">
                            <?php $mails = DB::table('usuarios')->pluck('email'); ?>

                            @foreach($mails as $mail)
                                <option><?php echo $mail ?></option>
                            @endforeach
                        </select>

                        <div class="floatRight marginBottom10px margintop10px">
                            <button type="submit" class="btn btn-success">Añadir</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>




@endsection
