<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase        = '../';                                          // COMO IR AL DIRECTORIO BASE
$llDepurar        = false;                                          // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan         = 'Consulta tarifas ALMACEN';                     // TITULO DE LA PAGINA
$llExpExcel       = false;                                          // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc         = 'TARIFAS_ALMACENS.xls';                         // NOMBRE DEL FICHERO EXCEL A GENERAR
$lcNomPagina      = mb_strtolower($_SERVER['PHP_SELF']);            // NOMBRE DE LA PAGINA PARA ESTABLECER PERMISOS
$lnPos            = mb_strrpos($lcNomPagina, '/');
if ($lnPos <> 0) {
    $lcNomPagina = mb_substr($lcNomPagina, $lnPos + 1);
}
//-PARAMETROS GENERAL PAGINA


//+Variables
//Globales o otros ficheros
global $DBTableNames;
global $DBName;
global $DBUserName;
global $DBPwd;
global $DBServer;
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
//Variables de pagina
$lnPag_Act          = 0;
$bolEnviarCorreoCliente = false;
//-Variables

//+REQUIRES POR DEFECTO y PSESSIONS
include_once $lcDirBase . 'jed_paratube.php';
//-REQUIRES POR DEFECTO y PSESSIONS

//+REQUIRES_OPCIONALES
//-REQUIRES_OPCIONALES

//+CONEXION BD
$connection = AccesoDatos::fgCrearConexion($DBUserName, $DBPwd, $DBServer, $DBName);
//-CONEXION BD

//+CAPTURO EL CODIGO DE USUARIO
$pIdMagento     = isset($pIdMagento) ? $pIdMagento : -1;
$lnIdUsuMagento = $pIdMagento;
//-CAPTURO EL CODIGO DE USUARIO

//+VALIDACION ACCESOS
//(1=ok		0=Sin validacion	-1=Usuario no permitido		-2=actualizando datos		-3=opcion no configurada)
$lnStatus                 = 0;                                    // 1 o 0 o -1 o -2 o -3
$llValidarUsuario         = true;
$llValidar_API            = true;
$llSoloUserDepurador      = false;
$llValidar_TiendaFerrokey = false;
$llVistaEspecial          = false;

include_once $lcDirBase . 'jed_valuso.php';

$llDepurar = $laDatosClienteConf['ADM_INFOR_DBG'];                // VERIFICAR SI EL USUARIO ES DEPURADOR
//-VALIDACION ACCESOS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////                           PAGINA VARIABLE                                  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//+PAGINAS RESPUESTAS
$lcNomPageAct    = $Rutas['Base_Intranet_Ww'] . 'Socios/art_Tarifas_Almacen.php';
$lcNomPageBus    = $lcNomPageAct;
$lcNomPageExc    = $Rutas['Base_Intranet_Ww'] . 'Socios/art_Tarifas_Almacen.php';
$lcNomPageBusPro = $Rutas['Base_Intranet_Ww'] . 'Socios/art_Tarifas_Almacen.php';
$lcNomPageVol    = $lcNomPageAct;
//-PAGINAS RESPUESTAS

//+CAPTURAR VARIABLES FORMULARIO
$oAccion     = Helper::post('oAccion');
$pSession    = Helper::post('pSession');
$oDetalle    = Helper::post('oDetalle');
$oParam1     = Helper::post('oParam1');
$oParam2     = Helper::post('oParam2');
$oParam3     = Helper::post('oParam3');
$oParam4     = Helper::post('oParam4');
$oParam5     = Helper::post('oParam5');
//Parametros propios de la pagina
$oCodigo    = isset($_POST['oCodigo']) ? $_POST ['oCodigo'] : '';
$oDes       = isset($_POST['oDes']) ? $_POST ['oDes'] : '';
$oMarca     = isset($_POST['oMarca']) ? $_POST ['oMarca'] : '';
$oProveedor = isset($_POST['oProveedor']) ? $_POST ['oProveedor'] : '';
$oProRaz    = isset($_POST['oProRaz']) ? $_POST ['oProRaz'] : '';
$oRef       = isset($_POST['oRef']) ? $_POST ['oRef'] : '';
$oIncBaja   = isset($_POST['oIncBaja']) ? $_POST ['oIncBaja'] : '';
$oOrden     = isset($_POST['oOrden']) ? $_POST ['oOrden'] : '';
$oBusCod    = isset($_POST['oBusCod']) ? $_POST ['oBusCod'] : '';
$oBusDes    = isset($_POST['oBusDes']) ? $_POST ['oBusDes'] : '';
$oBusCba    = isset($_POST['oBusCba']) ? $_POST ['oBusCba'] : '';
//-FIN CAPTURAR VARIABLES FORMULARIO

