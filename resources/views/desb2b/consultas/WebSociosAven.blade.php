<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase   = '../';                                          // COMO IR AL DIRECTORIO BASE
$llDepurar   = false;                                          // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan    = 'Buscar en que albaran se ha comprado un articulo'; // TITULO DE LA PAGINA
$llExpExcel  = false;                                          // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc    = 'INFORME_VENTAS.xls';                           // NOMBRE DEL FICHERO EXCEL A GENERAR
$lcNomPagina = mb_strtolower($_SERVER['PHP_SELF']);            // NOMBRE DE LA PAGINA PARA ESTABLECER PERMISOS
$lnPos       = mb_strrpos($lcNomPagina, '/');
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
$laDatosCliente     = [];
$laDatosClienteConf = [];
$llVistaEspecial    = false;
$stmtCabAlb         = 0;
$cArtCod            = '';
$cArtSus            = '';
//-Variables

//+REQUIRES POR DEFECTO y PSESSIONS
include_once $lcDirBase . 'jed_paratube.php';
//-REQUIRES POR DEFECTO y PSESSIONS

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
$lcNomPageAct = $Rutas['Base_Intranet_Ww'] . 'Socios/art_Comprados.php';
$lcNomPageBus = $lcNomPageAct;
$lcNomPageVol = $lcNomPageAct;
//-PAGINAS RESPUESTAS

//+CAPTURAR VARIABLES FORMULARIO
$oAccion  = Helper::post('oAccion');
$pSession = Helper::post('pSession');
$oDetalle = Helper::post('oDetalle');
$oParam1  = Helper::post('oParam1');
$oParam2  = Helper::post('oParam2');
$oParam3  = Helper::post('oParam3');
$oParam4  = Helper::post('oParam4');
$oParam5  = Helper::post('oParam5');
//Parametros propios de la pagina
$oSocio     = Helper::post('oSocio', '');
$oSucursal  = Helper::post('oSucursal', '');
$oAlbaran   = Helper::post('oAlbaran', '');
$oCodigo    = Helper::post('oCodigo', '');
$oDescrip   = Helper::post('oDescrip', '');
$oProveedor = Helper::post('oProveedor', '');
$oFecDes    = Helper::post('oFecDes', '');
$oFecHas    = Helper::post('oFecHas', '');
$oMarca     = Helper::post('oMarca', '');
$oRef       = Helper::post('oRef', '');
//-FIN CAPTURAR VARIABLES FORMULARIO

//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Grupo      = 'ARTICULO';
$lcLog_Texto      = '';

$mensaje = <<<MSG
Codigo: {$oCodigo} / Descripción: {$oDescrip} / Proveedor: {$oProveedor} / FecDesde: {$oFecDes} /
FecHasta: {$oFecHas} / VerCoste: {$laDatosCliente['VER_COSTE']} / TipoPrecio: {$laDatosCliente['TIPO_PRECIO']}
MSG;

