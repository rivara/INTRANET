@extends('layouts.desb2b')
@include('desb2b.bar')
@if($oAccion=="inicio")
<div class="container">
    <p class="breadcrumb-item sp">historico de pedidos</p>
    <div class="headerComafe">
        <h6>HISTÓRICO PEDIDOS</h6>
    </div>

    <form id="frmDatos" name="frmDatos" method="post" action="">
        <div class="my-account todo-el-ancho">
            <div class="box-title">
                <h2 class="box-title"><?php //echo strtoupper($lcTitPan); ?></h2>
            </div>
            <br/>


            <div align="center">
                <table width="100%">
                    <tr>
                        <td align="center" valign="middle" width="25%">tipo de pedido</td>
                        <td width="75%">
                            <select name="oDocTip" class="form-control width200px">
                                <option>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" align="center" valign="middle">numero pedido</td>
                        </td>
                        <td width="75%"><input name="oDocNum"
                                               class="form-control width200px"
                                               id="oDocNum"
                                               value="<?php // echo $oDocNum; ?>"
                                               height="25"
                                              /></td>
                    </tr>
                    <tr>
                        <td width="25%" align="center" valign="middle">socio</td>
                        <td width="75%"><input name="oBusSocio"
                                   class="form-control width200px"
                                   title=""
                                   id="oBusSocio"
                                   tabindex="2"
                                   value="<?php // echo $oBusSocio; ?>"


                            /></td>
                    </tr>
                    <tr>
                        <td width="25%" align="center" valign="middle">sucursal</td>
                        <td width="75%"><input name="oBusSuc"
                                   class="form-control width200px"
                                   title=""
                                   id="oBusSuc"
                                   tabindex="2"
                                   value="<?php // echo $oBusSuc; ?>"


                            /></td>
                    </tr>
                    <tr>
                        <td width="25%" align="center">Fechas</td>
                        <td width="75%" align="left">
                            <input class="form-control floatLeft width200px" type="date" name="fechaDesde">
                            <input class="form-control floatLeft width200px" type="date" name="fechaHasta" >
                        </td>
                    </tr>
                </table>


                <div class="wrapperComafe">
                    <button class="btn colorC margintop10px"><b>buscar</b></button>
                </div>
                <input type="hidden" name="oAccion" value="listado">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="id_usuario" value="{{$id_usuario}}">

    </form>
</div>



@endif()
    @if($oAccion!="inicio")

                <br />
                <div id="dvMensajes" style="text-align:center">
                    <span class="intranet-LetraError"><?php //echo  $lcMensajeProceso; ?></span>
                </div>

                <br />
                <div id="dvBotonesInf" style="text-align:center" class="botonera">
                    <button id="oBuscar"
                            type="button"
                            class="button"
                            tabindex="3"
                            onClick="fAccion ('', '', 'B')"><span><span>  Buscar  </span></span></button>
                </div>


            <br />
            <div id="dvProveedores" style="OVERFLOW: auto; HEIGHT: 250px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="7%"
                            align="center"
                            valign="top"
                            class="<?php// echo  $lcClaFil; ?>"><?php //echo  $lcEnlace; ?></td>
                        <td width="7%" align="center" valign="top" class="<?php // echo  $lcClaFil; ?>">
                            <span class="intranet-Text"><?php // echo  $lcProCod; ?></span></td>
                        <td width="51%" align="left" valign="top" class="<?php // echo  $lcClaFil; ?>"><span
                                    class="intranet-Text"><?php  //echo  $lcProDes; ?></span></td>
                        <td width="32%" align="left" valign="top" class="<?php //echo  $lcClaFil; ?>"><span
                                    class="intranet-Text"><?php //echo  $lcProWeb; ?></span></td>
                        <td width="5%" align="center" valign="top" class="<?php //echo  $lcClaFil; ?>">
                            <span class="intranet-Text"><?php //echo  $lcProFex; ?></span></td>
                        <td width="5%"
                            align="left"
                            valign="top"
                            class="<?php// echo  $lcClaFil; ?>"><?php //echo  $lcEnlace; ?></td>
                    </tr>
                </table>
            </div>


@endif()




