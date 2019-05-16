<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase   = '../';                                       // COMO IR AL DIRECTORIO BASE
$llDepurar   = false;                                       // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan    = 'Busqueda de articulos en baja / Alternativos';  // TITULO DE LA PAGINA
$llExpExcel  = false;                                       // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc    = 'ARTICULOS_BAJA.xls';                        // NOMBRE DEL FICHERO EXCEL A GENERAR
$lcNomPagina = mb_strtolower($_SERVER['PHP_SELF']);         // NOMBRE DE LA PAGINA PARA ESTABLECER PERMISOS
$lnPos       = mb_strrpos($lcNomPagina, '/');
if ($lnPos <> 0) {
    $lcNomPagina = mb_substr($lcNomPagina, $lnPos + 1);
}
//-PARAMETROS GENERAL PAGINA

//+Variables
//Globales o otros ficheros
global $DBTableNames;
global $DBName;
global $Rutas;
global $EmailsCliente;
global $UsersWebAdmin;
global $UserID;
global $UserSuc;
global $UserEmail_Gen;
global $pIdMagento;
global $rowConfEmp;
global $laDatosCliente;
global $laDatosClienteConf;
global $glServidorDevelop;
//Variables de la pagina
$arrRegs              = [];
$arrRegAlt            = [];
$llPintarAlternativos = false;
$lcDesBusAlter        = '';
//-Variables

//+REQUIRES INICIAL
include_once $lcDirBase . 'jed_paratube.php';
//-REQUIRES INICIAL

//+REQUIRES_OPCIONALES
//-REQUIRES_OPCIONALES

//+CONEXION BD
//-CONEXION BD

//+CAPTURO EL CODIGO DE USUARIO
$pIdMagento     = isset($pIdMagento) ? $pIdMagento : -1;
$lnIdUsuMagento = $pIdMagento;
//-CAPTURO EL CODIGO DE USUARIO

//+VALIDACION ACCESOS
//(1=ok		0=Sin validacion	-1=Usuario no permitido		-2=actualizando datos		-3=opcion no configurada)
$lnStatus                 = 0;                      // 1 o 0 o -1 o -2 o -3
$llValidarUsuario         = true;
$llSoloUserDepurador      = false;
$llValidar_TiendaFerrokey = false;

include_once $lcDirBase . 'jed_valuso.php';

$llDepurar = $laDatosClienteConf['ADM_INFOR_DBG'];        // VERIFICAR SI EL USUARIO ES DEPURADOR
//-VALIDACION ACCESOS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////                           PAGINA VARIABLE                                  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//+PAGINAS RESPUESTAS
$lcNomPageBus  = $Rutas['Base_Intranet_Ww'] . 'Socios/art_Consulta_Baja.php';
$lcNomPagePru  = $Rutas['Base_Intranet_Ww'] . 'Socios/Movs/pruebas-com.php';
$lcUrlBusqueda = $lcNomPageBus;
$lcNomPageAcc  = $lcNomPageBus;
//-PAGINAS RESPUESTAS

//+CAPTURAR VARIABLES FORMULARIO
$oAccion  = Helper::post('oAccion');
$pSession = Helper::post('pSession');
$oBajCba  = Helper::post('oBajCba');
$oBajCod  = Helper::post('oBajCod');
$oBajDes  = Helper::post('oBajDes');
$oBajPro  = Helper::post('oBajPro');
$oBajRef  = Helper::post('oBajRef');
$oBajMar  = Helper::post('oBajMar');
//-FIN CAPTURAR VARIABLES FORMULARIO

//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Grupo      = 'ARTICULO';
$lcLog_Texto      = '';
//QUE TIPO DE PRECIO PINTO
$lCampoPrecio = $laDatosCliente['TIPO_PRECIO'];
if ($lCampoPrecio == '') {
    $lCampoPrecio = 0;
}

$mensaje = <<<MSG
Código: {$oBajCod} / Cba: {$oBajCba} / Descripción: {$oBajDes} / Proveedor: {$oBajPro} / Referencia: {$oBajRef}
MSG;
Iniutils::escribeEnLog($mensaje);