//
$llPintaFiltroBusqueda     = true;
$llPintaRestultadoBusqueda = false;
if (($oAccion == 'B') or ($oAccion == 'EX')) {
    $llPintaFiltroBusqueda     = false;
    $llPintaRestultadoBusqueda = true;
    $lcLog_Texto               = 'CONSUMO (' . $oAccion . '): ' . $oCodigo . '/' . $oDescrip . '/' . $oProveedor;
}
//Ve costes
$llVerCoste = true;
if ($laDatosCliente['VER_COSTE'] == 'NO') {
    $llVerCoste = false;
}
//CARGAR EN UN ARRAY LA CONFIGURACION DEL USUARIO
$laArrConf        = fDatosTerceroConf(
    $connection,
    $DBTableNames['Socios_Conf'],
    $pgIdentificadorEmpresa,
    $UserID,
    $UserSuc,
    $lnIdUsuMagento
);
$llPintarSucursal = false;
if ($laArrConf ['IND_AGR_SUC_CONS'] == 1) {
    $llPintarSucursal = true;
}
//-
//+10/04/2017
$llBuscarCliente = false;
if (isset($laDatosCliente['TIPO_CLIENTE']) && $laDatosCliente['TIPO_CLIENTE'] == 'EMPLEADO') {
    $llBuscarCliente = true;
}
if ($laArrConf['IND_USER_DATMIN'] == 1) {
    $llBuscarCliente = true;
}
//Revisar si hay que pintar el buscar socio
$lcBuscar_Soc = $UserID;
if ($llBuscarCliente) {
    if ($oAccion == 'B') {
        if ($oSocio <> '') {
            $lcBuscar_Soc = $oSocio;
            if (App::getInstancia()->esFerrcash()) {
                $oTercero = new WebClientes();
            } else {
                $oTercero = new WebSociosSucs();
            }
            $oTercero->depurarObjeto($llDepurar);
            $arrClientes = $oTercero->obtenerRegistros(Conexion::getInstancia(), "CDCLIEN = {$oSocio}", "CDSUCUR");
            foreach ($arrClientes as $laArrConfCliBus) {
                //Quiero seleccionar el primer registro
                if (App::getInstancia()->esFerrcash()) {
                    $oTercero = new WebClientes($laArrConfCliBus);
                } else {
                    $oTercero = new WebSociosSucs($laArrConfCliBus);
                }
            }
            if ($laDatosClienteConf['IND_SOLO_TARICAT'] == 1) {
                if (!in_array($oTercero->tipoCliente(), ['TARICAT', 'TARIBAL'])) {
                    $lcMensajeProceso = 'NO SE PUEDEN SELECCIONAR CLIENTES QUE NO SEAN TARICAT / TARIBAL';
                    $oCliEsp          = $UserID;
                    $oAccion          = '';
                    $lcBuscar_Soc     = $UserID;
                } else {
                    $lcBuscar_Soc = $oSocio;
                }
            }
            if (!empty(trim($laDatosClienteConf['CODIGO_COMPRADOR']))) {
                if ($oTercero->codigoResponsableComercial() <> $laDatosClienteConf['CODIGO_COMPRADOR']) {
                    $lcMensajeProceso = "NO SE PUEDEN SELECCIONAR CLIENTES QUE NO TENGAS ASOCIADO ({$laDatosClienteConf['CODIGO_COMPRADOR']})";
                    $oCliEsp          = $UserID;
                    $lcBuscar_Soc     = $UserID;
                    $oAccion          = '';
                }
            }
        }
        if ($oSocio == '') {
            $lcMensajeProceso = "ES OBLIGATORIO RELLENAR EL CODIGO DE CLIENTE";
            $oAccion          = '';
        }
    }
}
//-10/04/2017

//+LOG
AccesoDatos::grabarLog(
    Conexion::getInstancia(),
    $pgIdentificadorEmpresa,
    $UserID,
    $UserSuc,
    $lcLog_Grupo,
    $lcLog_Texto,
    $lnIdUsuMagento
);
//-LOG

//+CONFIGURACION CONTENIDO
$lcAyuTex          = '<B>Esta pagina se usa para ver en que albaran has comprado un articulo</B><BR>';
$lcAyuTex          .= ' - En los campos descriptivos (descripcion y albaran comafe) falta poner asteriscos.<BR>';
$lcAyuTex          .= ' - Si la fecha esta vacia, no filtra por fecha.<BR>';
$lcAyudaPantalla_P = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco      = "document.getElementById('oCodigo').focus()";
$lcObjetoFoco      = "";
$llDivTitulo       = true;
$llDivExplicacion  = false;
$llDivBotones_Sup  = true;
$llDivBotones_Inf  = false;
$llDivPie          = false;
$llDivHiddenDown   = false;
//-CONFIGURACION CONTENIDO

//Revisar que todos los parametros de busqueda esten rellenos
if ($oAccion == 'B') {
    if (!empty($lcMensajeProceso)) {
        if (($oCodigo == '') and ($oDescrip == '') and ($oFecDes == '') and ($oFecHas == '') and ($oProveedor == '') and ($oAlbaran == '')) {
            $oAccion          = '';
            $lcMensajeProceso = "ES OBLIGATORIO RELLENAR ALGUN VALOR)";
        }
    }
}

//Numero de registros por pagina
$registrosPorPagina = (int) Helper::getNotEmptyFromArray($rowConfEmp, 'numRegsList', 50);

//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();

//Si no hay accion, le dejo en modo busqueda
if ($oAccion == '') {
    $llPintaFiltroBusqueda     = true;
    $llPintaRestultadoBusqueda = false;
}

$llDivBotones_Inf = $llPintaFiltroBusqueda;

