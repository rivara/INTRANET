<!--	29/11/2014	-->
<!--	15/03/2015	Nueva capa -> Pie	-->
<!--	21/03/2015	Grabar en tabla configuracion para el user Magento	-->
<!--	28/03/2015	Permisos: pagina que estoy revisando, se añaden al jed_valuso	-->
<?php
//+PARAMETROS GENERAL PAGINA
$lcDirBase   = '../';                                        // COMO IR AL DIRECTORIO BASE
$llDepurar   = false;                                        // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan    = 'Busqueda de proveedores';                    // TITULO DE LA PAGINA
$llExpExcel  = false;                                        // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc    = '';                                            // NOMBRE DEL FICHERO EXCEL A GENERAR
$lcNomPagina = mb_strtolower($_SERVER['PHP_SELF']);
$lnPos       = mb_strrpos($lcNomPagina, '/');
if ($lnPos <> 0) {
    $lcNomPagina = substr($lcNomPagina, $lnPos + 1);
}
//-

//+Inicio variables que seran luego cargadas en los includes
$Rutas              = [];
$DBTableNames       = [];
$EmailsCliente      = [];
$UsersWebAdmin      = [];
$laDatosCliente     = [];
$laDatosClienteConf = [];
$arrProveedores     = [];
$DBName             = '';
$UserID             = '';
$UserSuc            = '';
$User_TIPO          = '';
$pDynamicUser       = '';
$conBdMagTmp        = false;
//-

//+REQUIRES POR DEFECTO y PSESSIONS
include_once $lcDirBase . 'jed_paratube.php';
//-

//+REQUIRES_OPCIONALES
//-REQUIRES_OPCIONALES

//+CONEXION BD
$connection = fgCrearConexion($DBUserName, $DBPwd, $DBServer, $DBName);
//-

//+CAPTURO EL CODIGO DE USUARIO
if (!isset($pIdMagento)) {
    $pIdMagento = -1;
}
$lnIdUsuMagento = $pIdMagento;
//if ($lnIdUsuMagento == $gcUsuarioDebugger) { $llDepurar = true; }
//-

//+VALIDACION ACCESOS
//(1=ok		0=Sin validacion	-1=Usuario no permitido		-2=actualizando datos		-3=opcion no configurada)
$lnStatus               = 0;                                    // 1 o 0 o -1 o -2 o -3
$lcTipoClientePermitodo = '#SOCIO#EMPLEADO#####';                        // 'SOCIO#CECOFERSA#CLIENTE#CADENISTA#PROVEEDOR#AGENCIA#EMPLEADO';
if ($pgIdentificadorEmpresa == 'FER') {
    $lcTipoClientePermitodo = '##CADENISTA#CADENISTA-B####';
}
$llValidarUsuario         = true;
$llValidar_TiendaFerrokey = false;
$llValidar_ODBC           = false;
$lcValidar_ODBC_Tabla     = $DBTableNames['Socios'];            // 'Socios'		'Proveedor'		'Recep_Ag_Transporte'
$lcValidar_ODBC_Tipo      = 'SOCIO';                            // 'SOCIO'		'CECOFERSA'
$llValidar_API            = true;
$llSoloUserDepurador      = false;

//+

include_once $lcDirBase . 'jed_valuso.php';

$llDepurar = $laDatosClienteConf['ADM_INFOR_DBG'];                // VERIFICAR SI EL USUARIO ES DEPURADOR
//-VALIDACION ACCESOS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////                           PAGINA VARIABLE                                  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//+PAGINAS RESPUESTAS
$lcNomPageBus  = $Rutas['Base_Intranet_Ww'] . 'Socios/socios_Consulta_Act_Det.php';
$lcNomPageFic  = $Rutas['Base_Intranet_Ww'] . 'Socios/prov_Ficha.php';
$lcNomPageSelf = $Rutas['Base_Intranet_Ww'] . 'Socios/prov_Inicio.php';
//-

//+CAPTURAR VARIABLES FORMULARIO
if (!isset($pSession)) {
    $pSession = 0;
}
$oDetalle    = isset($_POST['oDetalle']) ? $_POST['oDetalle'] : '';
$oBusTip     = isset($_POST['oBusTip']) ? $_POST['oBusTip'] : '';
$oBusProvRef = isset($_POST['oBusProvRef']) ? $_POST['oBusProvRef'] : '';
$oBusProvEan = isset($_POST['oBusProvEan']) ? $_POST['oBusProvEan'] : '';
$oBusProvDes = isset($_POST['oBusProvDes']) ? $_POST['oBusProvDes'] : '';
$oBusProvCod = isset($_POST['oBusProvCod']) ? $_POST['oBusProvCod'] : '';
$oBusProvRaz = isset($_POST['oBusProvRaz']) ? $_POST['oBusProvRaz'] : '';
//-FIN CAPTURAR VARIABLES FORMULARIO