$llPintaFiltroBusqueda     = true;
$llPintaRestultadoBusqueda = false;
if (($oAccion == 'B') or ($oAccion == 'EX')) {
    if (($oBajCod == '') and ($oBajDes == '') and ($oBajPro == '') and ($oBajRef == '') and ($oBajCba == '')) {
        $oAccion          = '';
        $lcMensajeProceso = 'INTRODUCE ALGUN PARAMETRO DE BUSQUEDA';
    } else {
        $llPintaFiltroBusqueda     = false;
        $llPintaRestultadoBusqueda = true;
        $lcLog_Texto               = 'BAJAS: ' . $oBajCod . '/' . $oBajDes . '/' . $oBajPro . '/' . $oBajRef;
        $lArt                      = $oBajCod;
        //Buscar codigo de barras
        if ($oBajCba <> '') {
            $lWhere    = "";
            $lWherePvp = " cdbarra = '" . $oBajCba . "'";
            $lOrdenPvp = '';
            $lLimitPvp = '';
            $oCB       = new WebArticulosPres();
            $arrCbas   = $oCB->obtenerRegistros(Conexion::getInstancia(), $lWherePvp, $lOrdenPvp, $lLimitPvp);
            foreach ($arrCbas as $rowCB) {
                $lArt = $rowCB ['ARTICULO'];
            }
            $mensaje = <<<MSG
Articulo encontrado por CBARRAS: {$lArt}
MSG;
            Iniutils::escribeEnLog($mensaje);
        }
        //Where Sql
        $lWhere = "";
        if ($lArt <> '') {
            if ($lWhere <> '') {
                $lWhere .= " AND ";
            }
            $lWhere .= " codigo = '" . $lArt . "'";
        } else {
            if ($oBajDes <> '') {
                if ($lWhere <> '') {
                    $lWhere .= " AND ";
                }
                $lWhere .= " nombre LIKE '" . str_replace('*', '%', $oBajDes) . "'";
            }
            if ($oBajPro <> '') {
                if ($lWhere <> '') {
                    $lWhere .= " AND ";
                }
                $lWhere .= " cdprove = '" . $oBajPro . "'";
            }
            if ($oBajMar <> '') {
                if ($lWhere <> '') {
                    $lWhere .= " AND ";
                }
                $lWhere .= " marca LIKE '" . str_replace('*', '%', $oBajMar) . "'";
            }
            if ($oBajRef <> '') {
                if ($lWhere <> '') {
                    $lWhere .= " AND ";
                }
                $lWhere .= " referen LIKE '" . str_replace('*', '%', $oBajRef) . "'";
            }
        }
        //if (($oAccion == 'P-FI') or ($oAccion == 'A-CA') ){ $lWhere = " codigo = '".$oParam1."'";  }
        $oArticulo = new WebArticulos();
        $arrRegs   = $oArticulo->obtenerRegistros(Conexion::getInstancia(), $lWhere);
        $lnReg_Tot = sizeof($arrRegs);

        if ($lnReg_Tot) {
            //Si el codigo de articulo esta relleno
            $llPintarAlternativos = false;
            if ($lArt <> '') {
                $llPintarAlternativos = true;
            }
            //Busqueda de altenait
            $lcDesBusAlter = 'POR DESCRIPCION';
            //Preparar select alternativos
            $lWhere  = "";
            $lDesArt = $arrRegs[0]['NOMBRE'];
            $intPos  = strpos($lDesArt, ' ');
            $intPos2 = strpos($lDesArt, ' ', $intPos + 1);
            $lDes    = substr($lDesArt, 0, $intPos2);
            if ($lWhere <> '') {
                $lWhere .= " AND ";
            }
            $lWhere    .= " nombre LIKE '" . str_replace('*', '%', $lDes) . "%' AND ind_act = 1";
            $lOrden    = '';
            $lLimit    = '';
            $oArtiAlt  = new WebArticulos();
            $arrRegAlt = $oArtiAlt->obtenerRegistros(Conexion::getInstancia(), $lWhere, $lOrden, $lLimit);
            $lnReg_Tot = sizeof($arrRegAlt);
        }
    }
}
//-

