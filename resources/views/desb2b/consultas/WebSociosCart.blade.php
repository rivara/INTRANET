@extends('layouts.desb2b')
@include('desb2b.bar')


<div class="container">
    <p class="breadcrumb-item sp">consulta</p>
    <div class="headerComafe">
        <h6>CONSULTA DE ACTUALIZACIONES DE ARTICULOS</h6>
    </div>
    <form action="" method="get">

        <div align="center">


            <div id="dvContenido" style="text-align:center">
                <table width="90%" class="tabla-con-borde">
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td width="25%"><span class="intranet-Text">AÑO</span></td>
                        <td width="25%" align="left"><input name="oAlbDes"
                                                            type="text"
                                                            title=""
                                                            tabindex="1"
                                                            size="5"
                                                            maxlength="4"
                                                            value="<?php //echo $oAlbDes; ?>" <?php //echo $oAtt; ?>
                                                            style="HEIGHT: 20px; WIDTH: 40px"/></td>
                        <td width="50%" align="left"></td>
                    </tr>
                    <tr>
                        <td><span class="intranet-Text">SEMANA</span></td>
                        <td align="left"><input name="oAlbHas"
                                                id="semana"
                                                type="text"
                                                title=""
                                                tabindex="1"
                                                size="4"
                                                maxlength="2"
                                                value="<?php //echo $oAlbHas; ?>"
                                                style="HEIGHT: 20px; WIDTH: 30px"/></td>
                        <td align="left"></td>
                    </tr>
                    <tr>
                        <td><span class="intranet-Text">TIPO DE ACT.</span></td>
                        <td colspan="2" align="left"><select name="oTipAlb"
                                                             size="1"
                                                             class="text_edi_tv"
                                                             tabindex="5"
                                                             style="WIDTH: 160px"
                                                             title="">
                                <option value="">[TODAS]</option>
                                <option value="1">ALTA</option>
                                <option value="2">MODIFICACION</option>
                                <option value="3">BAJA</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><span class="intranet-Text">ARTICULO</span></td>
                        <td colspan="2" align="left"><input name="oArtCod"
                                                            id="codigo-articulo"
                                                            type="text"
                                                            title=""
                                                            tabindex="6"
                                                            size="10"
                                                            maxlength="10"
                                                            value="<?php // echo $oArtCod; ?>"
                                                            onChange="fAccion ('_self', '<?php // echo $lcNomPageRec; ?>', 'B');"
                                                            style="HEIGHT: 20px; WIDTH: 100px"/>&nbsp;
                            <input name="oArtHas"
                                   type="text"
                                   title=""
                                   class="text_desc"
                                   tabindex="0"
                                   size="60"
                                   maxlength="80"
                                   readonly="readonly"
                                   value="<?php // echo $lcArtDes; ?>"
                                   style="HEIGHT: 20px; WIDTH: 200px"/></td>
                    </tr>
                    <tr>
                        <td><span class="intranet-Text">DESCRIPCION</span></td>
                        <td colspan="2" align="left"><input name="oArtDes"
                                                            type="text"
                                                            title=""
                                                            tabindex="7"
                                                            size="20"
                                                            maxlength="20"
                                                            value="<?php // echo $oArtDes; ?>"
                                                            style="HEIGHT: 20px; WIDTH: 120px"/>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="intranet-Text">PROVEEDOR</span></td>
                        <td colspan="2" align="left"><input name="oProCod"
                                                            id="codigo-proveedor"
                                                            type="text"
                                                            title=""
                                                            tabindex="8"
                                                            size="6"
                                                            maxlength="10"
                                                            value="<?php // echo $oProCod; ?>"
                                                            onChange="fAccion ('_self', '<?php // echo $lcNomPageRec; ?>', 'B');"
                                                            style="HEIGHT: 20px; WIDTH: 40px"/>&nbsp;
                            <input name="oProDes"
                                   type="text"
                                   title=""
                                   class="text_desc"
                                   tabindex="0"
                                   size="40"
                                   maxlength="80"
                                   readonly="readonly"
                                   value="<?php // echo $lcProDes; ?>"
                                   style="HEIGHT: 20px; WIDTH: 200px"/></td>
                    </tr>
                </table>
            </div>

        </div>
        <!-- segunda parte -->


        <?php// if ($lcMensajeProceso <> '') { ?>
        <br/>
        <div id="dvMensajes" style="height:40px">
            <span><?php // echo $lcMensajeProceso; ?></span>
        </div>
        <?php //} ?>


        <?php //if ($llDivBotones_Inf) { ?>
        <br/>
        <div id="dvBotonesInf" style="text-align:center" class="botonera">
            <button type="button"
                    id="oBuscar"
                    name="oBuscar"
                    class="button"
                    title="Buscar"><span><span>  Buscar </span></span></button>
        </div>
        <?php //} ?>


        <?php //if ($llDivPie) { ?>
        <br/>
        <div id="dvPie" style="height:40px">
        </div>
    <?php //} ?>

</div>
</form>