//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Grupo      = 'ARTICULO';
$lcLog_Texto      = '';
//REVISAR SI VE COSTES
$llOpcionCoste = true;
if ($laDatosCliente['VER_COSTE'] == 'NO') {
    $llOpcionCoste = false;
}
//ACCIONES FORMULARIO
$lcPrecio = $laDatosCliente['TIPO_PRECIO'];

$mensaje = <<<MSG
oAccion: {$oAccion} / TipPrecio: {$lcPrecio} / Inc.Bajas: {$oIncBaja} / Orden: {$oOrden} /
Codigo: {$oCodigo} / Descripcion: {$oDes} / Proveedor: {$oProveedor} / Marca: {$oMarca}
MSG;
Iniutils::escribeEnLog($mensaje);

//
$llPintaFiltroBusqueda     = true;
$llPintaRestultadoBusqueda = false;
$llPintaFiltroBusquedaPro  = false;
if (($oAccion == 'B') or ($oAccion == 'EX')) {
    if (($oCodigo == '') and ($oDes == '') and ($oProveedor == '') and ($oMarca == '')) {
        $lcMensajeProceso = 'INTRODUCE ALGUN PARAMETRO DE BUSQUEDA.';
    } else {
        $llPintaFiltroBusqueda     = false;
        $llPintaRestultadoBusqueda = true;
        $lcLog_Texto               = 'TARIFAS (' . $oAccion . '): ' . $oCodigo . '/' . $oDes . '/' . $oProveedor . '/' . $oMarca;
    }
} else {
    if (($oAccion == 'L')) {
        $llPintaFiltroBusqueda     = false;
        $llPintaRestultadoBusqueda = true;
    } else {
        if (($oAccion == 'BUSPRO')) {
            $llPintaFiltroBusqueda    = false;
            $llPintaFiltroBusquedaPro = true;
            $llBuscar                 = true;
        } else {
            if (($oAccion == 'RESPRO')) {
                $llPintaFiltroBusqueda    = false;
                $llPintaFiltroBusquedaPro = true;
                $llBuscar                 = false;
                //Cba
                if ($oBusCba <> '') {
                    echo 'Buscar por codigo de barras ( ' . $oBusCod . ' / ' . $oBusDes . ' )';
                }

                // Buscar proveedor.
                if (!empty($oBusCod)) {
                    $lWhere = "CDPROVE = {$oBusCod}";
                } else {
                    if (!empty($oBusDes)) {
                        $lWhere = "RAZON LIKE '%{$oBusDes}%'";
                    }
                }
                $lOrd   = "CDPROVE";

                $oProveedor = new WebProveedor();
                $oProveedor->depurarObjeto($llDepurar);
                $arrProveedores = $oProveedor->obtenerRegistros(Conexion::getInstancia(), $lWhere, $lOrd);

            }
        }
    }
}
//-

//+LOG
AccesoDatos::grabarLog(Conexion::getInstancia(), $pgIdentificadorEmpresa, $UserID, $UserSuc, $lcLog_Grupo, $lcLog_Texto, $lnIdUsuMagento);
//-LOG