//+LOG
AccesoDatos::grabarLog(Conexion::getInstancia(), $pgIdentificadorEmpresa, $UserID, $UserSuc, $lcLog_Grupo, $lcLog_Texto, $lnIdUsuMagento);
//-LOG

//+CONFIGURACION CONTENIDO
$lcAyuTex           = '<B>Pantalla para consultar articulos de baja.</B><BR>';
$lcAyuTex          .= ' - Si buscas por articulo o por codigo de barras. Presentara una lista de prodcutos alternativos, al articulo encontrado.<BR>';
$lcAyuTex          .= ' - Saca los articulos sustitutivos o similares de un articulo de baja.<BR>';
$lcAyuTex          .= ' - En los campos descriptivos (descripcion,referencia proveedor) hay que poner asteriscos.<BR>';
$lcAyudaPantalla_P  = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco       = "document.getElementById('oBusCod').focus()";
$lcObjetoFoco       = "";
$llDivTitulo        = true;
$llDivExplicacion   = false;
$llDivBotones_Sup   = true;
$llDivBotones_Inf   = true;
$llDivPie           = false;
$llDivHiddenDown    = true;
//-CONFIGURACION CONTENIDO

//Los botones inferiores solo al filtrar
$llDivBotones_Inf = $llPintaFiltroBusqueda;

//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();