//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Texto      = '';
$lcLog_Grupo      = 'SOCIOS';
//PARA EL PROVEEDOR
//$lTipoCliente       = 'SOCIO';
$lTipoCliente = $User_TIPO;
//ACCIONES DEL FORMULARIO
$llPantallaBusqueda = true;
$llPantallaResumen  = false;
$llPantallaPedidos  = false;
if (($pgIdentificadorEmpresa == 'FER')) {
    $llPantallaBusqueda = false;
    $llPantallaResumen  = true;
}
if (!isset($oAccion)) {
    $oAccion = '';
}
if ($oAccion == 'C') {
    $oBusTip = $oDetalle;
}
$llBuscarPorArticulo = false;
if ($oBusTip == '') {
    $oBusTip = 'PR';
}
$lcOpt1_Fondo = $pgEmails_FilaTitulo_Color;
$lcOpt1_Letra = 'letra_sec';
$lcOpt2_Fondo = $pgEmails_FilaTitulo_Color;
$lcOpt2_Letra = 'letra_sec';
$lcOpt3_Letra = 'letra_sec';
$lcOpt3_Fondo = $pgEmails_FilaTitulo_Color;
switch ($oBusTip) {
    case 'PR':
        $lcOpt1_Fondo = 'yellow';
        $lcOpt1_Letra = 'letra_sec';
        break;
    case 'AR':
        $lcOpt2_Fondo        = 'yellow';
        $lcOpt2_Letra        = 'letra_sec';
        $llBuscarPorArticulo = true;
        break;
    case 'PP':
        $lcOpt2_Fondo       = 'yellow';
        $lcOpt2_Letra       = 'letra_sec';
        $llPantallaBusqueda = false;
        //$llPantallaPedidos   = true;
        $llBuscarPorArticulo = false;
        break;
    case 'IN':
        $llPantallaResumen  = true;
        $llPantallaBusqueda = false;
        break;
}
//
$lcEnlace_BusPro  = '<button type="button" class="button" id="oBusPro" onclick="' . "fAccionDetalle('', '', 'PR', 'C')" . '"><span><span> Busqueda por proveedor  </span></span></button>';
$lcEnlace_BusArt  = '<button type="button" class="button" id="oBusPro" onclick="' . "fAccionDetalle('', '', 'AR', 'C')" . '"><span><span> Busqueda por articulos  </span></span></button>';
$lcEnlace_Pedidos = '';
$lcEnlace_Indice  = '';
if (($User_TIPO == 'TARICAT') or ($User_TIPO == 'TARIBAL')) {
    $lcEnlace_Indice = '<button type="button" class="button" id="oIndPro" onclick="' . "fAccionDetalle('', '', 'IN', 'C')" . '"><span><span> Indice Proveedores  </span></span></button>';
    $lTipoCliente    = 'TARICAT';
}

//if (($laDatosClienteConf['IND_USER_DEVELOP'] == 1) or ($laDatosClienteConf['ADM_INFOR_DBG'] == 1)) {
//    $lcEnlace_Pedidos = '<button type="button" class="button" id="oPedPen" onclick="' . "fAccionDetalle('', '', 'PP', 'C')" . '"><span><span> Pedidos Abiertos  </span></span></button>';
//}

//