//+CONFIGURACION CONTENIDO
$lcAyuTex           = "<B>Utilice esta pantalla para sacar listado de precios de articulos</B><BR>";
$lcAyuTex          .= " - En los campos descriptivos (descripcion y marca), hace falta poner asteriscos.<BR>";
$lcAyudaPantalla_P  = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco       = "document.getElementById('oCodigo').focus()";
$lcObjetoFoco       = "";
$llDivTitulo        = true;
$llDivExplicacion   = false;
$llDivBotones_Sup   = true;
$llDivBotones_Inf   = true;
$llDivPie           = false;
$llDivHiddenDown    = false;
//-CONFIGURACION CONTENIDO

//Pintar botones segun capas
$lAccion_Volver    = '';
$llDivBotones_Inf  = $llPintaFiltroBusqueda;

//Registros por pagina
$registrosPorPagina = (int) App::getInstancia()->getNumeroRegistrosListado();

//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();


if ($llExpExcel) {


    //Sobre el fichero
    $lcNombrePestana = 'TARIFAS_ALMACEN';

    //Cabeceras informe
    $cabeceras = [
        'COD.BARRA',
        'ARTICULO',
        'PROVEEDOR',
        'DESCRIPCION',
        'REFERENCIA',
        'MARCA',
        'COSTE',
        'PRESENTACION',
        '¿ESTA DE BAJA?',
        'EXISTENCIA',
    ];

    //Exportar a Excel
    $phpExcel = new PHPExcel();
    $phpExcel->getActiveSheet()->setTitle($lcNombrePestana);
    $hojaActiva = $phpExcel->getActiveSheet();
    PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

    // Título en la primera fila.
    $hojaActiva->getStyle('A1')->getFont()->setSize(20);
    $hojaActiva->getStyle('A1')->getFont()->setBold(true);
    $hojaActiva->mergeCells('A1:D1');

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
    $lIncBaja = '';
    $cFiltrosAux = '';
    if ($oIncBaja == 'NO') {
        $cFiltrosAux .= '#NO_BAJA';
    }
    if ($oIncBaja == 'BA') {
        $cFiltrosAux .= '#SOLO_BAJA';
    }

    $oArts = new WebArticulos();
    $oCBarras = new WebArticulosPres();
    //$oArts->depurarObjeto($llDepurar);
    $lFamilia     = '';
    $lOrd         = "a.CODIGO";
    $arrRegs = $oArts->getListaConPrecios($lcPrecio, $oCodigo, $oDes, $oProveedor, $oMarca, $oRef, $lFamilia, $cFiltrosAux, '', $lOrd);


    $fila++; // Los datos comienzan en la fila 5.
    $lnCon    = 0;
    $lnTotAcu = 0;

    foreach ($arrRegs as $aLineaAlbaran) {

        $columna = 'A';

        $cArt  = (int)$aLineaAlbaran ['ART'];
        $cPro  = $aLineaAlbaran ['PROV'];
        $cDes  = $aLineaAlbaran ['DES'];
        $cRef  = $aLineaAlbaran ['REF'];
        $cMar  = $aLineaAlbaran ['MARCA'];
        $cCos  = $aLineaAlbaran ['COSTE'];
        $cPre  = $aLineaAlbaran ['PRES_BASE'];
        $cAct  = $aLineaAlbaran ['ACT'];
        $cSto  = $aLineaAlbaran ['STOCK'];;

        $cCba1 = '';
        $cCba2 = '';
        $cCba3 = '';
        $arrCbs = $oCBarras->obtenerRegistros(Conexion::getInstancia(), "ARTICULO = $cArt");
        foreach ($arrCbs as $aCb) {
            $cCba1 = $aCb['CDBARRA'];
        }
        //$cArtPvp  = $aLineaAlbaran ['PRECIO'];

        $lcColorLetra  = '';
        $cEstado       = '';
        if ($cAct == 0) {
            $lcColorLetra  = 'red';
            $cEstado       = 'SI';
        }

        $hojaActiva->setCellValueExplicit($columna++ . $fila, $cCba1, PHPExcel_Cell_DataType::TYPE_STRING);
        //$hojaActiva->setCellValue($columna++ . $fila, $cCba1);
        $hojaActiva->setCellValue($columna++ . $fila, $cArt);
        $hojaActiva->setCellValue($columna++ . $fila, $cPro);
        $hojaActiva->setCellValue($columna++ . $fila, $cDes);
        $hojaActiva->setCellValue($columna++ . $fila, $cRef);
        $hojaActiva->setCellValue($columna++ . $fila, $cMar);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $cCos);
        $hojaActiva->setCellValue($columna++ . $fila, $cPre);
        $hojaActiva->setCellValue($columna++ . $fila, $cEstado);
        $hojaActiva->setCellValue($columna++ . $fila, $cSto);
        //$hojaActiva->setCellValue($columna++ . $fila, $cCba);
        //$hojaActiva->setCellValue($columna++ . $fila, $cCba);
        //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lArt_Est, PHPExcel_Cell_DataType::TYPE_STRING);
        //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lcRefProv, PHPExcel_Cell_DataType::TYPE_STRING);

        $fila++;

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
    <input type="hidden" id="oParam1" name="oParam1" value="">
    <input type="hidden" id="oParam2" name="oParam2" value="">
    <input type="hidden" id="oParam3" name="oParam3" value="">
    <input type="hidden" id="oParam4" name="oParam4" value="">
    <input type="hidden" id="oParam5" name="oParam5" value="">
    <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>">
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
                       title="Exportar a fichero Excel"
                       onClick="fAccion('frmDescarga','<?php echo $lcNomPageExc; ?>','EX');"></i>
                    <?php } ?>
                    <i class="fa fa-question fa-icono-header" style="cursor: none"
                       title=""
                       onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
                    <?php if ($llPintaRestultadoBusqueda)  { ?>
                    <i class="fa fa-arrow-left fa-icono-header"
                       title="Volver a la página anterior"
                       onClick="fAccion('','<?php echo $lcNomPageVol; ?>','<?php echo $lAccion_Volver; ?>');"></i>
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
                    <td width="30%" align="center" valign="middle"><span class="intranet-Text">CODIGO DE ARTICULO</span>
                    </td>
                    <td width="70%"><input name="oCodigo"
                                           type="text"
                                           title=""
                                           id="oCodigo"
                                           tabindex="2"
                                           value="<?php echo $oCodigo; ?>"
                                           maxlength="10"
                                           onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageAct; ?>','R')"
                                           style="width:80px" /></td>
                </tr>
                <tr>
                    <td align="center" valign="middle"><span class="intranet-Text">DESCRIPCION</span></td>
                    <td><input name="oDes"
                               type="text"
                               title=""
                               id="oDes"
                               tabindex="3"
                               value="<?php echo $oDes; ?>"
                               size="40"
                               maxlength="80"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageAct; ?>','R')"
                               style="width:300px" />&nbsp;&nbsp;&nbsp;<span style="font-size:11px; color:black">Hace falta poner asteriscos</span>
                    </td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">CODIGO PROVEEDOR</span></td>
                    <td>
                        <input name="oProveedor"
                               type="text"
                               title=""
                               id="oProveedor"
                               tabindex="4"
                               value="<?php echo $oProveedor; ?>"
                               size="6"
                               maxlength="6"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageAct; ?>','R')"
                               style="width:60px" />
                        <input name="oProRaz"
                               type="text"
                               title=""
                               id="oProRaz"
                               tabindex="3"
                               value="<?php echo $oProRaz; ?>"
                               style="width:300px"
                               readonly="readonly" /></td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">MARCA</span></td>
                    <td><input name="oMarca"
                               type="text"
                               title=""
                               id="oMarca"
                               tabindex="5"
                               value="<?php echo $oMarca; ?>"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageAct; ?>','R')"
                               style="width:100px" />&nbsp;&nbsp;&nbsp;<span style="font-size:11px; color:black">Hace falta poner asteriscos</span>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="middle" colspan="2" height="40">
                        <div style="background-color: #8C8D8F; color: white; font-weight: bold; padding: 2px 0">
                                    OPCIONES PARA LOS LISTADOS
                                </div>
                    </td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">INCLUIR BAJAS?</span></td>
                    <td><SELECT id="oIncBaja" name="oIncBaja" title="">
                            <option value="NO" <?php if ($oIncBaja == 'NO') {
                                echo 'SELECTED';
                            } ?>>NO
                            </option>
                            <option value="SI" <?php if ($oIncBaja == 'SI') {
                                echo 'SELECTED';
                            } ?>>SI
                            </option>
                            <option value="BA" <?php if ($oIncBaja == 'BA') {
                                echo 'SELECTED';
                            } ?>>SOLO BAJAS
                            </option>
                        </SELECT></td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">ORDERNAR POR</span></td>
                    <td><SELECT id="oOrden" name="oOrden" title="">
                            <option value="D" <?php if ($oOrden == 'D') {
                                echo 'SELECTED';
                            } ?>>DESCRIPCION
                            </option>
                            <option value="M" <?php if ($oOrden == 'M') {
                                echo 'SELECTED';
                            } ?>>MARCA
                            </option>
                        </SELECT></td>
                </tr>
            </table>
        </div>
        <?php } ?>

    <!-- BUSCAR PROVEEDOR -->
        <?php if ($llPintaFiltroBusquedaPro) { ?>
        <br />
        <!--<div id="dvContenido" style="OVERFLOW: auto; WIDTH: 1000px; HEIGHT: 500px">-->
        <div id="dvContenido">
        <?php if ($llBuscar) { ?>
        <!-- FORMULARIO DE BUSQUEDA -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="100" align="center" valign="middle">

                        <table width="90%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="25%" height="25" align="left">Codigo</td>
                                <td width="75%" align="left"><input name="oBusCod"
                                                                    type="text"
                                                                    title=""
                                                                    id="oBusCod"
                                                                    tabindex="1"
                                                                    value="<?php echo $oBusCod; ?>"
                                                                    size="6"
                                                                    maxlength="4"
                                                                    onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','RESBUS')"
                                                                    style="width:80px" /></td>
                            </tr>
                            <tr>
                                <td height="25" align="left">Descripcion</td>
                                <td align="left"><input name="oBusDes"
                                                        type="text"
                                                        title=""
                                                        id="oBusDes"
                                                        tabindex="2"
                                                        value="<?php echo $oBusDes; ?>"
                                                        size="60"
                                                        maxlength="60"
                                                        onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','RESBUS')" />
                                </td>
                            </tr>
                            <tr>
                                <td height="25" align="left">Codigo de barras</td>
                                <td align="left"><input name="oBusCba"
                                                        type="text"
                                                        title=""
                                                        id="oBusCba"
                                                        tabindex="2"
                                                        value="<?php echo $oBusCba; ?>"
                                                        size="20"
                                                        maxlength="20"
                                                        onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','RESBUS')"
                                                        style="width:160px" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                            <tr>
                                <td height="5" colspan="2" align="right"><span><span style="font-size: small;color: red">*** No hace falta poner asteriscos al buscar por nombre&nbsp;</span></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        <?php } else { ?>
        <!-- CONSULTA -->
            <table>
                <tr>
                    <td height="40" align="center" valign="middle" class="intranet-FilaTitulo"><span>PROVEEDORES ENCONTRADOS</span>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="middle">
                        <table width="700" border="0" cellspacing="0" cellpadding="0">
                            <?php


                            $lnNumReg = 0;

                            foreach ($arrProveedores as $row) {

                            $llPintar = true;

                            $lcProCod       = $row['CODIGO'];
                            $lcPintarProCod = $lcProCod;
                            //if (($oAccion == "E") or ($Accion == "E")) {
                            if (($oAccion == "E") or ($oAccion == "E")) {
                                $lcPintarProCod = "' " . $lcProCod;
                            }

                            $lcSuc    = '1';
                            $lcProDes = str_replace('?', 'Ñ', strtoupper($row['NOMBRE']));
                            $lcPob    = str_replace('?', 'Ñ', strtoupper($row['LOCALIDAD']));
                            $lcMar    = str_replace('?', 'Ñ', strtoupper($row['NOMCOMER']));
                            $lcProNif = $row['NIF'];
                            $lcProFex = '';

                            $lcClaseLet = 'letra_sec';
                            if ($row['ESTADO'] == 0) {
                                $lcClaseLet = 'letra_error';
                            }
                            $lcEnlace = '<a href="#">';

                            // VALIDAR SUCURSALES....
                            if ($llPintar) {

                                if ((int) $lcProCod == 888) {
                                    $llPintar = false;
                                    if ((int) $lcSuc == 1) {
                                        $llPintar = true;
                                    }
                                } else {
                                    if ((int) $lcProCod == 169) {
                                        $llPintar = false;
                                        if ((int) $lcSuc == 1) {
                                            $llPintar = true;
                                        }
                                    }
                                }
                            }

                            if ((int) $lcSuc == 99) {
                                $llPintar = false;
                            } else {
                                if ((int) $lcSuc == 10) {
                                    $llPintar = false;
                                }
                            }

                            if ($llPintar) {

                            $lSel     = "fAccionMultiple('', '" . $lcNomPageAct . "', '', 'X', '" . $lcProNif . "', '', '', '', '')";
                            $lnNumReg = $lnNumReg + 1;

                            if (($lnNumReg % 2) == 0) {
                                $lcClaFil = '';
                            } else {
                                $lcClaFil = 'intranet-FilaMarca_Amarilla';
                            }

                            ?>
                            <tr>
                                <td width="30"
                                    align="center"
                                    valign="top"
                                    class="<?php echo $lcClaFil; ?>"><?php if (!$llExpExcel) { ?>
                                    <img src="<?php echo $Rutas ['Imagenes']; ?>bAdd.jpg"
                                         style="width:20px;height=20px"
                                         class="pondedo"
                                         onClick="<?php echo $lSel; ?>" /><?php } ?></td>
                                <td width="150" align="center" valign="top" class="<?php echo $lcClaFil; ?>"><span
                                            class="<?php echo $lcClaseLet; ?>"><?php echo $lcPintarProCod; ?>
                                        / <?php echo $lcSuc; ?></span></td>
                                <td width="350" align="left" valign="top" class="<?php echo $lcClaFil; ?>"><span
                                            class="<?php echo $lcClaseLet; ?>"><?php echo $lcProDes; ?></span></td>
                                <td width="250" align="left" valign="top" class="<?php echo $lcClaFil; ?>"><span
                                            class="<?php echo $lcClaseLet; ?>"><?php echo $lcMar; ?></span></td>
                                <td width="100" align="center" valign="top" class="<?php echo $lcClaFil; ?>"><span
                                            class="<?php echo $lcClaseLet; ?>"><?php echo $lcProNif; ?></span></td>
                                <td width="10" align="left" valign="top" class="<?php echo $lcClaFil; ?>"></td>
                            </tr>
                            <?php
                            }
                            }
                            ?>
                            <tr>
                                <td colspan="6" class="intranet-FilaTotal"><span> <?php echo $lnNumReg; ?> registro/s encontrado/s</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <?php } ?>
        </div>
        <?php } ?>
    <!-- FIN BUSCAR PROVEEDOR -->

        <?php if ($llPintaRestultadoBusqueda) { ?>

        <input type="hidden" id="oCodigo" name="oCodigo" value="<?php echo $oCodigo; ?>" />
        <input type="hidden" id="oDes" name="oDes" value="<?php echo $oDes; ?>" />
        <input type="hidden" id="oProveedor" name="oProveedor" value="<?php echo $oProveedor; ?>" />
        <input type="hidden" id="oMarca" name="oMarca" value="<?php echo $oMarca; ?>" />
        <input type="hidden" id="oRef" name="oRef" value="<?php echo $oRef; ?>" />
        <input type="hidden" id="oOrden" name="oOrden" value="<?php echo $oOrden; ?>" />
        <input type="hidden" id="oIncBaja" name="oIncBaja" value="<?php echo $oIncBaja; ?>" />
        <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>" />

        <?php
        $lnCon    = 0;
        $lnTotAcu = 0;

        //HAGO LA SELECT EN LA PARTE SUPERIOR PARA PAGINAR RESUTLADO
        $lIncBaja = '';
        $cFiltrosAux = '';
        if ($oIncBaja == 'NO') {
            $cFiltrosAux .= '#NO_BAJA';
        }
        if ($oIncBaja == 'BA') {
            $cFiltrosAux .= '#SOLO_BAJA';
        }

        $oArts = new WebArticulos();
        $oArts->depurarObjeto($llDepurar);
        $lFamilia     = '';
        $lOrd         = "a.CODIGO";
        $arrRegs = $oArts->getListaConPrecios($lcPrecio, $oCodigo, $oDes, $oProveedor, $oMarca, $oRef, $lFamilia, $cFiltrosAux, '', $lOrd);

        // Paginación (se utilizan los parámetros Param4 y Param5).
        $paginaInicial  = 0;
        $totalRegistros = 0;
        $totalRegistros = count($arrRegs);
        $hayPaginacion  = $totalRegistros > 0;

        if ($hayPaginacion) {
        $totalPaginas = (int) ($totalRegistros / $registrosPorPagina);

        Iniutils::escribeEnLog(
            __FILE__ . ": RegsPagina: {$registrosPorPagina} / Pagina Act: {$oParam5} / PagintaTotales: {$totalPaginas} / Registro Actual: {$oParam4} / RegistroTotales: {$totalRegistros}", $llDepurar);

        if ($totalRegistros % $registrosPorPagina <> 0) {
            $totalPaginas++;
        }

        if ($totalRegistros < $registrosPorPagina) {
            $paginaActual  = 1;
            $hayPaginacion = false;
        } else {
            $paginaActual = $oParam5;
            if (empty($oParam5)) {
                if (!empty($oParam4)) {
                    $registroActual = (int) $oParam4;
                    $paginaActual   = (int) ($registroActual / $registrosPorPagina);
                    if ($registroActual % $registrosPorPagina <> 0) {
                        $paginaActual++;
                    }
                }
            }

            Iniutils::escribeEnLog(__FILE__ . ": Pagina: {$paginaActual}", $llDepurar);

            if ($paginaActual <= 0 || empty($paginaActual)) {
                $paginaActual = 1;
            }

            if ($paginaActual > $totalPaginas) {
                $paginaActual = $totalPaginas;
            }

            $paginaInicial = $paginaActual - 1;

            // Preparo el LIMIT.
            $lLimit = ' LIMIT ' . ($paginaInicial * $registrosPorPagina) . ',' . $registrosPorPagina;
            // Ejecutamos la query con el LIMIT.
            $arrRegs = $oArts->getListaConPrecios($lcPrecio, $oCodigo, $oDes, $oProveedor, $oMarca, $oRef, $lFamilia, $cFiltrosAux, '', $lOrd, $lLimit);

        } ?>
        <div class="table-flex">
            <div class="tr-flex">
                <div class="td-flex" style="justify-content: flex-end">
                    <img src="<?php echo $Rutas['Imagenes']; ?>bInicio.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAct; ?>','','B', '', '', '', '', '1');"
                         alt="Ir a la primera pagina">
                    <img src="<?php echo $Rutas['Imagenes']; ?>bAtras.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAct; ?>','','B', '', '', '', '', '<?php echo($paginaActual - 1); ?>');"
                         alt="Ir a la pagina anterior">
                    <span style="margin: auto 5px;"><?php echo "Página {$paginaActual} de {$totalPaginas}"; ?></span>
                    <img src="<?php echo $Rutas['Imagenes']; ?>bDelante.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAct; ?>','','B', '', '', '', '', '<?php echo($paginaActual + 1); ?>');"
                         alt="Ir a la pagina siguiente">
                    <img src="<?php echo $Rutas['Imagenes']; ?>bFinal.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAct; ?>','','B', '', '', '', '', '<?php echo $totalPaginas; ?>');"
                         alt="Ir a la ultima pagina">
                </div>
            </div>
        </div>
        <?php
        }
        // Fin Paginacion.
        ?>
        <div id="dvContenido">
            <div class="table-flex" style="border: 1px solid <?php echo "#{$colorCorporativo}"; ?>;">
                <div class="tr-flex th-flex" style="color: #ffffff; background-color: <?php echo "#{$colorCorporativo}"; ?>">
                    <div class="td-flex" style="justify-content: center">ARTICULO</div>
                    <div class="td-flex" style="justify-content: center">PROVEEDOR</div>
                    <div class="td-flex" style="flex-grow: 4; justify-content: flex-start">DESCRIPCION</div>
                    <div class="td-flex" style="justify-content: flex-start">REFERENCIA</div>
                    <div class="td-flex" style="justify-content: flex-start">MARCA</div>
                    <div class="td-flex" style="justify-content: center">COSTE</div>
                    <div class="td-flex" style="justify-content: center">PRESENTACION</div>
                    <!--<div class="td-flex" style="justify-content: flex-end">PRECIO</div>-->
                </div>
                <?php
                $lnAcuBas = 0;
                $lnAcuIva = 0;
                $lnAcuTot = 0;

                foreach ($arrRegs as $aLineaAlbaran) {

                $lnCon++;

                $cArt  = $aLineaAlbaran ['ART'];
                $cPro  = $aLineaAlbaran ['PROV'];
                $cDes  = $aLineaAlbaran ['DES'];
                $cRef  = $aLineaAlbaran ['REF'];
                $cMar  = $aLineaAlbaran ['MARCA'];
                $cCos  = $aLineaAlbaran ['COSTE'];
                $cPre  = $aLineaAlbaran ['PRES_BASE'];
                $cAct  = $aLineaAlbaran ['ACT'];
                //$cArtPvp  = $aLineaAlbaran ['PRECIO'];

                $lcPrecio   = number_format($cCos,4,',', '.');

                $llPintarLinea = true;
                $lcColorLetra  = '';
                if ($cAct == 0) {
                    $lcColorLetra  = 'intranet-LetraError';
                }

                if ($llPintarLinea) {
                ?>
                <div class="tr-flex">
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center"><?php echo $cArt; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center"><?php echo $cPro; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?> texto-con-saltos" style="flex-grow: 4; justify-content: flex-start"><?php echo $cDes; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: flex-start"><?php echo $cRef; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: flex-start"><?php echo $cMar; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center"><?php echo $lcPrecio; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: flex-start"><?php echo $cPre; ?></div>
                </div>

                <?php
                }
                }
                ?>

                <div class="tr-flex">
                    <div class="td-flex intranet-FilaTotal" style="flex-grow: 2;">
                        <div class="td-flex" style="justify-content: flex-start"><?php echo "{$lnCon} de {$totalRegistros} registros"; ?></div>
                    </div>
                </div>

            </div>

        </div>
        <?php } ?>

        <?php if ($lcMensajeProceso <> '') { ?>
        <br />
        <div id="dvMensajes" style="height:40px; text-align: center">
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

<?php if ($connection) {
    fgCerrarConexion($connection);
} ?>

</body>

<?php if ($llDivHiddenDown) { ?>
<iframe id="frmDescarga" name="frmDescarga" style="visibility:hidden" height="1" width="1"></iframe><?php } ?>
</html>

<?php
}

?>