<body>
<form id="frmDatos" name="frmDatos" method="post" action="">
    <input type="hidden" id="pUrl" name="pUrl" value="">
    <input type="hidden" id="oAccion" name="oAccion" value="">
    <input type="hidden" id="oParam1" name="oParam1" value="">
    <input type="hidden" id="oParam2" name="oParam2" value="">
    <input type="hidden" id="oParam3" name="oParam3" value="">
    <input type="hidden" id="oParam4" name="oParam4" value="">
    <input type="hidden" id="oParam5" name="oParam5" value="">
    <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php //echo $pIdMagento; ?>">
    <!-- CAMPOS DEL FORMULARIO -->
    <input type="hidden" id="oAlbDes" name="oAlbDes" value="<?php //echo $oAlbDes; ?>">
    <input type="hidden" id="oAlbHas" name="oAlbHas" value="<?php //echo $oAlbHas; ?>">
    <input type="hidden" id="oArtCod" name="oArtCod" value="<?php //echo $oArtCod; ?>">
    <input type="hidden" id="oArtDes" name="oArtDes" value="<?php //echo $oArtDes; ?>">
    <input type="hidden" id="oProCod" name="oProCod" value="<?php //echo $oProCod; ?>">
    <!-- FIN CAMPOS DEL FORMULARIO -->
    <script type="text/javascript" src="<?php //echo $lcDirBase; ?>Conf/wz_tooltip.js"></script>

    <div class="my-account todo-el-ancho">
        <div class="box-title"
             style="display: flex; flex-flow: row wrap;">
            <div style="display: flex: 1 auto;"><h2 class="box-title"><?php //echo mb_strtoupper($lcTitPan); ?></h2>
            </div>

            <div id="dvBotones" style="flex: 1 auto;">
                <div style="height: 100%; display: flex; flex-flow: row wrap; justify-content: flex-end;">
                    <div id="dvBotones" style="flex: 1 auto;">
                        <div style="height: 100%; display: flex; flex-flow: row wrap; justify-content: flex-end;">
                            <i class="fa fa-file-excel-o fa-icono-header"
                               title="Exportar a fichero Excel"
                               onClick="fAccion('frmDescarga','<?php //echo $lcNomPageDwn; ?>','EX');"></i>
                            <i class="fa fa-print fa-icono-header"
                               title="Imprimir pantalla"
                               onClick="window.print();"></i>
                            <i class="fa fa-arrow-left fa-icono-header"
                               title="Volver a la página anterior"
                               onClick="fAccion('','<?php //echo $lcNomPageVol; ?>','');"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php //if ($llDivExplicacion) { ?>
        <br>
        <div id="dvExplicacion" style="height:40px">
        </div>
    <?php //} ?>

    <!-- PAGINADO -->

        <div id="dvContenido">
            <div class="table-flex" style="border: 1px solid">
                <div class="tr-flex th-flex">
                    <div class="td-flex">AÑO/SEM</div>
                    <div class="td-flex">TIPO</div>
                    <div class="td-flex">ARTIC.</div>
                    <div class="td-flex texto-con-saltos" style="flex: 5 1;">DESCRIPCIÓN</div>
                    <div class="td-flex">PRECIO</div>

                    <div class="td-flex">PRECIO ANT</div>

                    <div class="td-flex">UBIC.</div>
                    <div class="td-flex">UD.COM</div>
                    <div class="td-flex">PRESEN</div>
                    <div class="td-flex">UD.EMBA</div>
                    <div class="td-flex">% REC</div>
                </div>
            <?php
            $lnCon = 0;
            $lnTotAcu = 0;
            /*
                            foreach ($rows as $row) {
                            $lnCon++;

                            $lcArt = $row['CDARTI'];
                            $lcMod = $row['ANYO'] . '/' . str_pad($row['SEMANA'], 2, '0', STR_PAD_LEFT);
                            $lcTip = $row['TIPO'];
                            $lcTip = substr($lcTip, 0, 2);
                            $lcTip = ['', 'AL', 'MD', 'BJ'][$lcTip];
                            $lcArt = $row['CDARTI'];
                            $lcDes = $row['DESCRIP'];
                            $lnPvp = $row[$lcPrecio];
                            $lcPvp = number_format($lnPvp, 4, ',', '.');
                            $lcUbi = $row['UBICACION'];
                            $lcPvr = $row['PVPR'];
                            $lcPre = $row['CDUNIDAD'];
                            $lcPro = $row['CDPROVE'];
                            $lcRaz = $row['NOM_PROVE'];
                            $lcUco = $row['UNICOM'];
                            $lcUem = $row['UNIEMB'];

                            $lnPvpAnt = $row[$lcPrecioAnt];
                            $lcPvpAnt = number_format($lnPvpAnt, 4, ',', '.');

                            $lColorFuenteTexto = '';
                            if ($lcTip == 'BJ') {
                                $lColorFuenteTexto = 'intranet-LetraError';
                            }

                            //+SI ES MARCA PROPIA
                            if (($row['IND_MAR_PROPIA'] == 1) or ($lcPro == '5834')) {
                                $lcPro = '1000';
                                $lcRaz = 'IMPORTACIONES COMAFE';
                            }

                            $lcUem = number_format($lcUem, 2, ',', '.');
                            $lcUco = number_format($lcUco, 2, ',', '.');
                            $lcPvr = number_format($lcPvr, 2, ',', '.');
                            ?>
                            <div class="tr-flex">
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: center;"><?php echo $lcMod; ?></div>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: center;"><?php echo $lcTip; ?></div>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: space-around;"><?php echo $lcArt; ?></div>
                                <div class="td-flex texto-con-saltos <?php echo $lColorFuenteTexto; ?>"
                                     style="flex: 5 1;"><?php echo $lcDes; ?></div>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: space-around;"><?php echo $lcPvp; ?></div>
                                <?php if ($llPcoAnt): ?>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: space-around;"><?php echo $lcPvpAnt; ?></div>
                                <?php endif; ?>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: center;"><?php echo $lcUbi; ?></div>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: space-around;"><?php echo $lcUem; ?></div>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: center;"><?php echo $lcPre; ?></div>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: space-around;"><?php echo $lcUco; ?></div>
                                <div class="td-flex <?php echo $lColorFuenteTexto; ?>"
                                     style="justify-content: space-around;"><?php echo $lcPvr; ?></div>
                            </div>
                            <?php
                            }*/
            ?>
            <!-- paginado -->
            </div>
        </div>


    </div>
</form>

</body>
</html>