//
$lnNumReg    = 0;
$stmtListado = 0;
if ($oAccion == "B") {
    $mensaje = <<<MSG
Par.Busqueda: Codigo= {$oBusProvCod} / Descripcion= {$oBusProvRaz} /
Ref.Prov= {$oBusProvRef} / Ean= {$oBusProvEan} / Des= {$oBusProvDes}
MSG;
    Iniutils::escribeEnLog($mensaje, $llDepurar);

    if ($llBuscarPorArticulo) {
        $oBusProvRef = str_replace('Ñ', '_', trim($oBusProvRef));
        $oBusProvEan = trim(strtoupper($oBusProvEan));
        $oBusProvDes = str_replace('Ñ', '_', trim($oBusProvDes));

        // SQL
        $llAviBus = false;
        if (($oBusProvRef == '') and ($oBusProvEan == '') and ($oBusProvDes == '')) {
            $lcMensajeProceso = 'Tienes que rellenar algun campo para buscar los proveedores';
        } else {
            $oProveedorTarifa = new WebProveedorTarifaCabecera();
            $oProveedorTarifa->depurarObjeto($llDepurar);
            $arrParameter['empresa']           = $pgIdentificadorEmpresa;
            $arrParameter['tipoCliente']       = $lTipoCliente;
            $arrParameter['filtroReferencia']  = strtoupper($oBusProvRef);
            $arrParameter['filtroDescripcion'] = strtoupper($oBusProvDes);
            $arrParameter['filtroEan']         = $oBusProvEan;
            $arrProveedores                    = $oProveedorTarifa->obtenerRegistro_BusquedaArticulos(
                Conexion::getInstancia(),
                $arrParameter
            );
        }
    } else {

        $lcBusCod = trim($oBusProvCod);
        $lcBusDes = trim(strtoupper($oBusProvRaz));
        $lcBusDes = str_replace('Ñ', '_', $lcBusDes);

        $llAviBus = false;
        if (($lcBusCod == '') and ($lcBusDes == '')) {
            $lcMensajeProceso = 'Tienes que rellenar algun campo para buscar los proveedores';
        } else {
            // EJECUTAR SQL
            $lProCif         = '';
            $lOrden          = '';
            $lSoloConDirecto = false;
            if (($lTipoCliente == 'TARICAT') or ($lTipoCliente == 'TARIBAL')) {
                $lSoloConDirecto = true;
            }
            $oProveedor = new WebProveedor();
            $oProveedor->depurarObjeto($llDepurar);
            $arrParameter['empresa']             = $pgIdentificadorEmpresa;
            $arrParameter['tipoCliente']         = $lTipoCliente;
            $arrParameter['filtroCodigoCliente'] = $lcBusCod;
            $arrParameter['filtroCodigoRazon']   = $lcBusDes;
            $lOrden                              = "RAZON";
            $arrProveedores                      = $oProveedor->obtenerRegistrosConDirectos(
                Conexion::getInstancia(),
                $arrParameter,
                $lOrden
            );
        }
    }

    //$stmtListadoNReg = fgEjecutarSql($connection, $lcCadSql);
    $lnNumReg = count($arrProveedores);
    if ($lnNumReg == '') {
        $lnNumReg = 0;
    }
    if ($lcMensajeProceso == '') {
        if ($lnNumReg == 0) {
            $lcMensajeProceso = 'No existen proveedores segun los campos que has rellenado';
        } else {
            $lcMensajeProceso = 'Se han encontrado ' . $lnNumReg . ' proveedores';
        }
    }
}
//-ACCIONES

//+LOG

if ($lcLog_Texto <> '') {
    fRegistrarLog($connection, $pgIdentificadorEmpresa, $UserID, $UserSuc, $lcLog_Grupo, $lcLog_Texto, $lnIdUsuMagento);
}
//-LOG

//+CONFIGURACION CONTENIDO
$lcAyuTex          = '* Ejemplo.<BR>';
$lcAyuTex          .= '* <B>Ejemplo</B>.<BR>';
$lcAyudaPantalla_P = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco      = "document.getElementById('oBusCod').focus()";
//$lcObjetoFoco      = "";
$llExplicacion = false;
//-CONFIGURACION CONTENIDO
//+CONFIGURACION CONTENIDO
$lcAyuTex          = '* Ejemplo.<BR>';
$lcAyuTex          .= '* <B>Ejemplo</B>.<BR>';
$lcAyudaPantalla_P = "Tip('" . $lcAyuTex . "');";
//<img src="../images/ayuda_peq_comafe.gif" width="15" height="15" border="0" onMouseOver=" <?php $lcAyudaPantalla_P; #CIERRE_PHP# ">
$lcObjetoFoco     = "document.getElementById('oBusCod').focus()";
$lcObjetoFoco     = "";
$llDivTitulo      = true;
$llDivExplicacion = false;
$llDivBotones_Sup = false;
$llDivBotones     = ($llPantallaBusqueda OR $llPantallaPedidos OR $oBusTip == 'IN');
$llDivBotones_Inf = $llDivBotones;
$llDivPie         = false;
$llDivHiddenDown  = true;
//OTRAS CAPAS
//CONFIGURACION DE EXPORTACION EXCEL
if ($llExpExcel) {
    $llDivExplicacion = false;
    $llDivBotones     = false;
    $llDivHiddenDown  = false;
}
//-FIN CONFIGURACION CONTENIDO

