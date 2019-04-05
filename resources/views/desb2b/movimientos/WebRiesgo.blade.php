<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase   = '../../';                                       // COMO IR AL DIRECTORIO BASE
$llDepurar   = false;                                          // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan    = 'CONSULTA DE ESTADO RIESGO';                    // TITULO DE LA PAGINA
$llExpExcel  = false;                                          // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc    = 'RIESGO_CONS.xls';                              // NOMBRE DEL FICHERO EXCEL A GENERAR
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
global $UserName;
global $UserEmail_Gen;
global $pIdMagento;
global $rowConfEmp;
global $laDatosCliente;
global $laDatosClienteConf;
global $glServidorDevelop;
//Variables de pagina
$lcEmailCliente = '';
$Rutas          = [];
$lcEnl          = '';
$lUserID        = '';
$lUserSuc       = '';
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
$llVistaEspecial          = false;

$pLinkOut = Helper::get('pPwLk');
if (!empty($pLinkOut)) {

    $llVistaEspecial = true;

    $pDecry = Iniutils::fgDesEncripta($pLinkOut, $pgIdRaizFerretera);
    //
    $lnIni          = strpos($pDecry, '-');
    $lcCacho        = substr($pDecry, $lnIni + 1);
    $lnFin          = strpos($lcCacho, '-');
    $lnIdUsuMagento = substr($lcCacho, 0, $lnFin);
    $lcCacho        = substr($lcCacho, $lnFin + 1);
    //
    $lnIni                  = strpos($lcCacho, '-');
    $lcCacho                = substr($lcCacho, $lnIni + 1);
    $lnFin                  = strpos($lcCacho, '-');
    $pgIdentificadorEmpresa = substr($lcCacho, 0, $lnFin);
    $lcCacho                = substr($lcCacho, $lnFin + 1);
    //
    $lnIni   = strpos($lcCacho, '-');
    $lcCacho = substr($lcCacho, $lnIni + 1);
    $lnFin   = strpos($lcCacho, '-');
    $lUserID  = substr($lcCacho, 0, $lnFin);
    $lcCacho = substr($lcCacho, $lnFin + 1);
    //
    $lnIni   = strpos($lcCacho, '-');
    $lcCacho = substr($lcCacho, $lnIni + 1);
    $lnFin   = strpos($lcCacho, '-');
    $lUserSuc = substr($lcCacho, 0, $lnFin);
    $lcCacho = substr($lcCacho, $lnFin + 1);

    //Iniutils::escribeEnLog("Vista especial: Empresa: {$pgIdentificadorEmpresa} / lUserId: {$lUserID} / lUserSuc: {$lUserSuc} / IdMagento: {$lnIdUsuMagento}", true);

}

include_once $lcDirBase . 'jed_valuso.php';

$llDepurar = $laDatosClienteConf['ADM_INFOR_DBG'];                // VERIFICAR SI EL USUARIO ES DEPURADOR
//-VALIDACION ACCESOS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////                           PAGINA VARIABLE                                  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//+PAGINAS RESPUESTAS
$lcNomPageAct = $Rutas['Base_Intranet_Ww'] . 'Socios/Movs/rie_Vista.php';
$lcNomPageDwn = $lcNomPageAct;
$lcNomPageExc = $lcNomPageAct;
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
//Validacion variables formulario
$oCliEsp  = Helper::post('oCliEsp');
//-FIN CAPTURAR VARIABLES FORMULARIO

$lcBuscar_Soc = Helper::getFromArray($laDatosCliente, 'COMPRA_SOC');
if (empty($lcBuscar_Soc) || (int) $lcBuscar_Soc == 0) {
    $lcBuscar_Soc = $UserID;
}
$lcBuscar_Suc = Helper::getFromArray($laDatosCliente, 'COMPRA_SUC');
if (empty($lcBuscar_Suc)) {
    $lcBuscar_Suc = $UserSuc;
}
//Si estoy en modo vista especial cojo el cliente del LINK
if ($llVistaEspecial) {
    $lcBuscar_Soc = $lUserID;
    $lcBuscar_Suc = $lUserSuc;
} else {
    //Cojo el cliente especial
    if (!empty($oCliEsp)) {
        $lcBuscar_Soc = $oCliEsp;
    }
}
//-FIN CAPTURAR VARIABLES FORMULARIO

//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Grupo      = 'SOCIOS';
$lcLog_Texto      = "MOVS - RIESGO ( {$oAccion} / {$lcBuscar_Soc} )";

// VER SI HAY POSIBILIDAR DE BUSCAR EL RIESGO DE UN DETERMINADO CLIENTE.
$esEmpleado      = Helper::getFromArray($laDatosCliente, 'TIPO_CLIENTE') === 'EMPLEADO';
$esAdministrador = $laDatosClienteConf['IND_USER_DATMIN'] == 1;
$llBuscarCliente = $esEmpleado || $esAdministrador;