if ($llExpExcel) {

    //Sobre el fichero
    $lcNombreFichero = '';
    $lcNombrePestana = 'CONSULTA_ARTS_BAJA';
    //Cabeceras informe
    $cabeceras = [
        'ARTICULO',
        'DESCRIPCION',
        'REFERENCIA.',
        'PROVEEDOR',
        'NOMBRE PROVEEDOR',
        'SUSTITUIDO POR',
        'FECHA BAJA',
    ];
    $cabeceras_2 = [
        'CODIGO',
        'DESCRIPCION',
        'MARCA.',
        'PRECIO',
        'PRESENTACION VENTA',
        '¿ES CATALOGO FERROKEY?',
    ];
    //Datos


    //Exportar a Excel
    $phpExcel = new PHPExcel();
    $phpExcel->getActiveSheet()->setTitle($lcNombrePestana);
    $hojaActiva = $phpExcel->getActiveSheet();
    PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

    // Título en la primera fila.
    $hojaActiva->getStyle('A1')->getFont()->setSize(20);
    $hojaActiva->getStyle('A1')->getFont()->setBold(true);
    $hojaActiva->mergeCells('A1:F1');

    $hojaActiva->getStyle('4:4')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB("FF{$colorCorporativo}");

    $fila    = 1;
    $columna = 'A';
    $hojaActiva->setCellValue($columna . $fila, strtoupper($lcTitPan));

    // Escribimos las cabeceras.
    $fila = 4;
    $hojaActiva->getStyle('4:4')->getFont()->setBold(true);
    foreach ($cabeceras as $cabecera) {
        $hojaActiva->getColumnDimension($columna)->setAutoSize(true);
        $hojaActiva->setCellValue($columna . $fila, $cabecera);
        $columna++;
    }

    // Escribimos los datos.
    $fila++; // Los datos comienzan en la fila 5.
    $lnCon    = 0;
    $lnTotAcu = 0;

    foreach ($arrRegs as $aDatos) {

        $lnCon++;
        $columna = 'A';

        $lArt_Cod = $aDatos ['CODIGO'];
        $lArt_Des = $aDatos ['NOMBRE'];
        $cArtRef  = $aDatos ['REFEREN'];
        $cArtPro  = $aDatos ['CDPROVE'];
        $cArtRaz  = '';
        $cArtSus  = $aDatos ['INTERCAMBIO'];
        $cFecBaj  = $aDatos ['FEC_BAJA'];

        $hojaActiva->setCellValue($columna++ . $fila, $lArt_Cod);
        $hojaActiva->setCellValue($columna++ . $fila, $lArt_Des);
        $hojaActiva->setCellValue($columna++ . $fila, $cArtRef);
        $hojaActiva->setCellValue($columna++ . $fila, $cArtPro);
        $hojaActiva->setCellValue($columna++ . $fila, $cArtRaz);
        $hojaActiva->setCellValue($columna++ . $fila, $cArtSus);
        $hojaActiva->setCellValue($columna++ . $fila, $cFecBaj);

        //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lArt_Est, PHPExcel_Cell_DataType::TYPE_STRING);
        //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lcRefProv, PHPExcel_Cell_DataType::TYPE_STRING);

        $fila++;
    }

    if ($llPintarAlternativos) {

        // Escribimos las cabeceras (2)
        $columna = 'A';
        $fila = $fila + 2;

        $hojaActiva->getStyle($fila . ':' . $fila)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB("FF{$colorCorporativo}");
        $hojaActiva->getStyle($fila . ':' . $fila)->getFont()->setBold(true);

        foreach ($cabeceras_2 as $cabecera) {
            $hojaActiva->getColumnDimension($columna)->setAutoSize(true);
            $hojaActiva->setCellValue($columna . $fila, $cabecera);
            $columna++;
        }

        // Escribimos los datos.
        $fila++;        // Los datos comienzan en la fila X.
        $lnCon = 0;
        $lnTotAcu = 0;

        foreach ($arrRegAlt as $rowArtAlt) {

            $lnCon++;
            $columna = 'A';

            $cArtCod = $rowArtAlt ['CODIGO'];
            $cArtDes = $rowArtAlt ['NOMBRE'];
            $cArtPro = $rowArtAlt ['CDPROVE'];
            $cArtMar = $rowArtAlt ['MARCA'];
            $cArtRef = $rowArtAlt ['REFEREN'];
            $cArtInd = $rowArtAlt ['IND_ACT'];
            $cArtSus = $rowArtAlt ['INTERCAMBIO'];
            $cArtPre = $rowArtAlt ['PRES_VENTA'];
            $cArtFky = $rowArtAlt ['IND_FRKY'];

            $lPrecio = 0;


            $lArtFky = '';
            if ($cArtFky == 1) {
                $lArtFky = 'Articulo Ferrokey';
            }

            $hojaActiva->setCellValue($columna++ . $fila, $cArtCod);
            $hojaActiva->setCellValue($columna++ . $fila, $cArtDes);
            $hojaActiva->setCellValue($columna++ . $fila, $cArtMar);
            $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
            $hojaActiva->setCellValue($columna++ . $fila, $lPrecio);
            $hojaActiva->setCellValue($columna++ . $fila, $cArtPre);
            $hojaActiva->setCellValue($columna++ . $fila, $lArtFky);

            //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lArt_Est, PHPExcel_Cell_DataType::TYPE_STRING);
            //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lcRefProv, PHPExcel_Cell_DataType::TYPE_STRING);
            $fila++;

        }
    }

    $writer = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    $writer->save('php://output');

} else {
?>

<body onLoad="<?php echo $lcObjetoFoco; ?>">

<form id="frmDatos" name="frmDatos" method="post" action="">
    <INPUT type="hidden" id="pSession" name="pSession" value="<?php echo $pSession; ?>">
    <INPUT type="hidden" id="pUrl" name="pUrl" value="">
    <INPUT type="hidden" id="oAccion" name="oAccion" value="">
    <INPUT type="hidden" id="oDetalle" name="oDetalle" value="">
    <INPUT type="hidden" id="oParam1" name="oParam1" value="">
    <INPUT type="hidden" id="oParam2" name="oParam2" value="">
    <INPUT type="hidden" id="oParam3" name="oParam3" value="">
    <INPUT type="hidden" id="oParam4" name="oParam4" value="">
    <INPUT type="hidden" id="oParam5" name="oParam5" value="">
    <!-- CAMPOS DEL FORMULARIO -->
    <INPUT type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>" />
    <!-- FIN CAMPOS DEL FORMULARIO -->
    <script type="text/javascript" src="<?php echo $lcDirBase; ?>Conf/wz_tooltip.js"></script>

    <div class="my-account todo-el-ancho">

        <?php if ($llDivTitulo) { ?>
        <div class="box-title"
             style="display: flex; flex-flow: row wrap;">
            <div style="display: flex: 1 auto;"><h2 class="box-title"><?php echo mb_strtoupper($lcTitPan); ?></h2></div>
            <?php if ($llDivBotones_Sup) { ?>
            <div id="dvBotones" style="flex: 1 auto;">
                <div style="height: 100%; display: flex; flex-flow: row wrap; justify-content: flex-end;">
                    <?php if ($llPintaRestultadoBusqueda) { ?>
                    <i class="fa fa-file-excel-o fa-icono-header"
                       title="Exportar a excel"
                       onclick="fAccion('frmDescarga','<?php echo $lcNomPageBus; ?>','EX')"></i>
                    <?php } ?>
                    <i class="fa fa-question fa-icono-header" style="cursor: none;"
                       title=""
                       onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
                    <?php if ($llPintaRestultadoBusqueda) { ?>
                    <i class="fa fa-arrow-left fa-icono-header"
                       title="Volver para realizar una nueva busqueda"
                       onclick="fAccion('','<?php echo $lcNomPageBus; ?>', '')"></i>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <?php if ($llDivExplicacion) { ?>
        <br />
        <div id="dvExplicacion" style="height:40px">
        </div>
        <?php } ?>

        <?php if ($llPintaFiltroBusqueda) { ?>
        <br />
        <!--<div id="dvContenido" style="OVERFLOW: auto; WIDTH: 1000px; HEIGHT: 500px">-->
        <div id="dvContenido">
            <table width="100%" cellpadding="0" cellspacing="2" class="tabla-con-borde">
                <tr><td height="10"></td></tr>
                <tr>
                    <td width="30%" align="center" valign="middle"><span class="intranet-Text">CODIGO DE BARRAS</span></td>
                    <td width="70%"><input name="oBajCba"
                                           type="text"
                                           title=""
                                           id="oBajCba"
                                           tabindex="2"
                                           value="<?php echo $oBajCba; ?>"
                                           maxlength="15"
                                           onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcUrlBusqueda; ?>','B')"
                                           style="width:160px" /></td>
                </tr>
                <tr>
                    <td width="30%" align="center" valign="middle"><span class="intranet-Text">CODIGO DE ARTICULO</span>
                    </td>
                    <td width="70%"><input name="oBajCod"
                                           type="text"
                                           title=""
                                           id="oBajCod"
                                           tabindex="2"
                                           value="<?php echo $oBajCod; ?>"
                                           maxlength="10"
                                           onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcUrlBusqueda; ?>','B')"
                                           style="width:80px" /></td>
                </tr>
                <tr>
                    <td align="center" valign="middle"><span class="intranet-Text">DESCRIPCION</span></td>
                    <td><input name="oBajDes"
                               type="text"
                               title=""
                               id="oBajDes"
                               tabindex="3"
                               value="<?php echo $oBajDes; ?>"
                               size="40"
                               maxlength="80"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcUrlBusqueda; ?>','B')"
                               style="width:200px" />&nbsp;&nbsp;&nbsp;<span style="font-size:11px; color:black">Hace falta poner asteriscos</span>
                    </td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">CODIGO PROVEEDOR</span></td>
                    <td><input name="oBajPro"
                               type="text"
                               title=""
                               id="oBajPro"
                               tabindex="6"
                               value="<?php echo $oBajPro; ?>"
                               size="6"
                               maxlength="6"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcUrlBusqueda; ?>','B')"
                               style="width:60px" /></td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">REFERENCIA PROVEEDOR</span></td>
                    <td><input name="oBajRef"
                               type="text"
                               title=""
                               id="oBajRef"
                               tabindex="6"
                               value="<?php echo $oBajRef; ?>"
                               size="15"
                               maxlength="20"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcUrlBusqueda; ?>','B')"
                               style="width:120px" />&nbsp;&nbsp;&nbsp;<span style="font-size:11px; color:black">Hace falta poner asteriscos</span>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="middle" colspan="2" height="40">
                        <div style="background-color: #8C8D8F; color: white; font-weight: bold; padding: 2px 0">
                                    OPCIONES PARA LOS LISTADOS
                                </div>
                    </td>
                <tr>
                <tr>
                    <td align="center"><span class="intranet-Text">BUSCAR SUSTITUTIVOS POR:</span></td>
                    <td><select id="oBusAlias" title="">
                            <option value="DES">DESCRIPCION</option>
                        </select>
                    </td>
                </tr>
                <tr>


                    <td align="center"><span class="intranet-Text">ORDENAR ALTERNATIVOS POR</span></td>
                    <td><select id="oOrden" title="">
                            <option value="DES">DESCRIPCION</option>
                        </select></td>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>


        <?php if ($llPintaRestultadoBusqueda) { ?>
        <br />
        <input type="hidden" id="oBajCba" name="oBajCba" value="<?php echo $oBajCba; ?>" />
        <input type="hidden" id="oBajCod" name="oBajCod" value="<?php echo $oBajCod; ?>" />
        <input type="hidden" id="oBajDes" name="oBajDes" value="<?php echo $oBajDes; ?>" />
        <input type="hidden" id="oBajPro" name="oBajPro" value="<?php echo $oBajPro; ?>" />
        <input type="hidden" id="oBajRef" name="oBajRef" value="<?php echo $oBajRef; ?>" />

        <div style="OVERFLOW: auto; WIDTH: 100%; HEIGHT: 630px">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="80" align="" class="intranet-FilaTitulo">&nbsp;<span>ARTICULO</span></td>
                    <td width="300" align="" class="intranet-FilaTitulo"><span>DESCRIPCION</span></td>
                    <td width="150" align="" class="intranet-FilaTitulo"><span>REFERENCIA</span></td>
                    <td width="60" align="" class="intranet-FilaTitulo"><span>PROV.</span></td>
                    <td width="230" align="" class="intranet-FilaTitulo"><span>NOMBRE</span></td>
                    <td width="90" align="center" class="intranet-FilaTitulo"><span>SUSTITUIDO POR</span></td>
                    <td width="100" align="center" class="intranet-FilaTitulo"><span>FEC.BAJA</span></td>
                </tr>
                <?php

                $lnConBaj = 0;
                foreach ($arrRegs as $rowArt) {

                $lnConBaj = $lnConBaj + 1;

                $cArtCod = $rowArt ['CODIGO'];
                $cArtDes = $rowArt ['NOMBRE'];
                $cArtPro = $rowArt ['CDPROVE'];
                $cArtRef = $rowArt ['REFEREN'];
                //$cArtFba = $rowArt ['IND_ACT'];
                $cArtInd = $rowArt ['IND_ACT'];
                $cArtSus = $rowArt ['INTERCAMBIO'];
                $cFecBaj = $rowArt ['FEC_BAJA'];
                if ($cFecBaj <> '') {
                    $cFecBaj = substr($cFecBaj, 8, 2) . '/' . substr($cFecBaj, 5, 2) . '/' . substr($cFecBaj, 0, 4);
                }

                $lWhere  = ' CDPROVE = ' . $cArtPro;
                $lOrden  = '';
                $lLimit  = '';
                $oProv   = new WebProveedor();
                $arrProv = $oProv->obtenerRegistros(Conexion::getInstancia(), $lWhere, $lOrden, $lLimit);
                $cArtRaz = '';
                foreach ($arrProv as $rowPro) {
                    $cArtRaz = $rowPro ['RAZON'];
                }

                if ($cArtSus == 0) {
                    $lcArtSus = '';
                } else {
                    $lcArtSus = $cArtSus;
                }

                $lcFilaColor = '';
                //if (($lnConBaj % 2) == 0) { $lcFilaColor = "intranet-FilaMarca_Amarilla"; }

                $lcColorTexto = 'intranet-Text';
                if ($cArtInd == 0) {
                    $lcColorTexto = "intranet-LetraError";
                }

                ?>
                <tr>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcColorTexto; ?>"><?php echo $cArtCod; ?></span></td>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcColorTexto; ?>"><?php echo $cArtDes; ?></span></td>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcColorTexto; ?>"><?php echo $cArtRef; ?></span></td>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcColorTexto; ?>"><?php echo $cArtPro; ?></span></td>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcColorTexto; ?>"><?php echo $cArtRaz; ?></span></td>
                    <td align="center" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcColorTexto; ?>"><?php echo $lcArtSus; ?></span></td>
                    <td align="center" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcColorTexto; ?>"><?php echo $cFecBaj; ?></span></td>
                </tr>

                <?php
                }
                ?>

                <?php if (!$llPintarAlternativos) { ?>
                <tr>
                    <td align="left" class="intranet-FilaTotal" valign="top" colspan="7">
                        &nbsp;<span><?php echo $lnConBaj; ?> articulos</span></td></tr><?php } ?>

            </table>

            <?php if ($llPintarAlternativos) { ?>

            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="intranet-FilaTitulo">&nbsp;PRODUCTOS ALTERNATIVOS ( <?php echo $lcDesBusAlter; ?> )</td>
                </tr>
            </table>
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="intranet-FilaTitulo" width="60">&nbsp;</td>
                    <td class="intranet-FilaTitulo" width="80">CODIGO</td>
                    <td class="intranet-FilaTitulo" width="450">DESCRICPION</td>
                    <td class="intranet-FilaTitulo" width="150">MARCA</td>
                    <td class="intranet-FilaTitulo" width="100">PRECIO</td>
                    <td class="intranet-FilaTitulo" width="100">PRES.VENTA</td>
                    <td class="intranet-FilaTitulo" width="60">&nbsp;</td>
                </tr>

                <?php

                $lnConBaj = 0;
                foreach ($arrRegAlt as $rowArtAlt) {

                $lnConBaj = $lnConBaj + 1;

                $lcImagen  = '';
                $lcFuncion = '';
                $lcArtPeg  = '';

                $cArtCod = $rowArtAlt ['CODIGO'];
                $cArtDes = $rowArtAlt ['NOMBRE'];
                $cArtPro = $rowArtAlt ['CDPROVE'];
                $cArtMar = $rowArtAlt ['MARCA'];
                $cArtRef = $rowArtAlt ['REFEREN'];
                $cArtInd = $rowArtAlt ['IND_ACT'];
                $cArtSus = $rowArtAlt ['INTERCAMBIO'];
                $cArtPre = $rowArtAlt ['PRES_VENTA'];
                $cArtFky = $rowArtAlt ['IND_FRKY'];

                $lcArtPeg = '';
                if ($cArtFky == 1) {
                    $lcArtPeg = '<img src="' . $Rutas ['Imagenes'] . 'fky.jpeg" style="height:15px;width:15px" title="Articulo en surtido ferrOkey" />';
                }

                $lWhere  = ' CDPROVE = ' . $cArtPro;
                $lOrden  = '';
                $lLimit  = '';
                $oProv   = new WebProveedor();
                $arrProv = $oProv->obtenerRegistros(Conexion::getInstancia(), $lWhere, $lOrden, $lLimit);
                $cArtRaz = '';
                foreach ($arrProv as $rowPro) {
                    $cArtRaz = $rowPro ['RAZON'];
                }

                //Imagen
                $lcImagen    = $Rutas ['Imagenes_Arts_Exis'] . $cArtCod . '.jpg';
                $lcImagenAux = $Rutas ['Imagenes_Arts_Real'] . $cArtCod . '.jpg';
                if (file_exists($lcImagenAux)) {
                    $tamanyo = getimagesize($lcImagenAux, $arrInfoImagen);
                    list($ancho, $alto, $tipo, $atributos) = getimagesize($lcImagenAux);
                    $lnTamImagen_Alto  = $alto / 2;
                    $lnTamImagen_Ancho = $ancho / 2;
                    $lcFuncion         = "fAccionDetalle ('', '" . $lcNomPageAcc . "', '" . $cArtCod . "', this.value);";
                    $lcImagen          = '<img src="' . $Rutas ['Imagenes'] . 'bFoto.gif" style="height:15px;width:15px" onmouseover="Tip(' . "'<img src=\'" . $lcImagen . "\' style=\'height:" . $lnTamImagen_Alto . "px;width:" . $lnTamImagen_Ancho . "px\'>'" . ')" />';
                }

                //Obtener precio
                $oPrecios  = new WebArticulosPres();
                $cArtCos   = 0;
                $lWherePvp = " articulo = '" . $cArtCod . "' AND um = '" . $cArtPre . "'";
                $lOrdenPvp = '';
                $lLimitPvp = '';
                $arrPvps   = $oPrecios->obtenerRegistros(
                    Conexion::getInstancia(),
                    $lWherePvp,
                    $lOrdenPvp,
                    $lLimitPvp
                );
                foreach ($arrPvps as $rowPvp) {
                    $cArtCos = $rowPvp [$lCampoPrecio];
                }
                $lcArtCos = number_format($cArtCos, 4);

                if ($cArtSus == 0) {
                    $lcArtSus = '';
                } else {
                    $lcArtSus = $cArtSus;
                }

                $lcFilaColor = '';
                //if (($lnConBaj % 2) == 0) { $lcFilaColor = "intranet-LetraError"; }
                if (($lnConBaj % 2) == 0) {
                    $lcFilaColor = "intranet-FilaMarca_Amarilla";
                }

                if ($llExpExcel) {
                    $lcArtPeg = 'fky';
                    $lcImagen = '';
                }

                //TODO: Ni idea de donde viene esto
                $lcFilaColorFue = '';
                ?>
                <tr>
                    <td align="center"
                        class="<?php echo $lcFilaColor; ?>"
                        valign="top"><?php echo $lcImagen; ?></td>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcFilaColorFue; ?>"><?php echo $cArtCod; ?></span></td>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcFilaColorFue; ?>"><?php echo $cArtDes; ?></span></td>
                    <td align="" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcFilaColorFue; ?>"><?php echo $cArtMar; ?></span></td>
                    <td align="center" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcFilaColorFue; ?>"><?php echo $lcArtCos; ?></span></td>
                    <td align="center" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcFilaColorFue; ?>"><?php echo $cArtPre; ?></span></td>
                    <td align="center" class="<?php echo $lcFilaColor; ?>" valign="top">
                        <span class="<?php echo $lcFilaColorFue; ?>"><?php echo $lcArtPeg; ?></span></td>
                </tr>

                <?php
                }
                ?>
            </table>

            <?php } ?>

        </div>
        <?php } ?>

        <?php if ($lcMensajeProceso <> '') { ?>
        <br />
        <div id="dvMensajes" style="height:40px; text-align: center" align="center">
            <span class="intranet-LetraError"><?php echo $lcMensajeProceso; ?></span>
        </div>
        <?php } ?>

        <?php if ($llDivBotones_Inf) { ?>
        <br />
        <div id="dvBotonesInf" style="text-align:center" class="botonera">
            <button type="button"
                    id="bBuscar"
                    name="bBuscar"
                    class="button"
                    onClick="fAccion('_self','<?php echo $lcNomPageBus; ?>','B')"
                    tabindex="12"><span><span>  Buscar  </span></span></button>
        </div>
        <?php } ?>

        <?php if ($llDivPie) { ?>
        <br />
        <div id="dvPie" style="height:40px">
        </div>
        <?php } ?>

    </div>
</form>

</body>

<?php if ($llDivHiddenDown) { ?>
<iframe id="frmDescarga" name="frmDescarga" style="visibility:hidden" height="1" width="1"></iframe><?php } ?>
</html>

<?php
}
?>

