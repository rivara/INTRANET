@extends('layouts.app')
@section('content')

        <form action="{{ route('goIndexSala') }}" method="GET">
            <button class="btn btn-primary  floatRight">volver</button>
            <input type="hidden" name="nombre" value="<?php echo $nombre ?>">

        </form>


    <div class="container wrapper">
        <h2>Reserva de la sala XXX</h2>
        <div class="row">

        <div class="col-md-6">
        <div class="row">
            <div class="col-md-10"></div>



            <div class="col-md-3">
                <p>Dia/mes/año</p>
            </div>
            <div class="col-md-4">
                <input class="form-control floatLeft" type="date" name="fechaDesde" required>
            </div>
            <div class="col-md-5"></div>

            <div class="col-md-3">
                <p>hora</p>
            </div>
            <div class="col-md-4">
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



                <div class="col-md-6">

                    <?php
                    $mails = DB::table('usuarios')->pluck('email');

                    ?>

                </div>




            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button class="btn-save btn btn-primary ">Save</button>
            </div>
        </div>
        </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">invitados</div>
                    <button type="submit" class="btn btn-primary"  data-toggle="modal" data-target="#ModalLoginForm">
                        <i class="fa fa-plus" ></i>
                        <i class="fa fa-user"></i>
                    </button>
                </div>
                <div class="col-md-12">

              </div>
            </div>

        </div>

    </div>

    <!-- Modal HTML Markup -->
    <div id="ModalLoginForm" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Mails</h1>
                </div>

                <div class="form-group">
                    <div>
                        <div class="modal-body">
                            <form role="form" method="POST" action="">
                                <input type="hidden" name="_token" value="">

                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Mail</label>
                                        <select class="form-control" id="exampleFormControlSelect1">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div>
                                        <button type="submit" class="btn btn-success">Añadir</button>


                                    </div>
                                </div>
                            </form>
                    </div>
                </div>



                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