//+(06/11/2016)
//Revisar si hay que pintar el buscar socio
if ($llBuscarCliente) {
    if (!empty($oCliEsp)) {
        if (App::getInstancia()->esFerrcash()) {
            $oTercero = new WebClientes();
        } else {
            $oTercero = new WebSociosSucs();
        }
        $oTercero->depurarObjeto($llDepurar);
        $arrClientes = $oTercero->obtenerRegistros(Conexion::getInstancia(),"CDCLIEN = {$oCliEsp}", "CDSUCUR");
        foreach ($arrClientes as $laArrConfCliBus) {
            //Quiero seleccionar el primer registro
            if (App::getInstancia()->esFerrcash()) {
                $oTercero = new WebClientes($laArrConfCliBus);
            } else {
                $oTercero = new WebSociosSucs($laArrConfCliBus);
            }
        }
        if ($laDatosClienteConf['IND_SOLO_TARICAT'] == 1) {
            $tipoCliente     = $laArrConfCliBus['TIPO_CLIENTE'];
            if ($tipoCliente <> 'TARICAT' && $tipoCliente <> 'TARIBAL') {
                $lcMensajeProceso = 'NO SE PUEDEN SELECCIONAR CLIENTES QUE NO SEAN TARICAT / TARIBAL';
                $oCliEsp          = $UserID;
                $oAccion          = '';
                $lcBuscar_Soc     = $UserID;
            } else {
                $lcBuscar_Soc = $oCliEsp;
            }
        }
        if (! empty(trim($laDatosClienteConf['CODIGO_COMPRADOR']))) {
            if ($oTercero->codigoResponsableComercial() <> $laDatosClienteConf['CODIGO_COMPRADOR']) {
                $lcMensajeProceso = "NO SE PUEDEN SELECCIONAR CLIENTES QUE NO TENGAS ASOCIADO ({$laDatosClienteConf['CODIGO_COMPRADOR']})";
                $oCliEsp          = $UserID;
                $lcBuscar_Soc     = $UserID;
                $oAccion          = '';
            }
        }
    }
}
//-(06/11/2016)

Iniutils::escribeEnLog("Consultar datos del empresa/cliente/sucursal: {$pgIdentificadorEmpresa}/{$lcBuscar_Soc}/{$lcBuscar_Suc} / Cliente especial: {$oCliEsp}", $llDepurar && !$llExpExcel);

//CAPTURAR DATOS DEL CLIENTE QUE ESTA CONSULTANDO
$nTercero                  = (int) $lcBuscar_Soc;
$lnRiesgo_Cliente          = 0;
$lfRiesgo_Fecha            = '';
$lcFechaActualizacionDatos = '';
$nCliente_Activo           = 0;
$cCliente_TipoCliente      = '';
$cCliente_TipoRiesgo       = '';
if ($pgIdentificadorEmpresa == 'FER') {
    $oTercero = new WebClientes();
} else {
    $oTercero = new WebSociosSucs();
}
$oTercero->depurarObjeto($llDepurar);
$oTercero->cdclien($nTercero);
$oTercero->cdsucur($lcBuscar_Suc);
if ($oTercero->obtenerPorId(Conexion::getInstancia())) {
    $nCliente_Activo      = $oTercero->indAct();
    $lnRiesgo_Cliente     = $oTercero->riesgoDisp();
    $lfRiesgo_Fecha       = $oTercero->fecActRie();
    $cCliente_TipoCliente = $oTercero->tipoCliente();
    $cCliente_TipoRiesgo  = $oTercero->tipoRiesgo();
}

//+Detalles (e importe pendiente)
$llMostrarConfPen_Imp   = false;
$llPintar_ResumenRiesgo = true;
//Conformidades
$lBot_DetConf_Capt     = 'Ver';
$lBot_DetConf_Acc      = 'CONFPEN';
$llPintarDetalleConfor = false;
if (($lnIdUsuMagento == 10) or ($lnIdUsuMagento == 1718) or ($lnIdUsuMagento == 348)) {
    $llMostrarConfPen_Imp = true;
}
if ($oAccion == $lBot_DetConf_Acc) {
    $llPintarDetalleConfor = true;
    $lBot_DetConf_Capt     = 'Ocultar';
    $lBot_DetConf_Acc      = '';
}
//Readsoft
$lBot_DetRead_Capt    = 'Ver';
$lBot_DetRead_Acc     = 'READPEN';
$llPintarDetalleReads = false;
if ($oAccion == $lBot_DetRead_Acc) {
    $llPintarDetalleReads = true;
    $lBot_DetRead_Capt    = 'Ocultar';
    $lBot_DetRead_Acc     = '';
}
//-

// (20/12/2012)
$lcTituloPantalla = 'DETALLE RIESGO ASIGNADO';
if (!empty($oDocumento)) {
    $laDatosClienteBus = fDatosTercero($connection, $DBTableNames['Socios'], $oDocumento, 1);
    if ($laDatosClienteBus ['IND_ACT'] == 0) {
    } else {
        $lcBuscar_Soc = $oDocumento;
        // CAMBIAR TITULO
        $lcTituloPantalla = $oDocumento . ' ' . $UserName;
    }
}

//
$llPagina       = true;
$llBotonDetalle = false;
$llBotonExcel   = false;

