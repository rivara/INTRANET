
@extends('layouts.desb2b')
@include('desb2b.bar')
@if($oAccion=="inicio")
<form action="{{ route('WebAdmLog') }}" method="get">
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
            <input type="hidden" name="id_usuario" value="{{$id_usuario}}">

</form>
@endif

@if($oAccion=="listado")
            <div class="center">
                <form action="{{ route("WebAdmLog")}}" method="get">
                    <button class="btn colorC" type="submit" ><i class="fa fa-arrow-left"></i></button>
                    <input type="hidden" name="oAccion" value="inicio">
                    <input type="hidden" name="id_usuario" value="[{{$id_usuario}}]">
                </form>
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

                <div class="row floatLeft ">
                    <div class="col-md-12">
                        <small>{{$logs->total()}} registros</small>
                    </div>
                    <div class="col-md-12 colorC">
                        {{$logs->appends(['fechaDesde' => $fechaDesde,'fechaHasta'=>$fechaHasta,'empresa'=>$empresa,'cdclien'=>$cdclien,'cdsucur'=>$cdsucur,'des'=>$des,'userMag'=>$userMag])->links()}}
                    </div>
                </div>
            </div>

@endif

