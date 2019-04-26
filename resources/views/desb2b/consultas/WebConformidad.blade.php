<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase        = '../../';                                       // COMO IR AL DIRECTORIO BASE
$llDepurar        = false;                                          // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan         = 'Consulta de conformidades';                    // TITULO DE LA PAGINA
$llExpExcel       = false;                                          // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc         = 'LISTA_DIRECTOS.xls';                           // NOMBRE DEL FICHERO EXCEL A GENERAR
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
$lcEmailCliente     = '';
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
$lnStatus                 = 0;                      // 1 o 0 o -1 o -2 o -3
$llValidarUsuario         = true;
$llValidar_API            = true;
$llSoloUserDepurador      = false;
$llValidar_TiendaFerrokey = false;

include_once $lcDirBase . 'jed_valuso.php';

$llDepurar = $laDatosClienteConf['ADM_INFOR_DBG'];                // VERIFICAR SI EL USUARIO ES DEPURADOR
//-VALIDACION ACCESOS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////                           PAGINA VARIABLE                                  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//+PAGINAS RESPUESTAS
$lcNomPageAcc = $Rutas['Base_Intranet_Ww'] . 'Socios/Movs/dir_Lista.php';
$lcNomPageBus = $lcNomPageAcc;
$lcNomPageExc = $lcNomPageAcc;
$lcNomPageAct = $Rutas['Base_Intranet_Ww'] . 'Prov/comafe_Solicitud_Gestion.php';
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
//Validacion variables formulario
$oAlbDes     = Helper::post('oAlbDes');
$oAlbHas     = Helper::post('oAlbHas');
$oAlbCli     = Helper::post('oAlbCli');
$oAlbSuc     = Helper::post('oAlbSuc');
$oFecDes     = Helper::post('oFecDes');
$oFecHas     = Helper::post('oFecHas');
$oCodPro     = Helper::post('oCodPro');
$oArtCod     = Helper::post('oArtCod');
$oCodCba     = Helper::post('oCodCba');
$oFilDoc     = Helper::post('oFilDoc');
//-FIN CAPTURAR VARIABLES FORMULARIO


//+CONFIGURACION APLICACION
$registrosPorPagina = (int) Helper::getNotEmptyFromArray($rowConfEmp, 'numRegsList', 50);
//-CONFIGURACION APLICACION


//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Grupo      = '';
$lcLog_Texto      = '';
//VALIDAR FECHAS
if ($oFecDes == '') {
    $oFecDes = '01' . date('/m/Y');
}
if ($oFecHas == '') {
    $oFecHas = date('d/m/Y');
}
//REVISAR SI PINTO SUCURSALES
if (($laDatosCliente['TIPO_CLIENTE'] == 'EMPLEADO')){
    $lcBuscar_Soc = $oAlbCli;
    $lcBuscar_Suc = $oAlbSuc;
} else {
    $lcBuscar_Soc = $laDatosCliente ['COMPRA_SOC'];
    $lcBuscar_Suc = $laDatosCliente ['COMPRA_SUC'];
}
//$lcBuscar_Soc = $laDatosCliente ['COMPRA_SOC'];
//$lcBuscar_Suc = $laDatosCliente ['COMPRA_SUC'];
//CARGAR EN UN ARRAY LA CONFIGURACION DEL USUARIO
$laArrConf        = fDatosTerceroConf(
    $connection,
    $DBTableNames['Socios_Conf'],
    $pgIdentificadorEmpresa,
    $lcBuscar_Soc,
    $lcBuscar_Suc,
    $lnIdUsuMagento
);
$llPintarSucursal = false;
if ($laArrConf ['IND_AGR_SUC_CONS'] == 1) {
    $llPintarSucursal = true;
}
//+Si pinto la sucursal, cojo el valor de este campo
if ($llPintarSucursal) {
    $lcBuscar_Suc = $oAlbSuc;
}
//-
//POR DEFECTO LA BUSQUEDA DE LAS PENDIENTES
if ($oFilDoc == '') {
    $oFilDoc = 'PD';
}
//PARA EMPLEADOS
$llPintarCliente = false;
if ($laDatosCliente['TIPO_CLIENTE'] == 'EMPLEADO') {
    $llPintarCliente = true;
}
if ($laArrConf['IND_USER_DATMIN'] == 1) {
    $llPintarCliente = true;
    $lcBuscar_Soc    = (int)$oAlbCli;
}
if ($laArrConf['IND_SOLO_TARICAT'] == 1) {
    $llPintarCliente = true;
    if ($oAccion == "B") {
        if ($oAlbCli == '') {
            $oAccion = "";
            $lcMensajeProceso = "ES OBLIGATORIO PONER UN CODIGO DE CLIENTE";
        } else {
            //Por defecto ERROR
            $oAccion = "";
            $lcMensajeProceso = "SOLO PARA CLIENTES ASIGNADOS";
            //Validar que el cliente que busco es TARICAT
            if ($oAlbSuc == '') {  $oAlbSuc = 1; }
            $oSocio = new WebSociosSucs();
            $oSocio->depurarObjeto($llDepurar);
            $oSocio->cdclien($oAlbCli);
            $oSocio->cdsucur($oAlbSuc);
            if ($oSocio->obtenerPorId(Conexion::getInstancia())) {
                if (in_array($oSocio->tipoCliente(), ['TARICAT', 'TARIBAL']) ) {
                    $oAccion = "B";
                    $lcMensajeProceso = "";
                }
            }
        }
    }
}
//BUSCAR DIRECTOS
if ($oAccion == "B") {
    $lcLog_Texto = "MOVS - CONFs (Lista): {$oFecDes} al {$oFecHas}";
}
//-ACCIONES


