@extends('layouts.app')
@section('content')
    <div class="container wrapper mitad2 ">
        <form action="{{ route('goIndexSala') }}" method="GET">
            <button class="btn btn-primary   floatRight">
                <i class="fa fa-arrow-left"></i>
            </button>
            <input type="hidden" name="nombre" value="{{$nombre}}">
            <input type="hidden" name="salaOpcion" value="{{$salaOpcion}}">
        </form>


        <form action="{{ route('deleteSala') }}" method="GET">
            <button class="btn-save btn btn-danger floatRight">
                <i class="fa fa-trash"></i> Borra
            </button>
            <input type="hidden" name="nombre" value="{{$nombre}}">
            <input type="hidden" name="salaOpcion" value="{{$salaOpcion}}">
        </form>

        <form action="{{ route('updateSala') }}" method="GET">
            <div class="row">
                <div class="col-md-8">
                    <h2>Actualiza reserva de la sala {{$salaOpcion}}</h2>
                </div>
                <div class="col-md-12"><br></div>
                <div class="col-md-2">
                    Dia/mes/año
                </div>
                <div class="col-md-6">
                    <input class="form-control floatLeft" type="date" name="fecha" value="{{$fecha}}" readonly>
                </div>
                <div class="col-md-2"></div>

                <div class="col-md-12"><br></div>


                <div class="col-md-2">
                    hora
                </div>
                <div class="col-md-3">
                    <input class="form-control " type="time" name="horaDesde" value="{{$desde}}" readonly>
                </div>
                <div class="col-md-3">
                    <input class="form-control " type="time" name="fechaHasta" value="{{$hasta}}" readonly>
                </div>
                <div class="col-md-3">
                    <div class="wrapperColorPicker">
                        <select id="colorselector_2" name="color">
                            <option value={{$color}} data-color="{{$color}}" selected></option>
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
                    <input class="form-control floatLeft" type="text" name="titulo" value="{{$titulo}}">
                </div>
                <div class="col-md-12"><br></div>
                <div class="col-md-12">
                   Decripcion
                </div>
                <div class="col-md-12">
                    <div class="form-group purple-border">
                        <textarea class="form-control" name="descripcion" rows="3" >
                            {{$descripcion}}
                        </textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    E-mails
                    <small>(sólo se grabaran los escritos correctos)</small>
                </div>
                <div class="col-md-12" class="form-control">
                    <!--<textarea name="mails" id="autocomplete" rows="3" ></textarea> -->
                    <textarea class="form-control" name="mails" rows="3" id="autocomplete" >
                        {{$mails}}
                    </textarea>
                </div>
                <div class="col-md-12"><br></div>

                <div class="col-md-6"></div>

                <div class="col-md-3">

                </div>

                <div class="col-md-3">
                    <button class="btn-save btn btn-primary">
                        <i class="fa fa-floppy-o"></i> Actualiza
                    </button>
                </div>

                <input type="hidden" name="nombre" value={{$nombre}}>
                <input type="hidden" name="salaOpcion" value={{$salaOpcion}}>
            </div>
        </form>
    </div>
@endsection




