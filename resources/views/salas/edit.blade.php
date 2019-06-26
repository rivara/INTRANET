@extends('layouts.app')
@section('content')


    <div class="container wrapper mitad2">
        <h2>Reserva de la sala XXX</h2>
        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <form action="{{ route('goIndexSala') }}" method="GET">
                    <button class="btn btn-primary  floatRight">volver</button>
                    <input type="hidden" name="nombre" value="<?php echo $nombre ?>">

                </form>
            </div>


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
                    <select class="custom-select" id="inlineFormCustomSelect">
                        @foreach($mails as $mail)
                            <option value="{{$mail}}">{{$mail}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus aria-hidden="true"></i>
                    </button>
                </div>

            <div class="col-md-12">
                @foreach($mails as $mail)
                    <div>
                      <p value="{{$mail}}">{{$mail}}</p>
                      <i class="fa fa-trash " aria-hidden="true"></i>
                    </div>
                @endforeach


            </div>

            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button class="btn-save btn btn-primary ">Save</button>
            </div>
        </div>
    </div>
@endsection