//+LOG
AccesoDatos::grabarLog(Conexion::getInstancia(), $pgIdentificadorEmpresa, $UserID, $UserSuc, $lcLog_Grupo, $lcLog_Texto, $lnIdUsuMagento);
//-LOG


//+CONFIGURACION CONTENIDO
$lcAyuTex           = '<B><U>CONSULTA DE SOLICITUDES / PEDIDOS DIRECTOS</U></B>.<BR />';
$lcAyuTex          .= '* Desde esta pantalla puede consultar el estado de las solicitudes.<BR /><BR />';
$lcAyuTex          .= '<B>* ESTADOS:</B><BR />';
$lcAyuTex          .= '- SOLICITUD: queda pendiente validarla por el socio. Utiliza el boton situado a la derecha, para aprobar/rechazar la solicitud.<BR />';
$lcAyuTex          .= '- PENDIENTE: esta pendiente por parte de la Cooperativa, la aprobacion de la  misma.<BR />';
$lcAyuTex          .= '- PROVEEDOR: pendiente de aceptar por el proveedor (Pedidos de Compra directa).<BR />';
$lcAyuTex          .= '- APROBADA: la solicitud ya tiene Nº.Conformidad.<BR />';
$lcAyuTex          .= '- RECHAZADA: esta solicitud fue rechazada.<BR />';
$lcAyudaPantalla_P  = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco       = "document.getElementById('oBusCod').focus()";
$lcObjetoFoco       = "";
$llDivTitulo        = true;
$llDivExplicacion   = false;
$llDivBotones_Sup   = false;
$llDivBotones_Inf   = true;
$llDivPie           = false;
$llDivHiddenDown    = false;
//-CONFIGURACION CONTENIDO

//Juego de capas, para mostrar la correcta
$llPintar_Busqueda = true;
$llPintar_Lista    = false;
if ($oAccion == 'B') {
    $llPintar_Busqueda = false;
}
$llPintar_Lista    = ! $llPintar_Busqueda ;
$llDivBotones_Inf  = ! $llPintar_Lista;
$llDivBotones_Sup  = ! $llDivBotones_Inf;
if ($llPintar_Busqueda) {
    $oParam4 = 0;
    $oParam5 = 0;
}

//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();

//Ver si es un proveedor
$llPintarProveedor = true;
if ($laDatosCliente['TIPO_CLIENTE'] == 'PROVEEDOR') {
    $llPintarProveedor = false;
    $llPintarCliente   = true;
    $lcBuscar_Soc      = $oAlbCli;
    $lcBuscar_Suc      = "";
    $oCodPro           = $UserID;
}