if ($oAccion == 'E') {
    $llPagina = false;
}
//MOSTRAR EL DETALLE???
$llDetallePedido = false;
if ($oAccion == 'D') {
    $llDetallePedido = true;
}
//MOSTRAR EL DETALLE???
if ($oAccion == 'D') {
    $llPedidosBloqueados = false;
}
if ($oAccion == 'EX') {
    $llPagina = false;
}

$pLinkOut          = 'MAGENTO-' . $lnIdUsuMagento . '-EMPRESA-' . $pgIdentificadorEmpresa . '-CLIENTE-' . $lcBuscar_Soc . '-SUCURSAL-' . $UserSuc . '-';
$pLinkOut          = Iniutils::fgEncripta($pLinkOut, $pgIdRaizFerretera);
$windowOptions     = 'scrollbars=1, height=600, width=900';
$lcFuncionImprimir = "window.open('{$lcNomPageAct}?pPwLk={$pLinkOut}', 'yt_main', '{$windowOptions}'); return false;";
if ($llVistaEspecial) {
    $lcFuncionImprimir = "window.print()";
    $llBotonExcel      = false;
    $llBuscarCliente   = false;
    $lcAyuNota         = '';
}
//-

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

$lcAyuTex           = "* La <u>CLASIFICACION DE RIESGO</u> es otorgada por AXEXOR.<BR>";
$lcAyuTex          .= '';
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

$llPintar_ResumenRiesgo = !$llExpExcel;
if ($oAccion == 'EX-CF') {
    $llPintarDetalleConfor = true;
}
if ($oAccion == 'EX-RD') {
    $llPintarDetalleReads = true;
}

//Ayuda especial de la nota
$lcAyudaPantalla = "Tip('Valoracion de riesgo otorgada por <B>AXESOR</B>');";
$lcAyuNota          = '<img src="' . $Rutas ['Imagenes'] . 'ayuda_peq_comafe.gif" height="17" width="17" onmouseover="' . $lcAyudaPantalla . '" />';

//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();

//Pintar nota de riesgo
$llMostrarNotaRiesgo = false;
if ($pgIdentificadorEmpresa == 'COM') {
    $llMostrarNotaRiesgo = true;
}

//

