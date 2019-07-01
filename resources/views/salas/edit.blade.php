@extends('layouts.app')
@section('content')

    <form action="{{ route('goIndexSala') }}" method="GET">
        <button class="btn btn-primary  floatRight marginRight30px">volver</button>
        <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
    </form>


    <div class="container wrapper">
        <h2>Reserva de la sala XXX</h2>
        <div class="row">

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2">
                        <p>Dia/mes/año</p>
                    </div>
                    <div class="col-md-5">
                        <input class="form-control floatLeft" type="date" name="fechaDesde" required>
                    </div>
                    <div class="col-md-5"></div>

                    <div class="col-md-2">
                        <p>hora</p>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control floatLeft" type="time" name="fechaDesde" required>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control floatLeft" type="time" name="fechaDesde" required>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-md-12">
                        <p>Titulo</p>
                    </div>
                    <div class="col-md-12">
                        <input class="form-control floatLeft" type="text" name="fechaDesde" required>
                    </div>
                    <div class="col-md-12">
                              &nbsp;
                          </div>
                    <div class="col-md-12">
                        <p>Decripcion</p>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group purple-border">
                            <textarea class="form-control" id="exampleFormControlTextarea4" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#ModalLoginForm">
                            <i class="fa fa-plus"></i>
                            <i class="fa fa-user"></i>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary floatRight">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </div>
                    <div class="col-md-12">

                    </div>
                </div>

            </div>

        </div>
        <div class="floatRight">
            <button class="btn-save btn btn-primary   ">Save</button>
        </div>

    </div>
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
    <script type="text/javascript">

        $('.colorpicker').colorpicker();

    </script>
@endsection