if ($llExpExcel) {

    //Sobre el fichero
    $lcNombrePestana = 'LINEAS';
    //Cabeceras informe
    $cabeceras = [
        'CONFORMIDAD',
        'SOCIO',
        'SUCURSAL',
        'FECHA',
        'FIN VALIDEZ',
        'PEDIDO',
        'PROVEEDOR',
        'ENTREGA EN',
        'ESTADO',
        'TOTAL',
    ];
    //Datos
    $oConformidad = new WebConformidad();
    $lWhere = $oConformidad->getWhere($pgIdentificadorEmpresa, $oAlbDes, $oAlbHas, $oCodPro, $lcBuscar_Soc, $lcBuscar_Suc, $oFecDes, $oFecHas, $oFilDoc);
    $lOrden = "FECHA DESC, NCONFORMIDAD DESC";
    $lLimit = "";

    $arrConformidades = $oConformidad->obtenerRegistros(Conexion::getInstancia(), $lWhere, $lOrden, $lLimit);

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

    foreach ($arrConformidades as $aDatos) {

        $aConformidad = new WebConformidad($aDatos);

        $lnCon++;
        $columna = 'A';

        $lProveedorNombre = "";
        $oProveedor = new WebProveedor();
        $oProveedor->cdprove($aConformidad->cdprove());
        if ($oProveedor->obtenerPorId(Conexion::getInstancia())) {
            $lProveedorNombre = $oProveedor->razon();
        }

        $lId      = $aConformidad->nmconfor();
        $lcNumero = $aConformidad->nconformidad();
        $lcSolFec = Iniutils::ymdTOdmy($aConformidad->fecha(), '/');
        $lcSolFin = "";
        if (! empty($aConformidad->fecFinVal())) {
            $lcSolFin = Iniutils::ymdTOdmy($aConformidad->fecFinVal(), '/');
        }
        $lcSolCli = "{$aConformidad->cdclien()}";
        $lcSolSuc = "{$aConformidad->cdsucur()}";
        $lcSolPro = "{$aConformidad->cdprove()} {$lProveedorNombre}";
        $lnSolImp = $aConformidad->importe();
        $lcSolPed = $aConformidad->pedido();
        $lcSolObs = $aConformidad->obser();
        $lcSolEnt = $aConformidad->DevolverDescripcion_LugarEntrega();
        $lcEstado = $aConformidad->DevolverDescripcion_Estado();

        $llPintarLinea = true;
        if ($aConformidad->indBorrado() == 1) {
            $llPintarLinea = false;
        } else {
            if ($aConformidad->indAprov() == 1) {
                if ($laDatosCliente['TIPO_CLIENTE'] <> 'SOCIO') {
                    $llPintarLinea = false;
                }
            }
        }

        if ($llPintarLinea) {
            $hojaActiva->setCellValue($columna++ . $fila, $lcNumero);
            $hojaActiva->setCellValue($columna++ . $fila, $lcSolCli);
            $hojaActiva->setCellValue($columna++ . $fila, $lcSolSuc);
            $hojaActiva->setCellValue($columna++ . $fila, $lcSolFec);
            $hojaActiva->setCellValue($columna++ . $fila, $lcSolFin);
            $hojaActiva->setCellValue($columna++ . $fila, $lcSolPed);
            $hojaActiva->setCellValue($columna++ . $fila, $lcSolPro);
            $hojaActiva->setCellValue($columna++ . $fila, $lcSolEnt);
            $hojaActiva->setCellValue($columna++ . $fila, $lcEstado);
            $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
            $hojaActiva->setCellValue($columna++ . $fila, $lnSolImp);
            //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lArt_Est, PHPExcel_Cell_DataType::TYPE_STRING);
            //$hojaActiva->setCellValueExplicit($columna++ . $fila, $lcRefProv, PHPExcel_Cell_DataType::TYPE_STRING);

            $fila++;
        }


    }

    $writer = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    $writer->save('php://output');


} else {
?>

<body>

<form id="frmDatos" name="frmDatos" method="post" action="">
    <INPUT type="hidden" id="pSession"    name="pSession"    value="<?php echo $pSession; ?>">
    <INPUT type="hidden" id="pUrl"        name="pUrl"        value="">
    <INPUT type="hidden" id="oDetalle"    name="oDetalle"    value="">
    <INPUT type="hidden" id="oAccion"     name="oAccion"     value="">
    <INPUT type="hidden" id="oParam1"     name="oParam1"     value="">
    <INPUT type="hidden" id="oParam2"     name="oParam2"     value="">
    <INPUT type="hidden" id="oParam3"     name="oParam3"     value="">
    <INPUT type="hidden" id="oParam4"     name="oParam4"     value="<?php echo $oParam4; ?>">
    <INPUT type="hidden" id="oParam5"     name="oParam5"     value="<?php echo $oParam5; ?>">


    <INPUT type="hidden" id="oPantallaV"  name="oPantallaV"  value="LIS">
    <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>" />
    <script type="text/javascript" src="<?php echo $lcDirBase; ?>Conf/wz_tooltip.js"></script>

    <div class="my-account todo-el-ancho">

        <?php if ($llDivTitulo) { ?>
        <div class="box-title"
             style="display: flex; flex-flow: row wrap;">
            <div style="display: flex: 1 auto;"><h2 class="box-title"><?php echo mb_strtoupper($lcTitPan); ?></h2></div>
            <?php if ($llDivBotones_Sup) { ?>
            <div id="dvBotones" style="flex: 1 auto;">
                <div style="height: 100%; display: flex; flex-flow: row wrap; justify-content: flex-end;">
                    <?php if ($llPintar_Busqueda) { ?>
                    <i class="fa fa-search fa-icono-header"
                       title="Realizar busqueda"
                       onClick="fAccion ('', '<?php echo $lcNomPageBus; ?>', 'B');">
                        <?php } ?>
                        <?php if ($llPintar_Lista) { ?>
                        <i class="fa fa-file-excel-o fa-icono-header"
                           title="Exportar a excel el listado que actualmente tiene en pantalla"
                           onClick="fAccion('frmDescarga','<?php echo $lcNomPageExc; ?>','EX');"></i>
                        <i class="fa fa-question fa-icono-header" style="cursor: none"
                           title=""
                           onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
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


        <?php if ($llPintar_Busqueda) { ?>
        <br />
        <!--<div id="dvContenido" style="OVERFLOW: auto; WIDTH: 1000px; HEIGHT: 500px">-->
        <div id="dvContenido">
            <table width="1000" border="0">
                <tr>
                    <td align="center">
                        <table width="90%" border="0">
                            <tr>
                                <td width="40%"><span class="intranet-Text">NUMERO DE CONFORMIDAD</span></td>
                                <td width="30%" align="left"><input name="oAlbDes"
                                                                    type="text"
                                                                    title=""
                                                                    tabindex="1"
                                                                    size="10"
                                                                    maxlength="10"
                                                                    value="<?php echo $oAlbDes; ?>"
                                                                    style="WIDTH: 100px" /></td>
                                <td width="30%" align="left"><input name="oAlbHas"
                                                                    type="text"
                                                                    title=""
                                                                    tabindex="2"
                                                                    size="10"
                                                                    maxlength="10"
                                                                    value="<?php echo $oAlbHas; ?>"
                                                                    style="WIDTH: 100px" /></td>
                            </tr>
                            <tr>
                                <td class="letra_sec"><span class="intranet-Text">FECHA DE CONFORMIDAD</span></td>
                                <td align="left"><input id="oFecDes"
                                                        name="oFecDes"
                                                        type="text"
                                                        title=""
                                                        tabindex="3"
                                                        size="10"
                                                        maxlength="10"
                                                        value="<?php echo $oFecDes; ?>"
                                                        style="WIDTH: 80px" />
                                    &nbsp;
                                    <button id="btnFecDes"><IMG src="<?php echo $Rutas ['Imagenes']; ?>ico_calendario.gif"
                                                                width="15"
                                                                height="15" /></button>
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            inputField : "oFecDes",   			// id of the input field
                                            ifFormat   : "%d/%m/%Y",   		// format of the input field
                                            showsTime  : false,        		// will display a time selector
                                            button     : "btnFecDes",			// trigger for the calendar (button ID)
                                            singleClick: true,         		// double-click mode
                                            step       : 1,         			// show all years in drop-down boxes (instead of every other year as default)
                                            weekNumbers: false,
                                            firstDay   : 1
                                        });
                                    </script>
                                </td>
                                <td align="left"><input id="oFecHas"
                                                        name="oFecHas"
                                                        type="text"
                                                        title=""
                                                        tabindex="4"
                                                        size="10"
                                                        maxlength="10"
                                                        value="<?php echo $oFecHas; ?>"
                                                        style="WIDTH: 80px" />
                                    &nbsp;
                                    <button id="btnFecHas"><IMG src="<?php echo $Rutas ['Imagenes']; ?>ico_calendario.gif"
                                                                width="15"
                                                                height="15" /></button>
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            inputField : "oFecHas",   			// id of the input field
                                            ifFormat   : "%d/%m/%Y",   		// format of the input field
                                            showsTime  : false,        		// will display a time selector
                                            button     : "btnFecHas",			// trigger for the calendar (button ID)
                                            singleClick: true,         		// double-click mode
                                            step       : 1,         			// show all years in drop-down boxes (instead of every other year as default)
                                            weekNumbers: false,
                                            firstDay   : 1
                                        });
                                    </script>
                                </td>
                            </tr>
                            <?php if ($llPintarProveedor) { ?>
                            <tr>
                                <td><span class="intranet-Text">CODIGO DE PROVEEDOR</span></td>
                                <td colspan="2" align="left">
                                    <input type="text"
                                           title=""
                                           id="oCodPro"
                                           name="oCodPro"
                                           size="6"
                                           maxlength="6"
                                           value="<?php echo $oCodPro; ?>"
                                           style="WIDTH: 60px" />
                                </td>
                            </tr>
                            <?php } else { ?>
                            <input type="hidden" id="oCodPro" name="oCodPro" value="<?php echo $oCodPro; ?>" />
                            <?php } ?>
                            <?php if ($llPintarCliente) { ?>
                            <tr>
                                <td><span class="intranet-Text">CLIENTE</span></td>
                                <td colspan="2" align="left">
                                    <input type="text"
                                           title=""
                                           id="oAlbCli"
                                           name="oAlbCli"
                                           size="8"
                                           maxlength="8"
                                           value="<?php echo $oAlbCli; ?>"
                                           style="WIDTH: 60px" />
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if ($llPintarSucursal) { ?>
                            <tr>
                                <td><span class="intranet-Text">SUCURSAL</span></td>
                                <td colspan="2" align="left">
                                    <input type="text"
                                           title=""
                                           id="oAlbSuc"
                                           name="oAlbSuc"
                                           size="3"
                                           maxlength="3"
                                           value="<?php echo $oAlbSuc; ?>"
                                           style="WIDTH: 40px" />
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td><span class="intranet-Text">ESTADO CONFORMIDAD</span></td>
                                <td align="left">
                                    <select name="oFilDoc" class="text_edi" id="oFilDoc" style="width: 225px" title="">
                                        <option value="T"<?php if ($oFilDoc == "T") {
                                            print ' SELECTED';
                                        } ?>>TODAS</option>
                                        <option value="SP"<?php if ($oFilDoc == "SP") {
                                            print ' SELECTED';
                                        } ?>>SOLICITUDES PENDIENTES
                                        </option>
                                        <option value="PD"<?php if ($oFilDoc == "PD") {
                                            print ' SELECTED';
                                        } ?>>APROBADAS
                                        </option>
                                        <option value="IN"<?php if ($oFilDoc == "IN") {
                                            print ' SELECTED';
                                        } ?>>INCIDENCIAS</option>
                                        <option value="RE"<?php if ($oFilDoc == "RE") {
                                            print ' SELECTED';
                                        } ?>>RECHAZADAS
                                        </option>
                                    </select>
                                </td>
                                <td align="left"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>


        <?php if ($llPintar_Lista) { ?>

        <br />
        <input type="hidden" id="oAlbDes" name="oAlbDes" value="<?php echo $oAlbDes; ?>" />
        <input type="hidden" id="oAlbHas" name="oAlbHas" value="<?php echo $oAlbHas; ?>" />
        <input type="hidden" id="oAlbCli" name="oAlbCli" value="<?php echo $oAlbCli; ?>" />
        <input type="hidden" id="oAlbSuc" name="oAlbSuc" value="<?php echo $oAlbSuc; ?>" />
        <input type="hidden" id="oFecDes" name="oFecDes" value="<?php echo $oFecDes; ?>" />
        <input type="hidden" id="oFecHas" name="oFecHas" value="<?php echo $oFecHas; ?>" />
        <input type="hidden" id="oCodPro" name="oCodPro" value="<?php echo $oCodPro; ?>" />
        <input type="hidden" id="oFilDoc" name="oFilDoc" value="<?php echo $oFilDoc; ?>" />

        <?php
        $lnCon    = 0;
        $lnTotAcu = 0;

        $oConformidades = new WebConformidad();
        $oConformidades->depurarObjeto($llDepurar);
        $lWhere   = $oConformidades->getWhere($pgIdentificadorEmpresa, $oAlbDes, $oAlbHas, $oCodPro, $lcBuscar_Soc, $lcBuscar_Suc, $oFecDes, $oFecHas, $oFilDoc);
        $lOrden   = "FECHA DESC, NCONFORMIDAD DESC";
        $lLimit   = "";

        $arrLista = $oConformidades->obtenerRegistros(Conexion::getInstancia(), $lWhere, $lOrden);

        // Paginación (se utilizan los parámetros Param4 y Param5).
        $paginaInicial  = 0;
        $totalRegistros = count($arrLista);
        $hayPaginacion  = $totalRegistros > 0;

        if ($hayPaginacion) {
        $totalPaginas = (int) ($totalRegistros / $registrosPorPagina);

        Iniutils::escribeEnLog(
            "RegsPagina: {$registrosPorPagina} / Pagina Act: {$oParam5} / PagintaTotales: {$totalPaginas} / Registro Actual: {$oParam4} / RegistroTotales: {$totalRegistros}", $llDepurar);

        if ($totalRegistros % $registrosPorPagina <> 0) {
            $totalPaginas++;
        }

        if ($totalRegistros < $registrosPorPagina || $llExpExcel) {
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

            Iniutils::escribeEnLog("Pagina: {$paginaActual}", $llDepurar);

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
            $arrLista = $oConformidades->obtenerRegistros(Conexion::getInstancia(), $lWhere, $lOrden, $lLimit);

        } ?>
        <div class="table-flex">
            <div class="tr-flex">
                <div class="td-flex" style="justify-content: flex-end">
                    <img src="<?php echo $Rutas['Imagenes']; ?>bInicio.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAcc; ?>','','B', '', '', '', '', '1');"
                         alt="Ir a la primera pagina">
                    <img src="<?php echo $Rutas['Imagenes']; ?>bAtras.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAcc; ?>','','B', '', '', '', '', '<?php echo($paginaActual - 1); ?>');"
                         alt="Ir a la pagina anterior">
                    <span style="margin: auto 5px;"><?php echo "Página {$paginaActual} de {$totalPaginas}"; ?></span>
                    <img src="<?php echo $Rutas['Imagenes']; ?>bDelante.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAcc; ?>','','B', '', '', '', '', '<?php echo($paginaActual + 1); ?>');"
                         alt="Ir a la pagina siguiente">
                    <img src="<?php echo $Rutas['Imagenes']; ?>bFinal.gif"
                         class="pondedo"
                         style="margin: auto 5px;"
                         onClick="fAccionMultiple('','<?php echo $lcNomPageAcc; ?>','','B', '', '', '', '', '<?php echo $totalPaginas; ?>');"
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
                    <div class="td-flex" style="flex-grow: 2; justify-content: center">CONFORMIDAD</div>
                    <div class="td-flex" style="justify-content: center">SOCIO</div>
                    <div class="td-flex" style="justify-content: center">SUC.</div>
                    <div class="td-flex" style="justify-content: center">FECHA</div>
                    <div class="td-flex" style="flex-grow: 2; justify-content: center">FIN VALIDEZ</div>
                    <div class="td-flex" style="flex-grow: 2; justify-content: flex-start">PEDIDO</div>
                    <div class="td-flex texto-con-saltos" style="flex-grow: 4; justify-content: flex-start">PROVEEDOR</div>
                    <div class="td-flex texto-con-saltos" style="flex-grow: 2; justify-content: flex-start">ENTREGA EN</div>
                    <div class="td-flex" style="flex-grow: 2; justify-content: flex-start">ESTADO</div>
                    <div class="td-flex" style="justify-content: flex-end">TOTAL</div>
                    <div class="td-flex"> </div>
                </div>
                <?php
                //$totalRegistros = count($arrLista);

                foreach ($arrLista as $aDatos) {

                $aConformidad = new WebConformidad($aDatos);

                $llPintarLinea = true;
                if ($aConformidad->indBorrado() == 1) {
                    $llPintarLinea = false;
                } else {
                    if ($aConformidad->indAprov() == 1) {
                        if ($laDatosCliente['TIPO_CLIENTE'] <> 'SOCIO') {
                            $llPintarLinea = false;
                        }
                    }
                }

                if ($llPintarLinea) {

                $llSolicitudPendienteCliente = false;

                $lnCon++;

                $lProveedorNombre = '';
                $oProveedor = new WebProveedor();
                $oProveedor->cdprove($aConformidad->cdprove());
                if ($oProveedor->obtenerPorId(Conexion::getInstancia())) {
                    $lProveedorNombre = $oProveedor->razon();
                }

                $lClienteNombre = '';
                $aTercero = $aConformidad->empresa() == 'FER' ? new WebClientes() : new WebSociosSucs();
                $aTercero->cdclien($aConformidad->cdclien());
                $aTercero->cdsucur($aConformidad->cdsucur());
                if ($aTercero->obtenerPorId(Conexion::getInstancia())) {
                    $lClienteNombre = $aTercero->nombre();
                }

                $lId      = $aConformidad->nmconfor();
                $lcNumero = $aConformidad->nconformidad();
                $lcSolFec = Iniutils::ymdTOdmy($aConformidad->fecha(), '/');
                $lcSolFin = $aConformidad->fecFinVal();
                if (! empty($lcSolFin) &&($lcSolFin<>'NULL')&&($lcSolFin<>'now()')) {
                    $lcSolFin = Iniutils::ymdTOdmy($aConformidad->fecFinVal(), '/');
                } else {
                    if ($lcSolFin=='now()') { $lcSolFin = ''; }
                }
                $lcSolCli = "{$aConformidad->cdclien()}";
                $lcSolSuc = "{$aConformidad->cdsucur()}";
                $lcSolPro = "{$aConformidad->cdprove()} {$lProveedorNombre}";
                $lnSolImp = $aConformidad->importe();
                $lcSolPed = $aConformidad->pedido();
                $lcSolObs = $aConformidad->obser();

                $lcSolImp = number_format($lnSolImp, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lnTotAcu = $lnTotAcu + $lnSolImp;

                $lcSolEnt = $aConformidad->DevolverDescripcion_LugarEntrega();
                $lcEstado = $aConformidad->DevolverDescripcion_Estado();

                $lcColorLetra = '';
                if ($aConformidad->indRechazada() == 1) {
                    $lcColorLetra = 'intranet-LetraError';
                } else {
                    if ($aConformidad->indBloqueo() == 1) {
                        if ($aConformidad->tipoInc() == 'APR') {
                            if($aConformidad->tipoPedido()<>'PDIR') {
                                $llSolicitudPendienteCliente = true;
                            }

                        }
                    }
                }

                //Fichero adjunto de la conformidad
                $llExisteFichero = false;
                $lcEnlaceFicAdj  = '';
                $lcFicheroAdj    = $aConformidad->ficAdjunto();
                if ($lcFicheroAdj <> '') {
                    if ($aConformidad->aprovPed()>0) {
                        $lTipoDescarga = 'PAACFSE';
                        $lcRutaFichero = $Rutas ['Prov_Directos_Real'];
                    } else {
                        $lTipoDescarga = 'PDPFA';
                        $lcRutaFichero = $Rutas ['Prov_Conf_Real'];
                    }
                    $lcFicheroAdj = $lcRutaFichero.$lcFicheroAdj;
                    Iniutils::escribeEnLog("Fichero adjunto: campo tabla: {$aConformidad->ficAdjunto()} / Fichero ruta: {$lcFicheroAdj} / existe: ".file_exists($lcFicheroAdj)."", $llDepurar);
                    if (file_exists($lcFicheroAdj)) {
                        $llExisteFichero = true;
                        $lcNomFicDown   = 'Fic_Adjunto_Conf.' . Iniutils::fgAveriguaExtencionFic($lcFicheroAdj);
                        $lcEnlaceFicAdj = $Rutas ['Pagina_Descargas'];

                        $lcEnlaceFicAdj .= "?Source={$lTipoDescarga}&pFicNom={$lcNomFicDown}&pFicDes={$aConformidad->ficAdjunto()}";

                    }
                }

                //Si el pedido es aprovicionamiento, cambio el tipo pedido
                if ($aConformidad->aprovPed() > 0) {
                    $lcSolPed = "APROV-{$aConformidad->aprovPed()}";
                    //Le quito para que se pueda descar el fichero asociado
                    if ($laDatosCliente['TIPO_CLIENTE'] <> 'SOCIO') {
                        $llExisteFichero = false;
                    }
                }

                ?>
                <div class="tr-flex">
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="flex-grow: 2; justify-content: space-around"><?php echo $lcNumero; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center"><?php echo $lcSolCli; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center"><?php echo $lcSolSuc; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center"><?php echo $lcSolFec; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="flex-grow: 2; justify-content: center"><?php echo $lcSolFin; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="flex-grow: 2; justify-content: flex-start"><?php echo $lcSolPed; ?></div>
                    <div class="td-flex texto-con-saltos <?php echo $lcColorLetra; ?>" style="flex-grow: 4; justify-content: flex-start"><?php echo $lcSolPro; ?></div>
                    <div class="td-flex texto-con-saltos <?php echo $lcColorLetra; ?>" style="flex-grow: 2; justify-content: flex-start"><?php echo $lcSolEnt; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="flex-grow: 2; justify-content: flex-start"><?php echo $lcEstado; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: flex-end"><?php echo $lcSolImp; ?></div>
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center">
                        <?php if ($llExisteFichero) { ?>
                        <i class="fa fa-download fa-1_5x fa-icono-header pondedo"
                           title="Descargar fichero adjunto"
                           onClick="fAccion('frmDescarga','<?php echo $lcEnlaceFicAdj; ?>','');"></i>
                        <?php } ?>
                        <?php if ($llSolicitudPendienteCliente) { ?>
                        <i class="fa fa-file-o fa-1_5x fa-icono-header pondedo"
                           title="Gestionar solicitud pedido directo"
                           onClick="fAccionDetalle('','<?php echo $lcNomPageAct; ?>', '<?php echo $lId; ?>', 'LIS');"></i>
                        <?php } ?>
                    </div>
                </div>

                <?php
                }
                }
                ?>

                <div class="tr-flex">
                    <div class="td-flex intranet-FilaTotal" style="flex-grow: 2;">
                        <span>&nbsp;<?php echo "{$lnCon} de {$totalRegistros} registros"; ?></span>
                    </div>
                </div>

            </div>

        </div>
        <?php } ?>


        <?php if ($lcMensajeProceso <> '') { ?>
        <br />
        <div id="dvMensajes" style="height:40px">
            <span><?php echo $lcMensajeProceso; ?></span>
        </div>
        <?php } ?>


        <?php if ($llDivBotones_Inf) { ?>
        <br>
        <div id="dvBotonesInf" class="botonera">
            <table width="100%" border="0">
                <tr>
                    <td width="35%" align="center" valign="middle">
                        <button class="button"
                                title="Buscar"
                                type="button"
                                onClick="fAccion('_self','<?php echo $lcNomPageBus; ?>','B')"><span><span>  Buscar  </span></span>
                        </button>
                        </button>
                    </td>
                </tr>
            </table>
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

