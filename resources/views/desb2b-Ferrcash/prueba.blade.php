
@extends('layouts.desb2b')
@section('content')

<h1>PRUEBAs</h1>
@if($oAccion=="inicio")
<?php //isset($llExpExcel)}} ?>
<form action="{{ route('prueba') }}" method="GET">
        <div id="dvContenido" class="separacion-vertical-arriba" align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="100" align="center" valign="middle">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="25%" height="25" align="left">Id Magento</td>
                                <td width="75%" align="left"><label for="oBus_IdMag"><input name="empresa"
                                                                                            type="text"
                                                                                            class="form-control"
                                                                                            style="width:80px" /></label>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" height="25" align="left">Cliente</td>
                                <td width="75%" align="left"><label for="oBus_Cli"><input name="cdclien"
                                                                                          type="text"
                                                                                          class="form-control"
                                                                                          style="width:60px" /></label>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" height="25" align="left">Sucursal</td>
                                <td width="75%" align="left"><label for="oBus_Suc"><input name="cdsucur"
                                                                                          type="text"
                                                                                          class="form-control"
                                                                                          style="width:40px" /></label>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" height="25" align="left">Empresa</td>
                                <td width="75%" align="left" style="padding-bottom:10px;">
                                        <select class="form-control" name="empresa" style="width:80px;">
                                            <option value="COM">COM</option>
                                            <option value="FER">FER</option>
                                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" height="25" align="left">Seccion</td>
                                <td width="75%" align="left"><label for="oBus_Sec"><input name="seccion"
                                                                                          type="text"
                                                                                          class="form-control"
                                                                                          style="width:200px" /></label>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" height="25" align="left">Fechas</td>

                                <td width="75%" align="left" style="padding-bottom:10px;">
                                        <input class="form-control floatLeft" type="date"  name="fechaDesde"  style="width:200px; margin-right:20px;" >
                                        <input class="form-control floatLeft" type="date" name="fechaHasta" style="width:200px" >
                                </td>
                            </tr>
                            <tr>

                                <td width="25%" height="25" align="left">Text log</td>
                                <td align="left"  width="75%"><label for="oBus_Txt"><input name="des"
                                                                              type="text"
                                                                              class="form-control"
                                                                              style="width:200px" /></label></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2" align="right">
                                    <span class="tip-formulario">*** No hace falta poner asteriscos al buscar por nombre&nbsp;</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>

                            <tr>
                                <td colspan="2" align="center" valign="middle">

                                    <input type="hidden"
                                           id="pagina-busqueda"
                                           name="pagina-busqueda"
                                           value="<?php //echo $lcNomPageBus; ?>">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
                <button class="btn colorC margintop10px"><b>enviar</b>
                </button>
                <input type="hidden" name="oAccion" value="listado">
                <input type="hidden" name="page" value="1">

</form>
@endif

@if($oAccion=="listado")
    <form action="{{route('backb2b')}}" method="GET">
        @csrf
        <button type="submit" name="submit" value="Edit" class="btn btn-light btnE ">
            <i class="fa fa-arrow-left fa-lg"></i>
        </button>
    </form>
            <div class="center">
                <table class="table table-striped table-bordered" border="1px solid black">
                    <thead>
                    <th>ID</th>
                    <th>EMPRESA</th>
                    <th>SOCIO</th>
                    <th>FECHA</th>
                    <th>SECCION</th>
                    <th>TEXTO</th>
                    </thead>

                    <?php $descontador=9 ?>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{$logs->currentpage()*10-$descontador}}</td>
                            <?php $descontador--;?>
                            <td>{{$log['EMPRESA']}}</td>
                            <td>{{$log['CDCLIEN']}}</td>
                            <td>{{$log['FOR_FEC']}}</td>
                            <td>{{$log['SECCION']}}</td>
                            <td>{{$log['DES']}}</td>
                        </tr>
                    @endforeach
                </table>

                <div class="row floatLeft">
                    <div class="col-md-12">
                        <small>{{$logs->total()}} registros</small>
                    </div>
                    <div class="col-md-12">
                        {{$logs->appends(['fechaDesde' => $fechaDesde,'fechaHasta'=>$fechaHasta,'empresa'=>$empresa,'cdclien'=>$cdclien,'cdsucur'=>$cdsucur,'des'=>$des,'userMag'=>$userMag])->links()}}
                    </div>
                </div>


            </div>

@endif

@endsection