?>

<body onLoad="<?php echo $lcObjetoFoco; ?>">
<form id="frmDatos" name="frmDatos" method="post" action="">
    <INPUT type="hidden" id="pSession" name="pSession" value="<?php echo $pSession; ?>">
    <INPUT type="hidden" id="pUrl" name="pUrl" value="">
    <INPUT type="hidden" id="oAccion" name="oAccion" value="">
    <input type="hidden" id="oDetalle" name="oDetalle" value="">
    <!-- CAMPOS DEL FORMULARIO -->
    <input type="hidden" id="oBusTip" name="oBusTip" value="<?php echo $oBusTip; ?>" />
    <input type="hidden" id="oSelPro" name="oSelPro" value="" />
    <!-- FIN CAMPOS DEL FORMULARIO -->
    <script type="text/javascript" src="<?php echo $lcDirBase; ?>Conf/wz_tooltip.js"></script>
    <div class="my-account todo-el-ancho">

        <?php if ($llDivTitulo) { ?>
            <div class="box-title">
                <h2 class="box-title"><?php echo strtoupper($lcTitPan); ?></h2>
            </div>
        <?php } ?>


        <?php if ($llDivBotones) { ?>
            <br />
            <div id="dvBotones" style="text-align:center">
                <table border="0" cellspacing="2" cellpadding="0" width="100%">
                    <tr>
                        <td height="25"
                            align="center"
                            bgcolor="<?php echo $lcOpt1_Fondo; ?>"><?php echo $lcEnlace_Indice; ?></td>
                        <td height="25"
                            align="center"
                            bgcolor="<?php echo $lcOpt1_Fondo; ?>"><?php echo $lcEnlace_BusPro; ?></td>
                        <td align="center"
                            bgcolor="<?php echo $lcOpt2_Fondo; ?>"><?php echo $lcEnlace_BusArt; ?></td>
                        <td align="center"
                            bgcolor="<?php echo $lcOpt3_Fondo; ?>"><?php echo $lcEnlace_Pedidos; ?></td>
                    </tr>
                </table>
            </div>
        <?php } ?>


        <?php if ($llExplicacion) { ?>
            <br />
            <div id="dvExplicacion" style="height:40px">
            </div>
        <?php } ?>


        <?php if ($llPantallaBusqueda) {

            /* COMAFE */
            ?>

            <br />
            <div id="dvContenido" style="text-align:center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <?php
                    if ($llBuscarPorArticulo) {
                        $lcTipBusPan = 'AR';
                        ?>
                        <tr>
                            <td width="125" height="25" align="left" class="">
                                <span class="intranet-Text">REFERENCIA</span></td>
                            <td align="left"><input id="oBusProvRef"
                                                    name="oBusProvRef"
                                                    type="text"
                                                    title=""
                                                    class="text_edi_tv"
                                                    tabindex="1"
                                                    value="<?php echo $oBusProvRef; ?>"
                                                    size="20"
                                                    maxlength="20"
                                                    style="width:100px"
                                                    onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageSelf; ?>','B')" />
                            </td>
                        </tr>
                        <tr>
                            <td height="25" align="left" class=""><span class="intranet-Text">EAN</span></td>
                            <td align="left"><input id="oBusProvEan"
                                                    name="oBusProvEan"
                                                    type="text"
                                                    title=""
                                                    class="text_edi_tv"
                                                    tabindex="2"
                                                    value="<?php echo $oBusProvEan; ?>"
                                                    size="20"
                                                    maxlength="60"
                                                    style="width:140px"
                                                    onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageSelf; ?>','B')" />
                            </td>
                        </tr>
                        <tr>
                            <td height="25" align="left" class=""><span class="intranet-Text">DESCRIPCION</span>
                            </td>
                            <td align="left"><input id="oBusProvDes"
                                                    name="oBusProvDes"
                                                    type="text"
                                                    title=""
                                                    class="text_edi_tv"
                                                    tabindex="3"
                                                    value="<?php echo $oBusProvDes; ?>"
                                                    size="60"
                                                    maxlength="60"
                                                    style="width:200px"
                                                    onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageSelf; ?>','B')" />
                            </td>
                        </tr>
                        <?php
                    } else {
                        $lcTipBusPan = 'PR';
                        ?>
                        <tr>
                            <td width="100" height="25" align="left" class="">
                                <span class="intranet-Text">CODIGO</span></td>
                            <td align="left"><input id="oBusProvCod"
                                                    name="oBusProvCod"
                                                    type="text"
                                                    title=""
                                                    class="text_edi_tv"
                                                    tabindex="1"
                                                    value="<?php echo $oBusProvCod; ?>"
                                                    size="6"
                                                    maxlength="4"
                                                    style="width:60px"
                                                    onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageSelf; ?>','B')" />
                            </td>
                        </tr>
                        <tr>
                            <td height="25" align="left" class=""><span class="intranet-Text">RAZON SOCIAL</span>
                            </td>
                            <td align="left"><input id="oBusProvRaz"
                                                    name="oBusProvRaz"
                                                    type="text"
                                                    title=""
                                                    class="text_edi_tv"
                                                    tabindex="2"
                                                    value="<?php echo $oBusProvRaz; ?>"
                                                    size="60"
                                                    maxlength="60"
                                                    style="width:200px"
                                                    onKeyPress="fEvaluarTecla(event, document.frmDatos,'_self','<?php echo $lcNomPageSelf; ?>','B')" />
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>

            <?php if ($lcMensajeProceso <> '') { ?>
                <br />
                <div id="dvMensajes" style="text-align:center">
                    <span class="intranet-LetraError"><?php echo $lcMensajeProceso; ?></span>
                </div>
            <?php } ?>

            <?php if ($llDivBotones_Inf) { ?>
                <br />
                <div id="dvBotonesInf" style="text-align:center" class="botonera">
                    <button id="oBuscar"
                            type="button"
                            class="button"
                            tabindex="3"
                            onClick="fAccion ('', '', 'B')"><span><span>  Buscar  </span></span></button>
                </div>
            <?php } ?>

            <br />
            <div id="dvProveedores" style="OVERFLOW: auto; HEIGHT: 250px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <?php

                    foreach ($arrProveedores as $row) {

                        //if ($llDepurar) { pintaMensajeDepuracion($row); }

                        $lcProCod   = $row['CDPROVE'];
                        $lcProDes   = $row['RAZON'];
                        $lcProDes   = str_replace('?', 'Ñ', $lcProDes);
                        $lcProWeb   = $row['WEB'];
                        $lcProFex   = '';
                        $lnIdPlaAct = isset($row['IDPLANTILLA']) ? $row['IDPLANTILLA'] : 0;

                        $lcEnlace = '<img src="' . $Rutas['Imagenes'] . 'btnFicha.gif" alt="Ver los descuentos del proveedor seleccionado" width="25" height="20" border="0" onClick="' . "fAccionDetalle ('','" . $lcNomPageFic . "'," . $lcProCod . ",'P')" . '" />';
                        $lcEnlace = $lnIdPlaAct <> 0 ? $lcEnlace : '';
                        $llPintar = $lnIdPlaAct <> 0 ? true : false;

                        if ($llPintar) {

                            $lnNumReg = $lnNumReg + 1;

                            if (($lnNumReg % 2) == 0) {
                                $lcClaFil = '';
                            } else {
                                $lcClaFil = 'intranet-FilaMarca_Amarilla';
                            }

                            ?>
                            <tr>
                                <!--<img src="<?php echo $Rutas['Imagenes']; ?>btnFicha.gif" alt="Ver los descuentos del proveedor seleccionado" width="25" height="20" border="0" onClick="fAccionDetalle ('','<?php echo $lcNomPageFic; ?>',<?php echo $lcProCod; ?>,'P')" />-->
                                <td width="7%"
                                    align="center"
                                    valign="top"
                                    class="<?php echo $lcClaFil; ?>"><?php echo $lcEnlace; ?></td>
                                <td width="7%" align="center" valign="top" class="<?php echo $lcClaFil; ?>">
                                    <span class="intranet-Text"><?php echo $lcProCod; ?></span></td>
                                <td width="51%" align="left" valign="top" class="<?php echo $lcClaFil; ?>"><span
                                            class="intranet-Text"><?php echo $lcProDes; ?></span></td>
                                <td width="32%" align="left" valign="top" class="<?php echo $lcClaFil; ?>"><span
                                            class="intranet-Text"><?php echo $lcProWeb; ?></span></td>
                                <td width="5%" align="center" valign="top" class="<?php echo $lcClaFil; ?>">
                                    <span class="intranet-Text"><?php echo $lcProFex; ?></span></td>
                                <td width="5%"
                                    align="left"
                                    valign="top"
                                    class="<?php echo $lcClaFil; ?>"><?php echo $lcEnlace; ?></td>
                            </tr>
                            <?php
                        }
                    }

                    ?>
                </table>
            </div>

        <?php } ?>
        .

        <?php if ($llPantallaResumen) {

            /* FERRCASH */

            ?>
            <br />
            <div style="OVERFLOW: auto; WIDTH: 100%; HEIGHT: 450px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                    <?php
                    $oProveedor = new WebProveedor();
                    $oProveedor->depurarObjeto($llDepurar);
                    $arrParameter['empresa']             = $pgIdentificadorEmpresa;
                    $arrParameter['tipoCliente']         = $lTipoCliente;
                    $arrParameter['filtroCodigoCliente'] = '';
                    $arrParameter['filtroCodigoRazon']   = '';
                    $lOrden                              = "NOMCOMER";
                    $arrProveedores                      = $oProveedor->obtenerRegistrosConDirectos(
                        Conexion::getInstancia(),
                        $arrParameter,
                        $lOrden
                    );

                    $gnNumCol = 3;
                    $lnAncCol = (int) (100 / $gnNumCol);
                    $lnNumReg = 0;
                    $lnRegFil = 0;
                    $lcPriLet = '';
                    $lcLetAnt = '';

                    foreach ($arrProveedores as $aProveedor) {

                        $llPintar = false;
                        $llCambio = false;
                        $lcProCod = $aProveedor['CDPROVE'];
                        $lcRazon  = '';

                        if (!empty(Helper::getFromArray($aProveedor, 'IDPLANTILLA'))) {
                            $llPintar = true;
                            $lnRegFil = $lnRegFil + 1;
                            $lcRazon  = trim($aProveedor['NOMCOMER']);
                            $lcPriLet = substr($lcRazon, 0, 1);
                        }

                        if ($llPintar) {
                            $lcColor = '';

                            if ($lcLetAnt <> '') {
                                if ($lcPriLet <> $lcLetAnt) {
                                    $llCambio = true;
                                    if ($lcColor == 'yellow') {
                                        $lcColor = '';
                                    } else {
                                        $lcColor = 'yellow';
                                    }
                                    $lcLetAnt = $lcPriLet;
                                    if ($lnRegFil <> 1) {
                                        echo '</tr>';
                                        echo '<tr><td height="15"></td></tr>';
                                        $lnRegFil = 1;
                                    }
                                }
                            } else {
                                $lcLetAnt = $lcPriLet;
                                $llCambio = true;
                            }

                            if (($lnRegFil == 1)) {
                                echo '<tr>';
                                $lcPintar   = '';
                                $lcColorLet = '';
                                if ($llCambio) {
                                    $lcPintar   = '<span style="color:white">' . $lcPriLet . '</span>';
                                    $lcColorLet = 'black';
                                }
                                echo '<td width="3%" style="background-color:' . $lcColorLet . '" align="center">' . $lcPintar . '</td>';
                            }

                            echo '<td width="' . $lnAncCol . '%" valign="top" align="left" style="background-color:' . $lcColor . '">&nbsp;';

                            ?><img src="<?php echo $Rutas['Imagenes']; ?>btnFicha.gif"
                                   alt="Ver los descuentos del proveedor seleccionado"
                                   width="25"
                                   height="20"
                                   border="0"
                                   onClick="fAccionDetalle ('','<?php echo $lcNomPageFic; ?>',<?php echo $lcProCod; ?>, 'P')"
                                   class="pondedo" /><?php
                            echo '<span>' . $lcRazon . '</span>';
                            //echo '</a>';
                            echo '</td>';

                            if ($lnRegFil == $gnNumCol) {
                                $lnRegFil = 0;
                                echo '</tr>';
                                echo '<tr><td height="10"></td><tr>';
                            }
                        }
                        ?>

                    <?php } ?>

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
<?php if ($conBdMagTmp) {
    fgCerrarConexion($conBdMagTmp);
} ?>
</body>
<?php if ($llDivHiddenDown) { ?>
    <iframe id="frmDescarga" name="frmDescarga" style="visibility:hidden" height="1" width="1"></iframe><?php } ?>
</html>
