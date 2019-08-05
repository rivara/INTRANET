@extends('layouts.app')
@section('content')

    <form action="{{ route('goIndexSala') }}" method="GET">
        <button class="btn btn-primary  floatRight ">
            <i class="fa fa-arrow-left"></i>
        </button>
        <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
    </form>
    <div class="container wrapper mitad2 ">
        <form action="{{ route('recordSala') }}" method="GET">
            <div class="row">
                <div class="col-md-8">
                    <h2>Reserva de la sala {{$salaOpcion}}</h2>
                </div>
                <div class="col-md-12"><br></div>
                <div class="col-md-2">
                    Dia/mes/año
                </div>
                <div class="col-md-6">
                    <input class="form-control floatLeft" type="date" name="fecha" >
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-12"><br></div>


                <div class="col-md-2">
                    hora
                </div>
                <div class="col-md-3">
                    <input class="form-control " type="time" name="horaDesde" >
                </div>
                <div class="col-md-3">
                    <input class="form-control " type="time" name="fechaHasta" >
                </div>
                <div class="col-md-3">
                    <div class="wrapperColorPicker">
                        <select id="colorselector_1" name="color">
                            <option value="#A0522D" data-color="#A0522D"></option>
                            <option value="#87CEFA" data-color="#87CEFA"></option>
                            <option value="#FF4500" data-color="#FF4500"></option>
                            <option value="#008B8B" data-color="#008B8B"></option>
                            <option value="#DC143C" data-color="#DC143C"></option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12"><br></div>

                <div class="col-md-12">
                    Titulo
                </div>
                <div class="col-md-12">
                    <input class="form-control floatLeft" type="text" name="titulo" >
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
                    E-mails
                    <small>(sólo se grabaran los escritos correctos)</small>
                </div>
                <div class="col-md-12" class="form-control">
                    <!--<textarea name="mails" id="autocomplete" rows="3" ></textarea> -->
                    <textarea class="form-control" name="mails" rows="3" id="autocomplete"></textarea>
                </div>
                <div class="col-md-12"><br></div>

                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <button class="btn-save btn btn-primary">
                        <i class="fa fa-floppy-o"></i> graba
                    </button>
                </div>
                <input type="hidden" name="nombre" value={{$nombre}}>
                <input type="hidden" name="salaOpcion" value={{$salaOpcion}}>
            </div>
        </form>
    </div>
@endsection