if ($llExpExcel) {

    die('EN CONTRUCCION');

} else {
?>


<body onLoad="<?php echo $lcObjetoFoco; ?>">

<form id="frmDatos" name="frmDatos" method="post" action="">
    <INPUT type="hidden" id="pSession" name="pSession" value="<?php echo $pSession; ?>">
    <INPUT type="hidden" id="pUrl" name="pUrl" value="">
    <INPUT type="hidden" id="oAccion" name="oAccion" value="">
    <!-- CAMPOS DEL FORMULARIO -->
    <input type="hidden" id="pOrigen" name="pOrigen" value="FRIE" />
    <input type="hidden" id="oDetalle" name="oDetalle" value="" />
    <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>" />
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
                    <i class="fa fa-print fa-icono-header"
                       title="Imprimir listado de riesgo"
                       onClick="<?php echo $lcFuncionImprimir; ?>"></i>
                    <?php if (! $llVistaEspecial) {?>
                    <i class="fa fa-file-excel-o fa-icono-header"
                       title="Exportar a excel el listado que actualmente tiene en pantalla"
                       onClick="fAccion('frmDescarga','<?php echo $lcNomPageExc; ?>','EX');"></i>
                    <?php } ?>
                    <?php if (! $llVistaEspecial) {?>
                    <i class="fa fa-question fa-icono-header"
                       title=""
                       onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>


        <?php if ($llDivBotones_Inf) { ?>
        <br />
        <div>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="5"></td>
                </tr>
                <tr>
                    <td width="33%" align="center"><?php if ($llBuscarCliente) { ?>
                        <label for="oCliEsp"><input id="oCliEsp"
                                                    name="oCliEsp"
                                                    type="text"
                                                    class="text_edi"
                                                    value="<?php echo $oCliEsp; ?>"
                                                    size="6"
                                                    style="width:100px" />&nbsp;&nbsp;&nbsp;
                            <img src="<?php echo $Rutas ['Imagenes']; ?>bConsultar.jpg"
                                 height="18"
                                 width="20"
                                 onClick="fAccion('_self','<?php echo $lcNomPageAct; ?>','C')"
                                 class="pondedo" /></label><?php } ?>
                    </td>
                    <td width="33%" align="center">
                    </td>
                    <td width="33%" align="center">
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
        </div>
        <?php } ?>


        <br />
        <div id="dvResumenRiesgo">
            <?php

            //REVISAR QUE CAMPOS HAY QUE MOSTRAR
            if ($cCliente_TipoRiesgo == 'CONSUMO') {
                $llMostrarNotaRiesgo = false;
                $llMostrarConfPen    = false;
                $llMostrarConfRead   = false;
                $llMostrarSaldo      = false;
                $llMostrarFacAnual   = true;
                $lcLiteralRiesgo     = 'RIESGO';
            } else {
                $llMostrarNotaRiesgo = true;
                $llMostrarConfPen    = true;
                $llMostrarConfRead   = true;
                $llMostrarSaldo      = true;
                $llMostrarFacAnual   = false;
                $lcLiteralRiesgo     = 'SALDO';
            }

            //CAPTURAR DATOS DEL RIESGO DEL CLIENTE
            $lcRie_CredMax       = '';
            $lcRie_Nota          = '';
            $lcRie_Consumido     = '';
            $lcRie_PedVen        = '';
            $lcRie_Confor        = '';
            $lcRie_ConforImpPen  = '';
            $lcRie_AlbVen        = '';
            $lcRie_ReadSoft      = '';
            $lcRie_Saldo         = '';
            $lcRie_FacAnual      = '';
            $lcRie_Dis           = '';
            $llPedidosBloqueados = false;

            $oRiesgoTercero = new WebRiesgo();
            $oRiesgoTercero->depurarObjeto($llDepurar);
            $arrRiesgos = $oRiesgoTercero->obtenerRegistros(Conexion::getInstancia(), "EMPRESA = '{$pgIdentificadorEmpresa}' AND CDCLIEN = {$nTercero}");

            foreach ($arrRiesgos as $row) {

                $oRiesgo = new WebRiesgo($row);

                $lfFecha = mb_substr($oRiesgo->fecActRie(),0,10);
                $lfFecha = Iniutils::ymdTOdmy($lfFecha,'/').' '.mb_substr($oRiesgo->fecActRie(),11);

                //$lfFecha = $row['FOR_FAR'];

                $lnRie_CredMax      = $oRiesgo->credMaximo();
                $lnRie_PedVen       = $oRiesgo->consPedven();
                $lnRie_Confor       = $oRiesgo->consConfor();
                $lnRie_AlbVen       = $oRiesgo->consAlbaran();
                $lnRie_ReadSoft     = $oRiesgo->consReadsof();
                $lnRie_Saldo        = $oRiesgo->consSaldo();
                $lnRie_FacAnual     = $oRiesgo->consFactAnual();
                $lnRie_ConforImpPen = $oRiesgo->consConfImppen();

                $lnRie_Consumido = $lnRie_PedVen + $lnRie_Confor + $lnRie_AlbVen + $lnRie_ReadSoft + $lnRie_Saldo;

                $lnRie_Dis          = $oRiesgo->riesgoDisp();
                $lnRie_DisCalc      = $lnRie_CredMax - $lnRie_Consumido;
                $lcRie_Nota         = $oRiesgo->rieNota();

                $lcRie_CredMax      = number_format($lnRie_CredMax, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_Consumido    = number_format($lnRie_Consumido, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_PedVen       = number_format($lnRie_PedVen, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_Confor       = number_format($lnRie_Confor, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_AlbVen       = number_format($lnRie_AlbVen, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_ReadSoft     = number_format($lnRie_ReadSoft, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_Saldo        = number_format($lnRie_Saldo, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_FacAnual     = number_format($lnRie_FacAnual, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_Dis          = number_format($lnRie_DisCalc, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_ConforImpPen = number_format($lnRie_ConforImpPen, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcRie_Fact         = number_format($lnRie_FacAnual, 2, ',', '.') . '&nbsp;€&nbsp;';

                $lcFechaActualizacionDatos = "Fecha y hora de actualizacion: {$lfFecha}";

                $llPedidosBloqueados = $oRiesgo->indPedBlock();
            }

            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                <?php if ($llPintar_ResumenRiesgo) { ?>
                <tr>
                    <td width="4%" class="intranet-FilaTotal"><span></span></td>
                    <td class="intranet-FilaTotal" colspan="2"><span>CRÉDITO MÁXIMO </span></td>
                    <td width="18%" class="intranet-FilaTotal" align="right">
                        <span><?php echo $lcRie_CredMax; ?></span></td>
                    <td width="4%" class="intranet-FilaTotal"><span></span></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <?php if ($llMostrarNotaRiesgo) { ?>
                <tr>
                    <td width="4%" class="intranet-FilaTotal"><span></span></td>
                    <td class="intranet-FilaTotal" colspan="2"><span>CLASIFICACIÓN DE RIESGO  </span></td>
                    <td width="18%" class="intranet-FilaTotal" align="right">
                        <span><?php echo $lcRie_Nota; ?>&nbsp;&nbsp;&nbsp;<?php echo $lcAyuNota; ?></span>
                    </td>
                    <td width="4%" class="intranet-FilaTotal"><span></span></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="intranet-FilaTotal"><span></span></td>
                    <td class="intranet-FilaTotal" colspan="2"><span>RIESGO TOTAL CONSUMIDO</span></td>
                    <td class="intranet-FilaTotal" align="right"><span><?php echo $lcRie_Consumido; ?></span>
                    </td>
                    <td class="intranet-FilaTotal"><span></span></td>
                </tr>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <tr>
                    <td class=""><span class=""></span></td>
                    <td width="30%" class=""><span></span></td>
                    <td width="44%" class=""><span class="intranet-Text">+&nbsp;&nbsp; Pedidos Aceptados pendientes de servir</span>
                    </td>
                    <td class="" align="right"><span class="intranet-Text"><?php echo $lcRie_PedVen; ?></span>
                    </td>
                    <td class=""><span></span></td>
                </tr>
                <?php if ($llMostrarConfPen) { ?>
                <tr>
                    <td class=""><span></span></td>
                    <td class=""><span></span></td>
                    <td class=""><span class="intranet-Text">+&nbsp;&nbsp; Conformidades Pendientes</span>
                    </td>
                    <td class="" align="right">
                        <span class="intranet-Text"><?php echo $lcRie_Confor; ?></span></td>
                    <td class=""><span></span></td>
                </tr>
                <?php } ?>
                <?php if ($llMostrarConfPen_Imp) { ?>
                <tr>
                    <td class=""><span></span></td>
                    <td class=""><span></span></td>
                    <td class=""><span class="intranet-Text">+&nbsp;&nbsp; Conformidades Pendientes (Importe) <?php if (! $llVistaEspecial) { ?><a
                                    href="#"
                                    onClick="fAccion('_self','<?php echo $lcNomPageAct; ?>','<?php echo $lBot_DetConf_Acc; ?>')">(<?php echo $lBot_DetConf_Capt; ?>
                                detalle)</a><?php } ?></span>
                    </td>
                    <td class="" align="right">
                        <span class="intranet-Text"><?php echo $lcRie_ConforImpPen; ?></span></td>
                    <td class=""><span></span></td>
                </tr>
                <?php } ?>
                <tr>
                    <td class=""><span></span></td>
                    <td class=""><span></span></td>
                    <td class="">
                        <span class="intranet-Text">+&nbsp;&nbsp; Albaranes pendientes de facturar</span></td>
                    <td class="" align="right"><span class="intranet-Text"><?php echo $lcRie_AlbVen; ?></span>
                    </td>
                    <td class=""><span></span></td>
                </tr>
                <?php if ($llMostrarConfRead) { ?>
                <tr>
                    <td class=""><span></span></td>
                    <td class=""><span></span></td>
                    <td class="">
                        <span class="intranet-Text">+&nbsp;&nbsp; Directos sin facturar (Validados)</span>&nbsp;<?php if (! $llVistaEspecial) { ?><a
                                href="#"
                                onClick="fAccion('_self','<?php echo $lcNomPageAct; ?>','<?php echo $lBot_DetRead_Acc; ?>')">(<?php echo $lBot_DetRead_Capt; ?>
                            detalle)</a><?php } ?>
                    </td>
                    <td class="" align="right">
                        <span class="intranet-Text"><?php echo $lcRie_ReadSoft; ?></span></td>
                    <td class=""><span></span></td>
                </tr>
                <?php } ?>
                <?php if ($llMostrarSaldo) { ?>
                <tr>
                    <td class=""><span></span></td>
                    <td class=""><span></span></td>
                    <td class=""><span class="intranet-Text">+&nbsp;&nbsp; Saldo pendiente</span></td>
                    <td class="" align="right">
                        <span class="intranet-Text"><?php echo $lcRie_Saldo; ?></span></td>
                    <td class=""><span></span></td>
                </tr>
                <?php } ?>
                <?php if ($llMostrarFacAnual) { ?>
                <tr>
                    <td class=""><span></span></td>
                    <td class=""><span></span></td>
                    <td class="">
                        <span class="intranet-Text">+&nbsp;&nbsp; Facturas/Abonos año en curso</span></td>
                    <td class="" align="right">
                        <span class="intranet-Text"><?php echo $lcRie_FacAnual; ?></span></td>
                    <td class=""><span></span></td>
                </tr>
                <?php } ?>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <tr>
                    <td class="intranet-FilaTotal"><span></span></td>
                    <td class="intranet-FilaTotal" colspan="2">
                        <span class="intranet-Text"><?php echo $lcLiteralRiesgo; ?> DISPONIBLE</span></td>
                    <td class="intranet-FilaTotal" align="right">
                        <span class="intranet-Text"><?php echo $lcRie_Dis; ?></span></td>
                    <td class="intranet-FilaTotal"><span></span></td>
                </tr>
                <?php } ?>


                <?php if ($llPedidosBloqueados) { ?>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <tr>
                    <td class="intranet-FilaMarca_Amarilla"><span></span></td>
                    <td class="intranet-FilaMarca_Amarilla" colspan="2"><span>PEDIDOS BLOQUEADOS POR EXCESO DE RIESGO</span>
                    </td>
                    <td class="intranet-FilaMarca_Amarilla" align="right"><span></span></td>
                    <td class="intranet-FilaMarca_Amarilla"><span></span></td>
                </tr>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <?php

                $nSocio = (int)$lcBuscar_Soc;

                $oPedidosBloqueados = new WebRiesgoDet();
                $oPedidosBloqueados->depurarObjeto($llDepurar);
                $arrPedidos = $oPedidosBloqueados->obtenerPedidosBloqueados(Conexion::getInstancia(), $pgIdentificadorEmpresa, $nSocio);

                ?>
                <tr>
                    <td class=""><span class=""></span></td>
                    <td class="" colspan="3">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="25%" align="center" class="intranet-FilaTitulo">
                                    <span>Nº.PEDIDO</span></td>
                                <td width="25%" align="center" class="intranet-FilaTitulo"><span>FECHA</span>
                                </td>
                                <td width="25%" align="left" class="intranet-FilaTitulo"><span>REFERENCIA</span>
                                </td>
                                <td width="20%" align="center" class="intranet-FilaTitulo">
                                    <span>TOTAL PEDIDO</span></td>
                                <td width="5" align="center" class="intranet-FilaTitulo"><span></span></td>
                            </tr>
                            <?php
                            // CAPTURAR DATOS
                            foreach ($arrPedidos as $rowRie) {
                            $lDoc = $rowRie['N_DOC'];
                            $lFec = Iniutils::ymdTOdmy($rowRie['FECHA_DOC'],'/');
                            $lRef = $rowRie['REFEREN'];
                            $cTot = number_format($rowRie['TOTAL'],2,',', '.');
                            ?>
                            <tr>
                                <td align="center"><span><?php echo $lDoc; ?></span></td>
                                <td align="center"><span><?php echo $lFec; ?></span></td>
                                <td align="left"><span><?php echo $lRef; ?></span></td>
                                <td align="center"><span><?php echo $cTot; ?>&nbsp;€</span>
                                </td>
                                <td>
                                    <?php if (! $llVistaEspecial) {?>
                                    <i class="fa fa-file-o fa-icono-header fa-1_5x"
                                       title="Ver detalle del pedido"
                                       onClick="fAccionDetalle ('_self', '<?php echo $lcNomPageAct; ?>', '<?php echo $lDoc; ?>', 'D');"></i>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </table>

                    </td>
                    <td class=""><span></span></td>
                </tr>
                <?php } ?>

            <!-- PINTO DETALLE PEDIDO -->
                <?php if ($llDetallePedido) { ?>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <tr>
                    <td class="intranet-FilaMarca_Amarilla"><span></span></td>
                    <td class="intranet-FilaMarca_Amarilla" colspan="2">
                        <span>DETALLE PEDIDO: <?php echo $oDetalle; ?></span></td>
                    <td class="intranet-FilaMarca_Amarilla" align="right">
                        <i class="fa fa-arrow-left fa-icono-header"
                           title="Volver a pantalla de riesgo"
                           onClick="fAccion('_self', '<?php echo $lcNomPageAct; ?>', '');"></i>
                    </td>
                    <td class="intranet-FilaMarca_Amarilla"><span></span></td>
                </tr>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <?php

                $oPedidosBloqueados = new WebRiesgoDet();
                $oPedidosBloqueados->depurarObjeto($llDepurar);
                $arrLineas = $oPedidosBloqueados->obtenerPedidosBloqueadosDetalle(Conexion::getInstancia(), $pgIdentificadorEmpresa, $oDetalle);

                ?>
                <tr>
                    <td class=""><span></span></td>
                    <td class="" colspan="3">
                        <div style="OVERFLOW: auto; WIDTH: 100%; HEIGHT: 200px">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="10%" align="center" class="intranet-FilaTitulo"><span>&nbsp;ARTICULO</span>
                                    </td>
                                    <td width="55%" align="left" class="intranet-FilaTitulo">
                                        <span>DESCRIPCION</span></td>
                                    <td width="10%" align="right" class="intranet-FilaTitulo">
                                        <span>CANTIDAD</span></td>
                                    <td width="10%" align="right" class="intranet-FilaTitulo">
                                        <span>PRECIO</span></td>
                                    <td width="15%" align="right" class="intranet-FilaTitulo">
                                        <span>IMPORTE</span>&nbsp;</td>
                                </tr>
                                <?php
                                // CAPTURAR DATOS

                                foreach ($arrLineas as $rowRieDet) {
                                ?>
                                <tr>
                                    <td align="left"><span class=""><?php echo $rowRieDet['ART']; ?></span>
                                    </td>
                                    <td align="left"><span class=""><?php echo $rowRieDet['DES']; ?></span>
                                    </td>
                                    <td align="right"><span class=""><?php echo $rowRieDet['CAN']; ?></span>
                                    </td>
                                    <td align="right"><span class=""><?php echo $rowRieDet['PVP']; ?>&nbsp;€&nbsp;</span>
                                    </td>
                                    <td align="right"><span class=""><?php echo $rowRieDet['IMP']; ?>&nbsp;€&nbsp;</span>&nbsp;
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </td>
                    <td class=""><span></span></td>
                </tr>
                <?php } ?>
            <!-- FIN DETALLE PEDIDO -->

                <!-- DETALE CONFORMIDADES -->
                <?php if ($llPintarDetalleConfor) { ?>

                <?php
                $oConformidad = new WebConformidad();
                $oConformidad->depurarObjeto($llDepurar);

                $cWhere       = "EMPRESA = '{$pgIdentificadorEmpresa}' AND CDCLIEN = {$lcBuscar_Soc} AND (NCONFORMIDAD <> '')";
                $cWhere      .= " AND ((IMPORTE_PEN <> 0) AND (FECHA <= CURDATE() AND CURDATE() <= FEC_FIN_VAL))";
                $cOrden       = "NCONFORMIDAD";
                $arrRegistros = $oConformidad->obtenerRegistros(Conexion::getInstancia(), $cWhere, $cOrden);

                if (!$llExpExcel) {
                    $lcEnl = '<a href="#" onclick="fAccionDetalle' . "('frmDescarga','" . $lcNomPageDwn . "','" . $lcBuscar_Soc . "', 'EX-CF')" . '"><img src="' . $Rutas['Imagenes'] . 'Excel.gif" width="15" height="15" /></a>';
                }

                ?>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5">
                        <table width="100%">
                            <tr>
                                <td class="intranet-FilaMarca_Amarilla" align="center" colspan="8">
                                    &nbsp;<?php echo $lcEnl; ?>&nbsp;<span>DETALLE CONFORMIDADES</span></td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td colspan="8"><span style="font-size:14px; color:blue">Si aparecen los totales de la conformidad en AZUL, significa que este pedido se ha servido parcialmente. Al riesgo de conformidades sigue sumando la parte pendiente.</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td class="intranet-FilaTitulo" width="80">&nbsp;N.CONF.</td>
                                <td class="intranet-FilaTitulo" width="60" align="center">SUC.</td>
                                <td class="intranet-FilaTitulo" width="100" align="center">FECHA</td>
                                <td class="intranet-FilaTitulo" width="80">PROV.</td>
                                <td class="intranet-FilaTitulo" width="450">RAZON SOCIAL</td>
                                <td class="intranet-FilaTitulo" width="100">PED.SOCIO</td>
                                <td class="intranet-FilaTitulo" width="110" align="right">IMP.CONFOR.&nbsp;</td>
                                <td class="intranet-FilaTitulo" width="110" align="right">
                                    IMP.PENDIENTE&nbsp;
                                </td>
                            </tr>
                            <?php
                            $lnTmpTot = 0;
                            $lnTmpPen = 0;
                            $lnCont   = 0;

                            $oProveedor = new WebProveedor();

                            foreach ($arrRegistros as $rowConf) {

                            $lnCont   = $lnCont + 1;
                            $lnTmpTot = $lnTmpTot + $rowConf['IMPORTE'];
                            $lnTmpPen = $lnTmpPen + $rowConf['IMPORTE_PEN'];

                            $lcColorFue = '';
                            if ($rowConf['IMPORTE'] <> $rowConf['IMPORTE_PEN']) {
                                $lcColorFue = 'blue';
                            }

                            $lcNombreProveedor = '';
                            $oProveedor->cdprove($rowConf['CDPROVE']);
                            if ($oProveedor->obtenerPorId(Conexion::getInstancia())) {
                                $lcNombreProveedor = $oProveedor->razon();
                            }

                            ?>
                            <tr>
                                <td valign="top"><?php echo $rowConf['NCONFORMIDAD']; ?></td>
                                <td align="center" valign="top"><?php echo $rowConf['CDSUCUR']; ?></td>
                                <td align="center" valign="top"><?php echo Iniutils::ymdTOdmy($rowConf['FECHA'],'/'); ?></td>
                                <td valign="top"><?php echo $rowConf['CDPROVE']; ?></td>
                                <td valign="top"><?php echo $lcNombreProveedor; ?></td>
                                <td valign="top"><?php echo $rowConf['REF_SOCIO']; ?></td>
                                <td align="right" valign="top">
                                                <span style="color: <?php echo $lcColorFue; ?>; "><?php echo number_format(
                                                        $rowConf['IMPORTE'],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ); ?> &euro;</span></td>
                                <td align="right" valign="top">
                                                <span style="color: <?php echo $lcColorFue; ?>; "><?php echo number_format(
                                                        $rowConf['IMPORTE_PEN'],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ); ?> &euro;</span></td>
                            </tr>
                            <?php
                            }
                            $lcTmpTot = number_format($lnTmpTot, 2, ',', '.');
                            $lcTmpPen = number_format($lnTmpPen, 2, ',', '.');
                            ?>
                            <tr>

                                <td class="intranet-FilaTotal" align="left" colspan="6">
                                    &nbsp;<?php echo $lnCont . ' registro/s'; ?></td>
                                <td class="intranet-FilaTotal" align="right"><?php echo $lcTmpTot; ?> &euro;</td>
                                <td class="intranet-FilaTotal" align="right"><?php echo $lcTmpPen; ?> &euro;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php } ?>
            <!-- FIN DETALLE CONFORMIDADES -->

                <!-- DETALE READSOFT -->
                <?php if ($llPintarDetalleReads) { ?>

                <?php
                $oReadsoft = new WebReadsoft();
                $oReadsoft->depurarObjeto($llDepurar);

                $cWhere       = "EMPRESA = '{$pgIdentificadorEmpresa}' AND CDCLIEN = {$lcBuscar_Soc} AND ESTADO IN ('PD', 'RE')";
                $cOrden       = "IDCBA";
                $arrRegistros = $oReadsoft->obtenerRegistros(Conexion::getInstancia(), $cWhere, $cOrden);

                if (!$llExpExcel) {
                    $lcEnl = '<a href="#" onclick="fAccionDetalle' . "('frmDescarga','" . $lcNomPageDwn . "','" . $lcBuscar_Soc . "', 'EX-RD')" . '"><img src="' . $Rutas['Imagenes'] . 'Excel.gif" width="15" height="15" /></a>';
                }

                ?>
                <tr>
                    <td height="15" colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5">
                        <table width="100%">
                            <tr>
                                <td class="intranet-FilaMarca_Amarilla" align="center" colspan="8">
                                    &nbsp;<?php echo $lcEnl; ?>&nbsp;<span>DETALLE DIRECTOS VALIDADOS</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td class="intranet-FilaTitulo" width="80">&nbsp;TIP.DOC..</td>
                                <td class="intranet-FilaTitulo" width="60" align="center">SUC.</td>
                                <td class="intranet-FilaTitulo" width="100" align="center">FECHA</td>
                                <td class="intranet-FilaTitulo" width="80">PROV.</td>
                                <td class="intranet-FilaTitulo" width="450">RAZÓN SOCIAL</td>
                                <td class="intranet-FilaTitulo" width="100" align="center">CONFORMIDAD</td>
                                <td class="intranet-FilaTitulo" width="110" align="left">FAC.PROV.&nbsp;</td>
                                <td class="intranet-FilaTitulo" width="110" align="right">
                                    IMP.PENDIENTE&nbsp;
                                </td>
                            </tr>
                            <?php
                            $lnTmpTot = 0;
                            $lnTmpPen = 0;
                            $lnCont   = 0;

                            $oProveedor = new WebProveedor();

                            foreach ($arrRegistros as $rowRead) {

                            $lnCont = $lnCont + 1;

                            $lnImpDoc   = $rowRead['TOTAL'];
                            $sDocumento = 'FACTURA';
                            if ($rowRead['TIPO'] == 'A') {
                                $lnImpDoc = $lnImpDoc * (-1);
                                $sDocumento = 'ABONO';
                            }
                            $lnTmpTot = $lnTmpTot + $lnImpDoc;

                            $lcEstado   = $rowRead['ESTADO'];
                            $lcClassEsp = '';
                            if ($lcEstado == 'RE') {
                                $lcClassEsp = '';
                            }

                            $lcNombreProveedor = '';
                            $oProveedor->cdprove($rowRead['CDPROVE']);
                            if ($oProveedor->obtenerPorId(Conexion::getInstancia())) {
                                $lcNombreProveedor = $oProveedor->razon();
                            }
                            ?>
                            <tr>
                                <td valign="top">
                                    <span class="<?php echo $lcClassEsp; ?>"><?php echo $sDocumento; ?></span>
                                </td>
                                <td align="center" valign="top">
                                    <span class="<?php echo $lcClassEsp; ?>"><?php echo $rowRead['CDSUCUR']; ?></span>
                                </td>
                                <td align="center" valign="top">
                                    <span class="<?php echo $lcClassEsp; ?>"><?php echo Iniutils::ymdTOdmy($rowRead['FEC_CONF'],'/'); ?></span>
                                </td>
                                <td valign="top">
                                    <span class="<?php echo $lcClassEsp; ?>"><?php echo $rowRead['CDPROVE']; ?></span>
                                </td>
                                <td valign="top">
                                    <span class="<?php echo $lcClassEsp; ?>"><?php echo $lcNombreProveedor; ?></span>
                                </td>
                                <td valign="top" align="center">
                                    <span class="<?php echo $lcClassEsp; ?>"><?php echo $rowRead['NMCONFOR']; ?></span>
                                </td>
                                <td valign="top">
                                    <span class="<?php echo $lcClassEsp; ?>"><?php echo $rowRead['REFPROV']; ?></span>
                                </td>
                                <td align="right" valign="top">
                                                <span class="<?php echo $lcClassEsp; ?>"><?php echo number_format(
                                                        $lnImpDoc,
                                                        2,
                                                        ',',
                                                        '.'
                                                    ); ?> &euro;</span></td>
                            </tr>
                            <?php
                            }
                            $lcTotalAcumulado = number_format($lnTmpTot, 2, ',', '.');
                            ?>

                            <tr>
                                <td class="intranet-FilaTotal" align="left" colspan="7">
                                    &nbsp;<?php echo $lnCont . ' registro/s'; ?></td>
                                <td class="intranet-FilaTotal" align="right"><?php echo $lcTotalAcumulado; ?> &euro;</td>
                            </tr>

                        </table>
                    </td>
                </tr>
            <?php } ?>
            <!-- FIN DETALLE READSOFT -->

            </table>
        </div>

        <?php if ($lcMensajeProceso <> '') { ?>
        <br />
        <div id="dvMensajes" style="height:40px; text-align: center">
            <span class="intranet-LetraError"><?php echo $lcMensajeProceso; ?></span>
        </div>
        <?php } ?>

        <br />
        <div id="dvPie" class="footer-top" style="text-align:right">
            <p class="intranet-FilaPie"><span><strong><?php echo $lcFechaActualizacionDatos; ?>
                        &nbsp;&nbsp;&nbsp;</strong></span></p>
        </div>

    </div>

</form>

<?php if ($connection) {
    AccesoDatos::fgCerrarConexion($connection);
} ?>

</body>

<?php if ($llDivHiddenDown) { ?>
<iframe id="frmDescarga" name="frmDescarga" style="visibility:hidden" height="1" width="1"></iframe><?php } ?>
</html>

<?php
}
?>