if ($llExpExcel) {

    //Sobre el fichero
    $lcNombrePestana = 'ARTICULOS_COMPRADAS';

    //Cabeceras informe
    $cabeceras = [
        'ALBARAN',
        'ALMACEN',
        'SOCIO',
        'SUCURSAL',
        'FECHA',
        'ARTICULO',
        'DESCRIPCION',
        'CANTIDAD',
        'PRECIO',
    ];

    //Datos
    //+Si pinto la sucursal, cojo el valor de este campo
    $lSuc = $UserSuc;
    if ($llPintarSucursal) {
        $lSuc = $oSucursal;
    }
    //
    if (App::getInstancia()->esFerrcash()) {
        $oArts = new WebClientesAven();
    } else {
        $oArts = new WebSociosAven();
    }
    $lWhe = "";
    if ($lcBuscar_Soc <> '') {
        $lWhe .= "alb.CDCLIEN = $lcBuscar_Soc";
    } else {
        $lWhe .= "1=2";
    }
    if ($lSuc <> '') {
        $lWhe .= " AND alb.CDSUCUR = $lSuc";
    }
    if ($oProveedor <> '') {
        $oProveedorNumerico = (int) $oProveedor;
        $lWhe               .= " AND art.CDPROVE = $oProveedorNumerico";
    }
    if ($oCodigo <> '') {
        $lWhe .= " AND alb.CDARTI = $oCodigo";
    }
    if ($oDescrip <> '') {
        $lWhe .= " AND alb.DESCRIP LIKE '" . str_replace('*', '%', $oDescrip) . "'";
    }
    if ($oAlbaran <> '') {
        $lWhe .= " AND alb.CODIGO = '$oAlbaran'";
    }
    if ($oFecDes <> '') {
        $lFecDes = Iniutils::dmyTOymd($oFecDes, '-');
        $lWhe    .= " AND date_format (alb.fecha, '%Y-%m-%d') >= '{$lFecDes}'";
    }
    if ($oFecHas <> '') {
        $lFecHas = Iniutils::dmyTOymd($oFecHas, '-');
        $lWhe    .= " AND date_format (alb.fecha, '%Y-%m-%d') <= '{$lFecHas}'";
    }
    $lOrd    = "alb.CDARTI";
    $arrRegs = $oArts->getLineas(Conexion::getInstancia(), $lWhe, $lOrd);

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
    $fila++; // Los datos comienzan en la fila 5.
    $lnCon    = 0;
    $lnTotAcu = 0;

    foreach ($arrRegs as $aLineaAlbaran) {

        $columna = 'A';

        $cAlbCod = $aLineaAlbaran ['CODIGO'];
        $cAlbAlm = $aLineaAlbaran ['ALMACEN'];
        $cAlbCli = $aLineaAlbaran ['CDCLIEN'];
        $cAlbSuc = $aLineaAlbaran ['CDSUCUR'];
        $cAlbFec = Iniutils::ymdTOdmy($aLineaAlbaran ['FECHA'], '/');
        $cArtCod = $aLineaAlbaran ['CDARTI'];
        $cArtDes = $aLineaAlbaran ['DESCRIP'];
        $cArtCan = $aLineaAlbaran ['CANTIDAD'];
        $cArtPvp = $aLineaAlbaran ['PRECIO'];

        $hojaActiva->setCellValue($columna++ . $fila, $cAlbCod);
        $hojaActiva->setCellValue($columna++ . $fila, $cAlbAlm);
        $hojaActiva->setCellValue($columna++ . $fila, $cAlbCli);
        $hojaActiva->setCellValue($columna++ . $fila, $cAlbSuc);
        $hojaActiva->setCellValue($columna++ . $fila, $cAlbFec);
        $hojaActiva->setCellValue($columna++ . $fila, $cArtCod);
        $hojaActiva->setCellValue($columna++ . $fila, $cArtDes);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $cArtCan);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $cArtPvp);

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
    <input type="hidden" id="oParam4" name="oParam4" value="<?php echo $oParam4; ?>">
    <input type="hidden" id="oParam5" name="oParam5" value="<?php echo $oParam5; ?>">
    <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>">
    <!-- CAMPOS DEL FORMULARIO -->
    <!-- FIN CAMPOS DEL FORMULARIO -->
    <script type="text/javascript" src="<?php echo $lcDirBase; ?>Conf/wz_tooltip.js"></script>
    <div class="my-account todo-el-ancho">

        <?php if ($llDivTitulo) { ?>
        <div class="box-title"
             style="display: flex; flex-flow: row wrap;">
            <div style="display: flex: 1 auto;"><h2 class="box-title"><?php echo mb_strtoupper($lcTitPan); ?></h2>
            </div>
            <?php if ($llDivBotones_Sup) { ?>
            <div id="dvBotones" style="flex: 1 auto;">
                <div style="height: 100%; display: flex; flex-flow: row wrap; justify-content: flex-end;">
                    <?php if (!$llPintaFiltroBusqueda) { ?>
                    <i class="fa fa-file-excel-o fa-icono-header"
                       title="Exportar a excel el listado que actualmente tiene en pantalla"
                       onClick="fAccion('frmDescarga','<?php echo $lcNomPageBus; ?>','EX');"></i>
                    <?php } ?>
                    <i class="fa fa-question fa-icono-header" style="cursor: none"
                       title=""
                       onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
                    <?php if (!$llPintaFiltroBusqueda) { ?>
                    <i class="fa fa-arrow-left fa-icono-header"
                       title="Volver a la página anterior"
                       onClick="fAccion('','<?php echo $lcNomPageBus; ?>','');"></i>
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
                <tr>
                    <td height="10"></td>
                </tr>
                <?php if ($llBuscarCliente) { ?>
                <tr>
                    <td width="30%" align="center" valign="middle"><span class="intranet-Text">SOCIO</span></td>
                    <td width="70%"><input name="oSocio"
                                           type="text"
                                           title=""
                                           id="oSocio"
                                           tabindex="1"
                                           value="<?php echo $oSocio; ?>"
                                           maxlength="10"
                                           onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                                           style="width:70px" /></td>
                </tr>
                <?php } ?>
                <?php if ($llPintarSucursal) { ?>
                <tr>
                    <td width="30%" align="center" valign="middle"><span class="intranet-Text">SUCURSAL</span></td>
                    <td width="70%"><input name="oSucursal"
                                           type="text"
                                           title=""
                                           id="oSucursal"
                                           tabindex="2"
                                           value="<?php echo $oSucursal; ?>"
                                           maxlength="10"
                                           onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                                           style="width:70px" /></td>
                </tr>
                <?php } ?>
                <tr>
                    <td width="30%" align="center" valign="middle"><span class="intranet-Text">ALBARAN COMAFE</span></td>
                    <td width="70%"><input name="oAlbaran"
                                           type="text"
                                           title=""
                                           id="oAlbaran"
                                           tabindex="3"
                                           value="<?php echo $oAlbaran; ?>"
                                           maxlength="20"
                                           onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                                           style="width:140px" /></td>
                </tr>
                <tr>
                    <td width="30%" align="center" valign="middle"><span class="intranet-Text">CODIGO DE ARTICULO</span>
                    </td>
                    <td width="70%"><input name="oCodigo"
                                           type="text"
                                           title=""
                                           id="oCodigo"
                                           tabindex="4"
                                           value="<?php echo $oCodigo; ?>"
                                           maxlength="10"
                                           onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                                           style="width:80px" /></td>
                </tr>
                <tr>
                    <td align="center" valign="middle"><span class="intranet-Text">DESCRIPCION</span></td>
                    <td><input name="oDescrip"
                               type="text"
                               title=""
                               id="oDescrip"
                               tabindex="5"
                               value="<?php echo $oDescrip; ?>"
                               size="40"
                               maxlength="80"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                               style="width:200px" />&nbsp;&nbsp;&nbsp;<span style="font-size:11px; color:black">Hace falta poner asteriscos</span>
                    </td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">CODIGO PROVEEDOR</span></td>
                    <td><input name="oProveedor"
                               type="text"
                               title=""
                               id="oProveedor"
                               tabindex="6"
                               value="<?php echo $oProveedor; ?>"
                               size="6"
                               maxlength="6"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                               style="width:60px" /></td>
                </tr>
                <tr>
                    <td align="center"><span class="intranet-Text">FECHA</span></td>
                    <td><input name="oFecDes"
                               type="text"
                               title=""
                               id="oFecDes"
                               tabindex="7"
                               value="<?php echo $oFecDes; ?>"
                               size="12"
                               maxlength="12"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                               style="width:80px" />
                        &nbsp;&nbsp;&nbsp;
                        <button id="btnFecDes"><IMG src="<?php echo $Rutas ['Imagenes']; ?>ico_calendario.gif"
                                                    width="15"
                                                    height="15" /></button>
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField : 'oFecDes',   			// id of the input field
                                ifFormat   : '%d/%m/%Y',   		// format of the input field
                                showsTime  : false,        		// will display a time selector
                                button     : 'btnFecDes',			// trigger for the calendar (button ID)
                                singleClick: true,         		// double-click mode
                                step       : 1,         			// show all years in drop-down boxes (instead of every other year as default)
                                weekNumbers: false,
                                firstDay   : 1
                            });
                        </script>
                        &nbsp;&nbsp;&nbsp;
                        <input name="oFecHas"
                               type="text"
                               title=""
                               id="oFecHas"
                               tabindex="5"
                               value="<?php echo $oFecHas; ?>"
                               size="12"
                               maxlength="12"
                               onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageBus; ?>','R')"
                               style="width:80px" />
                        &nbsp;&nbsp;&nbsp;
                        <button id="btnFecHas"><IMG src="<?php echo $Rutas ['Imagenes']; ?>ico_calendario.gif"
                                                    width="15"
                                                    height="15" /></button>
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField : 'oFecHas',   			// id of the input field
                                ifFormat   : '%d/%m/%Y',   		// format of the input field
                                showsTime  : false,        		// will display a time selector
                                button     : 'btnFecHas',			// trigger for the calendar (button ID)
                                singleClick: true,         		// double-click mode
                                step       : 1,         			// show all years in drop-down boxes (instead of every other year as default)
                                weekNumbers: false,
                                firstDay   : 1
                            });
                        </script>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>


        <?php if ($llPintaRestultadoBusqueda) { ?>

        <br />
        <input type="hidden" id="oSocio" name="oSocio" value="<?php echo $oSocio; ?>" />
        <input type="hidden" id="oSucursal" name="oSucursal" value="<?php echo $oSucursal; ?>" />
        <input type="hidden" id="oAlbaran" name="oAlbaran" value="<?php echo $oAlbaran; ?>" />
        <input type="hidden" id="oCodigo" name="oCodigo" value="<?php echo $oCodigo; ?>" />
        <input type="hidden" id="oDescrip" name="oDescrip" value="<?php echo $oDescrip; ?>" />
        <input type="hidden" id="oProveedor" name="oProveedor" value="<?php echo $oProveedor; ?>" />
        <input type="hidden" id="oMarca" name="oMarca" value="<?php echo $oMarca; ?>" />
        <input type="hidden" id="oRef" name="oRef" value="<?php echo $oRef; ?>" />
        <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>" />
        <input type="hidden" id="oFecDes" name="oFecDes" value="<?php echo $oFecDes; ?>" />
        <input type="hidden" id="oFecHas" name="oFecHas" value="<?php echo $oFecHas; ?>" />

        <?php
        $lnCon    = 0;
        $lnTotAcu = 0;

        //+Si pinto la sucursal, cojo el valor de este campo
        $lSuc = $UserSuc;
        if ($llPintarSucursal) {
            $lSuc = $oSucursal;
        }
        //-

        if (App::getInstancia()->esFerrcash()) {
            $oArts = new WebClientesAven();
        } else {
            $oArts = new WebSociosAven();
        }
        $oArts->depurarObjeto($llDepurar);
        $lWhe = "";
        if ($lcBuscar_Soc <> '') {
            $lWhe .= "alb.CDCLIEN = $lcBuscar_Soc";
        } else {
            $lWhe .= "1=2";
        }
        if ($lSuc <> '') {
            $lWhe .= " AND alb.CDSUCUR = $lSuc";
        }
        if ($oProveedor <> '') {
            $oProveedorNumerico = (int) $oProveedor;
            $lWhe               .= " AND art.CDPROVE = $oProveedorNumerico";
        }
        if ($oCodigo <> '') {
            $lWhe .= " AND alb.CDARTI = $oCodigo";
        }
        if ($oDescrip <> '') {
            $lWhe .= " AND alb.DESCRIP LIKE '" . str_replace('*', '%', $oDescrip) . "'";
        }
        if ($oAlbaran <> '') {
            $lWhe .= " AND alb.CODIGO = '$oAlbaran'";
        }
        if ($oFecDes <> '') {
            $lFecDes = Iniutils::dmyTOymd($oFecDes, '-');
            $lWhe    .= " AND date_format (alb.fecha, '%Y-%m-%d') >= '{$lFecDes}'";
        }
        if ($oFecHas <> '') {
            $lFecHas = Iniutils::dmyTOymd($oFecHas, '-');
            $lWhe    .= " AND date_format (alb.fecha, '%Y-%m-%d') <= '{$lFecHas}'";
        }
        $lOrd    = "alb.CDARTI";
        $arrRegs = $oArts->getLineas(Conexion::getInstancia(), $lWhe, $lOrd);

        // Paginación (se utilizan los parámetros Param4 y Param5).
        $paginaInicial  = 0;
        $totalRegistros = 0;
        $totalRegistros = count($arrRegs);
        $hayPaginacion  = $totalRegistros > 0;

        if ($hayPaginacion) {
        $totalPaginas = (int) ($totalRegistros / $registrosPorPagina);

        Iniutils::escribeEnLog(
            __FILE__ . ": RegsPagina: {$registrosPorPagina} / Pagina Act: {$oParam5} / PagintaTotales: {$totalPaginas} / Registro Actual: {$oParam4} / RegistroTotales: {$totalRegistros}",
            $llDepurar
        );

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
            $arrRegs = $oArts->getLineas(Conexion::getInstancia(), $lWhe, $lOrd, $lLimit);
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
                <div class="tr-flex th-flex"
                     style="color: #ffffff; background-color: <?php echo "#{$colorCorporativo}"; ?>">
                    <div class="td-flex" style="flex:2; justify-content: center">ALBARAN</div>
                    <div class="td-flex" style="justify-content: center">SUC.</div>
                    <div class="td-flex" style="flex-grow: 2; justify-content: center">FECHA ALBARAN</div>
                    <div class="td-flex" style="justify-content: flex-start">ALMACEN</div>
                    <div class="td-flex" style="justify-content: flex-start">ARTICULO</div>
                    <div class="td-flex" style="flex-grow: 4; justify-content: flex-start">DESCRIPCION</div>
                    <div class="td-flex" style="justify-content: flex-end">CANTIDAD</div>
                    <div class="td-flex" style="justify-content: flex-end">PRECIO</div>
                </div>
                <?php
                $lnAcuBas = 0;
                $lnAcuIva = 0;
                $lnAcuTot = 0;

                foreach ($arrRegs as $aLineaAlbaran) {

                $lnCon++;

                $cAlbCod = $aLineaAlbaran ['CODIGO'];
                $cAlbAlm = $aLineaAlbaran ['ALMACEN'];
                $cAlbCli = $aLineaAlbaran ['CDCLIEN'];
                $cAlbSuc = $aLineaAlbaran ['CDSUCUR'];
                $cAlbFec = Iniutils::ymdTOdmy($aLineaAlbaran ['FECHA'], '/');
                $cArtCod = $aLineaAlbaran ['CDARTI'];
                $cArtDes = $aLineaAlbaran ['DESCRIP'];
                $cArtCan = $aLineaAlbaran ['CANTIDAD'];
                $cArtPvp = $aLineaAlbaran ['PRECIO'];

                $lcCantidad = number_format($cArtCan, 2, ',', '.');
                $lcPrecio   = number_format($cArtPvp, 4, ',', '.');

                $llPintarLinea = true;
                $lcColorLetra  = '';

                if ($llPintarLinea) {
                ?>
                <div class="tr-flex">
                    <div class="td-flex <?php echo $lcColorLetra; ?> texto-con-saltos"
                         style="flex: 2;justify-content: center"><?php echo $cAlbCod; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>"
                         style="justify-content: center"><?php echo "{$cAlbCli}/{$cAlbSuc}"; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>"
                         style="flex-grow: 2; justify-content: center"><?php echo $cAlbFec; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>"
                         style="justify-content: flex-start"><?php echo $cAlbAlm; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>"
                         style="justify-content: flex-start"><?php echo $cArtCod; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?> texto-con-saltos"
                         style="flex-grow: 4; justify-content: flex-start"><?php echo $cArtDes; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>"
                         style="justify-content: flex-end"><?php echo $lcCantidad; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>"
                         style="justify-content: flex-end"><?php echo $lcPrecio; ?></div>
                </div>

                <?php
                }
                }
                ?>

                <div class="tr-flex">
                    <div class="td-flex intranet-FilaTotal" style="flex-grow: 2;">
                        <div class="td-flex"
                             style="justify-content: flex-start"><?php echo "{$lnCon} de {$totalRegistros} registros"; ?></div>
                    </div>
                </div>

            </div>

        </div>
        <?php } ?>

        <?php if ($lcMensajeProceso <> '') { ?>
        <br />
        <div id="dvMensajes" style="height:40px" align="center">
            <span style="alignment: center; color: red"><?php echo $lcMensajeProceso; ?></span>
        </div>
        <?php } ?>

        <?php if ($llDivBotones_Inf) { ?>
        <br />
        <div id="dvBotones" style="text-align:center" class="botonera">
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
