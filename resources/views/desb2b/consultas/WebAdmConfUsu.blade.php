<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase = '../';                                             // COMO IR AL DIRECTORIO BASE
$llDepurar = false;                                           // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan = 'Configuracion';                                 // TITULO DE LA PAGINA
$lcSubtiit1 = 'General';                                       // SUBTITULO DE LA PAGINA 1
$lcSubtiit2 = 'Pedidos';                                       // SUBTITULO DE LA PAGINA 2
$lcSubtiit3 = 'Conformidad (para proveedor)';                  // SUBTITULO DE LA PAGINA 3
$lcSubtiit4 = 'Correos';                                       // SUBTITULO DE LA PAGINA 4 (FASE 2)
$llExpExcel = false;                                           // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc = 'Configuración.XLS';                             // NOMBRE DEL FICHERO EXCEL A GENERAR
$lcNomPagina = mb_strtolower($_SERVER['PHP_SELF']);             // NOMBRE DE LA PAGINA PARA ESTABLECER PERMISOS
$lnPos = mb_strrpos($lcNomPagina, '/');
if ($lnPos <> 0) {
    $lcNomPagina = mb_substr($lcNomPagina, $lnPos + 1);
}


//+Variables
$pgIdentificadorEmpresa = '';
$pSession = '';
$pIdMagento = '';
$oAccion = '';
$UserID = '';
$UserSuc = '';
$DBTableNames = [];
$laDatosClienteConf = [];
$Rutas = [];
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
global $llDivBotones_Inf;
global $rowConfEmp;
global $laDatosCliente;
global $laDatosClienteConf;
global $mail1;
global $mail2;
global $mail3;
//Variables de pagina
$lcEmailCliente = '';
//-Variables

//+REQUIRES POR DEFECTO y PSESSIONS
include_once $lcDirBase . 'jed_paratube.php';
//-REQUIRES POR DEFECTO y PSESSIONS

//+REQUIRES_OPCIONALES
//-REQUIRES_OPCIONALES

//+CONEXION BD
//-CONEXION BD

//+CAPTURO EL CODIGO DE USUARIO
$pIdMagento = isset($pIdMagento) ? $pIdMagento : -1;
$lnIdUsuMagento = $pIdMagento;
//-CAPTURO EL CODIGO DE USUARIO

//+VALIDACION ACCESOS
//(1=ok		0=Sin validacion	-1=Usuario no permitido		-2=actualizando datos		-3=opcion no configurada)
$lnStatus = 0; // 1 o 0 o -1 o -2 o -3
$llValidarUsuario = true;
$llSoloUserDepurador = false;
$llValidar_TiendaFerrokey = false;
$llVistaEspecial = false;


include_once $lcDirBase . 'jed_valuso.php';


// VERIFICAR SI EL USUARIO ES DEPURADOR.
$llDepurar = Helper::getFromArray($laDatosClienteConf, 'ADM_INFOR_DBG');
//-VALIDACION ACCESOS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////                           PAGINA VARIABLE                                  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//+PAGINAS RESPUESTAS
//$lcNomPageAct = $Rutas['Base_Intranet_Ww'] . 'configuracion.php';
$lcNomPagina = $Rutas['Base_Intranet_Ww'] . 'Socios/configuracion.php';
//-PAGINAS RESPUESTAS


//+CAPTURAR VARIABLES FORMULARIO EN CASO DE HACER EL POST
$pSession = isset($_POST ['pSession']) ? $_POST ['pSession'] : '';
$oEmpresa = isset($_POST ['oEmpresa']) ? $_POST ['oEmpresa'] : '';
$oIndVerOkHjp = isset($_POST ['oIndVerOkHjp']) ? $_POST ['oIndVerOkHjp'] : '';
$oMargenPvpr = isset($_POST ['oMargenPvpr']) ? $_POST ['oMargenPvpr'] : '';
$oIndSustitutivo = isset($_POST ['oIndSustitutivo']) ? $_POST ['oIndSustitutivo'] : '';
$oPorcenSusti = isset($_POST ['oPorcenSusti']) ? $_POST ['oPorcenSusti'] : '';
$oindConfRellPeds = isset($_POST ['oindConfRellPeds']) ? $_POST ['oindConfRellPeds'] : '';
$oindConfRellSuc = isset($_POST ['oindConfRellSuc']) ? $_POST ['oindConfRellSuc'] : '';
$oindConfRellAdj = isset($_POST ['oindConfRellAdj']) ? $_POST ['oindConfRellAdj'] : '';
$oEmailB2C = isset($_POST ['oEmailB2C']) ? $_POST ['oEmailB2C'] : '';
$oEmail = isset($_POST ['oEmail']) ? $_POST ['oEmail'] : '';
$oEmailPedidos = isset($_POST ['oEmailPedidos']) ? $_POST ['oEmailPedidos'] : '';
$oProntoPago = isset($_POST ['oProntoPago']) ? $_POST ['oProntoPago'] : '';
$oformatoFacturaDocumento = isset($_POST ['oformatoFacturaDocumento']) ? $_POST ['oformatoFacturaDocumento'] : '';
$oIdMail = isset($_POST ['oIdMail']) ? $_POST ['oIdMail'] : '';
$oMail = isset($_POST ['oMail']) ? $_POST ['oMail'] : '';
$oDetalle = isset($_POST ['oDetalle']) ? $_POST ['oDetalle'] : '';
$oIdRegistro = isset($_POST ['oIdRegistro']) ? $_POST ['oIdRegistro'] : '';


//-FIN CAPTURAR VARIABLES FORMULARIO
//Iniutils::escribeEnLog(__FILE__ . " ==> oAccion= {$oAccion} / oEmpresa = {$oEmpresa}");


//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Grupo = 'ADMIN';
$lcLog_Texto = '';
$mail1 = 0;
$pintaPrincipal = true;
$pintaDetalleMail = false;
$grabado = false;
$verdad = true;
$pintaGrabar = false;
$pintaModificar = false;

$oConfiguracionEmailUsu = new WebAdmEmailUsuarios();
$oConfiguracionEmail = new WebAdmEmail();
$oConfiguracionSocios = new WebSociosSucs();
$oConfiguracionUsu = new WebAdmConfUsu();

//GRABA PRINCIPAL
if ($oAccion == 'G') {
    //
    $oConfiguracionSocios->cdclien($UserID);
    $oConfiguracionSocios->cdsucur($UserSuc);
    $oConfiguracionSocios->indicadorProntoPagoDirecto($oProntoPago);
    $oConfiguracionSocios->formatoFacturaDocumento($oformatoFacturaDocumento);
    $oConfiguracionSocios->guardar(Conexion::getInstancia());
    $oConfiguracionUsu->idmagento($pIdMagento);
    $oConfiguracionUsu->cdclien($UserID);
    $oConfiguracionUsu->cdsucur($UserSuc);
    $oConfiguracionUsu->empresa($pgIdentificadorEmpresa);
    $oConfiguracionUsu->indVerOkHjp($oIndVerOkHjp);
    $oConfiguracionUsu->margenPvpr($oMargenPvpr);
    $oConfiguracionUsu->indSustitutivo($oIndSustitutivo);
    $oConfiguracionUsu->porcenSusti($oPorcenSusti);
    $oConfiguracionUsu->indConfRellPeds($oindConfRellPeds);
    $oConfiguracionUsu->indConfRellSuc($oindConfRellSuc);
    $oConfiguracionUsu->indConfRellAdj($oindConfRellAdj);
    $oConfiguracionUsu->guardar(Conexion::getInstancia());
}


//NUEVO MAIL -> te lleva a la página
if ($oAccion == 'NM') {
    $pintaGrabar = true;
    $pintaPrincipal = false;
    $oConfiguracionEmail->idemail(1);
    $oConfiguracionEmail->obtenerPorId(Conexion::getInstancia());
}

//NUEVO MAIL PRINCIPAL -> carga el mail
if ($oAccion == 'NMP') {
    $pintaGrabar = true;
    $pintaPrincipal = false;
    $oConfiguracionEmail->idemail($oIdMail);
    $oConfiguracionEmail->obtenerPorId(Conexion::getInstancia());
}

//NUEVO MAIL -> graba
if ($oAccion == 'NMG') {

    $oConfiguracionEmail->idemail($oIdMail);
    $oConfiguracionEmail->obtenerPorId(Conexion::getInstancia());


    if (preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $oMail)) {
        $verdad = true;
    } else {
        $verdad = false;
    }
    if ($verdad) {
        $oConfiguracionEmailUsu->idemail($oIdMail);
        $oConfiguracionEmailUsu->email($oMail);
        $oConfiguracionEmailUsu->cdclien($UserID);
        $oConfiguracionEmailUsu->empresa($pgIdentificadorEmpresa);
        $oConfiguracionEmailUsu->idmagento($pIdMagento);
        $oConfiguracionEmailUsu->cdsucur($UserSuc);
        $oConfiguracionEmailUsu->guardar(Conexion::getInstancia());
        $oMail = $oConfiguracionEmailUsu->email();
        $pintaPrincipal = true;
        $pintaDetalleMail = false;
        $pintaGrabar = false;
        $pintaModificar = false;
    } else {
        $pintaGrabar = true;
        $pintaPrincipal = false;
    }
}

//MODIFICA MAIL PRINCIPAL -> carga el mail
if ($oAccion == 'MM') {
    $pintaModificar = true;
    $pintaPrincipal = false;

    $oConfiguracionEmailUsu->idregistro($oDetalle);
    $oConfiguracionEmailUsu->obtenerPorId(Conexion::getInstancia());

    $oConfiguracionEmail->idemail($oConfiguracionEmailUsu->idemail());
    $oConfiguracionEmail->obtenerPorId(Conexion::getInstancia());
}

//MODIFICAR MAIL ->graba
if ($oAccion == 'MMG') {
    $modificado = false;
    $pintaModificar = true;
    $pintaPrincipal = false;
    if (preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $oMail)) {
        $verdad = true;
    } else {
        $verdad = false;
    }
    if ($verdad) {
        $oConfiguracionEmailUsu->idregistro($oIdRegistro);
        $oConfiguracionEmailUsu->obtenerPorId(Conexion::getInstancia());
        $oConfiguracionEmailUsu->idemail();
        $oConfiguracionEmailUsu->email($oMail);
        $oConfiguracionEmailUsu->cdclien();
        $oConfiguracionEmailUsu->empresa();
        $oConfiguracionEmailUsu->idmagento();
        $oConfiguracionEmailUsu->cdsucur();
        $oConfiguracionEmailUsu->guardar(Conexion::getInstancia());
        $oConfiguracionEmail->idemail($oConfiguracionEmailUsu->idemail());
        $oConfiguracionEmail->obtenerPorId(Conexion::getInstancia());
        $modificado = true;
    } else {
        $oConfiguracionEmailUsu->idregistro($oIdRegistro);
        $oConfiguracionEmailUsu->obtenerPorId(Conexion::getInstancia());
        $oConfiguracionEmail->idemail($oConfiguracionEmailUsu->idemail());
        $oConfiguracionEmail->obtenerPorId(Conexion::getInstancia());
    }
}

//MODIFICAR MAIL ->borra
if ($oAccion == 'D') {
    $oConfiguracionEmailUsu->idregistro($oDetalle);
    $oConfiguracionEmailUsu->eliminar(Conexion::getInstancia());
    $pintaPrincipal = true;
    $pintaDetalleMail = false;
    $grabado = false;
    $pintaGrabar = false;
    $pintaModificar = false;
}


$where = "CDCLIEN=" . $UserID . " and CDSUCUR=" . $UserSuc;
$datos = $oConfiguracionSocios->obtenerRegistros(Conexion::getInstancia(), $where, '', '');
foreach ($datos as $dato) {
    $oProntoPago = $dato["INDICADOR_PRONTO_PAGO_DIRECTO"];
    $oformatoFacturaDocumento = $dato["FORMATO_FACTURA_DOCUMENTO"];
}


$where = "CDCLIEN=" . $UserID . " and CDSUCUR=" . $UserSuc . " and IDMAGENTO=" . $pIdMagento . " and EMPRESA='" . $pgIdentificadorEmpresa . "'";
$datos = $oConfiguracionUsu->obtenerRegistros(Conexion::getInstancia(), $where, '', '');
foreach ($datos as $dato) {
    $oIndVerOkHjp = $dato["IND_VER_OK_HJP"];
    $oMargenPvpr = $dato["MARGEN_PVPR"];
    $oIndSustitutivo = $dato["IND_SUSTITUTIVO"];
    $oPorcenSusti = $dato["PORCEN_SUSTI"];
    $oindConfRellPeds = $dato["IND_CONF_RELL_PEDS"];
    $oindConfRellSuc = $dato["IND_CONF_RELL_SUC"];
    $oindConfRellAdj = $dato["IND_CONF_RELL_ADJ"];
}


//-ACCIONES

//+LOG
$lSuc = $UserSuc;
if ($UserSuc == '') {
    $lSuc = '0';
}
AccesoDatos::grabarLog(Conexion::getInstancia(), $pgIdentificadorEmpresa, $UserID, $lSuc, $lcLog_Grupo, $lcLog_Texto,
    $lnIdUsuMagento);
//-LOG

//+CONFIGURACION CONTENIDO
$lcAyuTex = '* Ejemplo.<BR>';
$lcAyuTex .= '* <B>Ejemplo</B>.<BR>';
$lcAyudaPantalla_P = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco = "document.getElementById('oBusCod').focus()";
$lcObjetoFoco = "";
$llDivTitulo = true;
$llDivBotones_Sup = true;
$llDivBotones_Inf = true;
$llDivExplicacion = true;
$llDivBotones = true;
$llDivPie = false;
$llDivHiddenDown = false;
$llDivExplicacion = true;
//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();


//-CONFIGURACION CONTENIDO
?>

<body>
<form id="frmDatos" name="frmDatos" method="post" action="">
    <input type="hidden" id="pUrl" name="pUrl" value="">
    <input type="hidden" id="oAccion" name="oAccion" value="">
    <input type="hidden" id="oParam1" name="oParam1" value="">
    <input type="hidden" id="oParam2" name="oParam2" value="">
    <input type="hidden" id="oParam3" name="oParam3" value="">
    <input type="hidden" id="oParam4" name="oParam4" value="">
    <input type="hidden" id="oParam5" name="oParam5" value="">
    <input type="hidden" id="oDetalle" name="oDetalle" value="">
    <input type="hidden" id="pIdMagento" name="pIdMagento" value="<?php echo $pIdMagento; ?>">
    <!-- CAMPOS DEL FORMULARIO -->
    <!-- FIN CAMPOS DEL FORMULARIO -->
    <script type="text/javascript" src="<?php echo $lcDirBase; ?>Conf/wz_tooltip.js"></script>
    <!-- TITULO SECTOR -->
    <div class="box-title">
        <h2 style="float:left;padding-top:5px; color:#fff"
            class="box-title"><?php echo mb_strtoupper($lcTitPan); ?></h2>
        <?php if ($pintaPrincipal) { ?>
        <i style="float:right;padding-top:5px; color:#fff" class="fa fa-question fa-icono-header" title=""
           onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
        <i style="float:right;padding-top:5px; color:#fff" class="fa fa-floppy-o  fa-icono-header"
           onClick="fAccion ('', '<?php echo $lcNomPagina; ?>', 'G');"></i>
        <?php } ?>
        <?php if ($pintaGrabar) { ?>
        <i style="float:right;padding-top:5px;color:#fff" class="fa fa-question fa-icono-header" title=""
           onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
        <i style="float:right;padding-top:5px;color:#fff" class="fa fa-arrow-left  fa-icono-header"
           title="Nuevo registro" onClick="fAccion ('', '<?php echo $lcNomPagina; ?>', );"></i>
        <i style="float:right;padding-top:5px;color:#fff" class="fa fa-floppy-o fa-icono-header"
           onClick="fAccion ('', '<?php echo $lcNomPagina; ?>', 'NMG');"></i>
        <?php } ?>
        <?php if ($pintaModificar) { ?>
        <i style="float:right;padding-top:5px;color:#fff" class="fa fa-question fa-icono-header" title=""
           onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
        <i style="float:right;padding-top:5px;color:#fff" class="fa fa-arrow-left  fa-icono-header"
           title="Nuevo registro" onClick="fAccion ('', '<?php echo $lcNomPagina; ?>', );"></i>
        <i style="float:right;padding-top:5px;color:#fff" class="fa fa-floppy-o fa-icono-header"
           onClick="fAccion ('', '<?php echo $lcNomPagina; ?>', 'MMG');"></i>
        <?php } ?>
    </div>
    <br><br/>
    <!--# TITULO SECTOR -->
    <!-- PRIMER SECTOR -->
    <div class="my-account todo-el-ancho"
    <?php if ($pintaPrincipal) { ?>

    <div class="box-title"
         style="display: flex; flex-flow: row wrap;">
        <div style="display: flex: 1 auto;width:200%;">
            <h2 class="box-title"><?php echo mb_strtoupper($lcSubtiit1); ?></h2>
        </div>
    </div>

    </div>
    <br>
    <table style="width:200%">
        <tr>

            <td align="left">
                    <span>Aprovechar pronto pago en directo &nbsp;
                        <select type="select" id="oProntoPago" name="oProntoPago"
                                style="width: 150px; text-align:left"
                                title="">
                            <option value="1" <?php echo $oProntoPago == 1 ? 'selected="selected"' : ''; ?>>
                                NO
                            </option>
                            <option value="0" <?php echo $oProntoPago == 0 ? 'selected="selected"' : ''; ?>>
                                SI
                            </option>
                        </select>
                    </span>
            </td>

            <td align="left">
                    <span>Formato de las facturas&nbsp;&nbsp;
    <select type="select" id="oformatoFacturaDocumento" name="oformatoFacturaDocumento"
            style="width: 150px; text-align:left"
            title="">
    <option value="PAPEL"<?php if ($oformatoFacturaDocumento == "PAPEL") {
        print ' SELECTED';
    } ?>>PAPEL
    </option>
    <option value="E-MAIL"<?php if ($oformatoFacturaDocumento == "E-MAIL") {
        print ' SELECTED';
    } ?>>E-MAIL
    </option>

    </select>
                    </span>
            </td>
        </tr>
    </table>
    <br>
    <!--# PRIMER SECTOR -->
    <!-- SEGUNDO SECTOR -->
    <div class="box-title"
         style="display: flex; flex-flow: row wrap;">
        <div style="display: flex: 1 auto;">
            <h2 class="box-title"><?php echo mb_strtoupper($lcSubtiit2); ?></h2>
        </div>
    </div>
    <br>
    <table>
        <tr>
            <td style="width:18%">
                <span>Enviar en el mail de pedidos los  articulos grabados sin incidencias</span>
            </td>

            <td style="width:18%">
                <span>Margen de precios cerrados en web venta  </span>
            </td>


            <td style="width:18%">
                <span>Usar sustitutivo en los pedidos via fichero  </span>
            </td>


            <td style="width:18%">
                <span>Porcentaje limite para el cambio de articulos   </span>
            </td>
        </tr>
        <tr>
            <td>
                <select type="select" id="oIndVerOkHjp" name="oIndVerOkHjp"
                        style="width: 70px; text-align:left">
                    <option value="1" <?php echo $oIndVerOkHjp == 1 ? 'selected="selected"' : ''; ?>>
                        SI
                    </option>
                    <option value="0" <?php echo $oIndVerOkHjp == 0 ? 'selected="selected"' : ''; ?>>
                        NO
                    </option>
                </select>
            </td>
            <td>
                <select type="select" id="oMargenPvpr" name="oMargenPvpr"
                        style="width: 70px; text-align:left"
                        title="">
                    <option value="1" <?php echo $oMargenPvpr == 1 ? 'selected="selected"' : ''; ?>>
                        SI
                    </option>
                    <option value="0" <?php echo $oMargenPvpr == 0 ? 'selected="selected"' : ''; ?>>
                        NO
                    </option>
                </select>
            </td>
            <td>
                <select type="select" id="oIndSustitutivo" name="oIndSustitutivo"
                        style="width: 70px; text-align:left"
                        title="">
                    <option value="1" <?php echo $oIndSustitutivo == 1 ? 'selected="selected"' : ''; ?>>
                        SI
                    </option>
                    <option value="0" <?php echo $oIndSustitutivo == 0 ? 'selected="selected"' : ''; ?>>
                        NO
                    </option>
                </select>

            </td>
            <td>
                <select type="select" id="oPorcenSusti" name="oPorcenSusti"
                        style="width: 70px; text-align:left"
                        title="">
                    <option value="1" <?php echo $oPorcenSusti == 1 ? 'selected="selected"' : ''; ?>>
                        SI
                    </option>
                    <option value="0" <?php echo $oPorcenSusti == 0 ? 'selected="selected"' : ''; ?>>
                        NO
                    </option>
                </select>
            </td>
        </tr>
    </table>
    <br>
    <!--# SEGUNDO SECTOR -->
    <!-- TERCER SECTOR -->
    <div class="box-title"
         style="display: flex; flex-flow: row wrap;">
        <div style="display: flex: 1 auto;">

            <h2 class="box-title"><?php echo mb_strtoupper($lcSubtiit3); ?></h2>
        </div>
    </div>
    <br>

    <table style="width:200%">
        <tr>
            <td>
                <span>Obliga a rellenar el pedido venta del socio </span>
            </td>
            <td>
                <span>Obliga a teclear la sucursal   </span>
            </td>
            <td>
                <span>Obligar a adjuntar el fichero en la conformidad  </span>
            </td>
        </tr>
        <tr>
            <td>
                <select type="select" id="oindConfRellPeds" name="oindConfRellPeds"
                        style="width: 70px; text-align:left"
                        title="">
                    <option value="1" <?php echo $oindConfRellPeds == 1 ? 'selected="selected"' : ''; ?>>
                        SI
                    </option>
                    <option value="0" <?php echo $oindConfRellPeds == 0 ? 'selected="selected"' : ''; ?>>
                        NO
                    </option>
                </select>
            </td>
            <td>
                <select type="select" id="oindConfRellSuc" name="oindConfRellSuc"
                        style="width: 70px; text-align:left"
                        title="">
                    <option value="1" <?php echo $oindConfRellSuc == 1 ? 'selected="selected"' : ''; ?>>
                        SI
                    </option>
                    <option value="0" <?php echo $oindConfRellSuc == 0 ? 'selected="selected"' : ''; ?>>
                        NO
                    </option>
                </select>
            </td>
            <td>
                <select type="select" id="oindConfRellAdj" name="oindConfRellAdj"
                        style="width: 70px; text-align:left"
                        title="">
                    <option value="1" <?php echo $oindConfRellAdj == 1 ? 'selected="selected"' : ''; ?>>
                        SI
                    </option>
                    <option value="0" <?php echo $oindConfRellAdj == 0 ? 'selected="selected"' : ''; ?>>
                        NO
                    </option>
                </select>
            </td>
        </tr>
    </table>
    <br>
    <!--# TERCER SECTOR -->
<!--  CUARTO SECTOR
        Demomento lo mantenemos dehabilitado
        <div class="box-title">
            <h2 style="float:left;padding-top:5px;" class="box-title"><?php //echo mb_strtoupper($lcSubtiit4); ?></h2>
            <i style="float:right;padding-top:10px;" class="fa fa-plus fa-icono-header"
               onClick="fAccion ('', '<?php //echo $lcNomPagina; ?>', 'NM');"></i>
        </div>
        <br>
        <?php
    //$datos = $oConfiguracionEmailusu->obtenerRegistros(Conexion::getInstancia(), 'CDCLIEN=' . $UserID, '');
    ?>

        <div class="table-flex" style="border: 1px solid #B5BF00;">
            <div class="tr-flex" style="color: #ffffff;background-color:<?php echo "#{$colorCorporativo}"; ?>">
                <div class="td-flex" style="justify-content: center;flex-grow:3;"><span>EMAIL</span></div>
                <div class="td-flex" style="justify-content: center;flex-grow:5"><span>DESCRIPCION</span></div>
                <div class="td-flex" style="justify-content: left;flex-grow:5;"><span>USO</span></div>
                <div class="td-flex" style="justify-content: center"><span></span></div>
                <div class="td-flex" style="justify-content: center;"><span>BORRAR</span></div>
                <div class="td-flex" style="justify-content: center;"><span>MODIFICAR</span></div>
            </div>


            <?php
    //foreach ($datos as $dato) {
    ?>

        <div class="tr-flex">
            <div class="td-flex" style="justify-content: center;flex-grow:3;">
                    <span>
                            <a href="#"
                               onClick="javascript:fAccionDetalle('','<?php // echo $lcNomPagina; ?>','<?php // echo $dato["IDREGISTRO"] ?>','MM');">
                                        <?php //echo $dato["EMAIL"] ?>
        </a>
    </span>
</div>
<div class="td-flex" style="justify-content: center;flex-grow:5;">
    <span><?php
    //$oConfiguracionEmail->idemail($dato["IDEMAIL"]);
    //$oConfiguracionEmail->obtenerPorId(Conexion::getInstancia());
    //echo $oConfiguracionEmail->descripcion();
    ?>
        </span>
</div>
<div class="td-flex" style="justify-content: left;flex-grow:5;">
        <span><?php
    //echo $oConfiguracionEmail->uso();
    ?>
        </span>
</div>
<div class="td-flex" style="justify-content: center;">

</div>
<div class="td-flex" style="justify-content: center;cursor:pointer">
    <i class="fa fa-trash-o fa-lg"
       onClick="fAccionDetalle('','<?php // echo $lcNomPagina; ?>','<?php // echo $dato["IDREGISTRO"] ?>','D');">
                        </i>
                    </div>
                    <div class="td-flex" style="justify-content: center;cursor:pointer">
                        <i class="fa fa-floppy-o fa-lg"
                           onClick="fAccionDetalle('','<?php // echo $lcNomPagina; ?>','<?php //echo $dato["IDREGISTRO"] ?>','MM');">
                        </i>
                    </div>
                </div>

            <?php // } ?>
        </div>
        CUARTO SECTOR -->
    <!--  ALTA MAIL -->

    <?php } ?>
    <?php if ($pintaGrabar) { ?>
    <div style="width:100%;">
        <div style="width:30%;float:left">
            <span>DESCRIPCION</span><br>
            <select type="select" id="oIdMail" name="oIdMail" style="width: 300px; text-align:left" title=""
                    onchange="fAccion ('', '<?php echo $lcNomPagina; ?>', 'NMP','');">
                <?php

                $gruposUsuarios = $oConfiguracionEmail->obtenerRegistros(Conexion::getInstancia(), "", "", "");
                foreach ($gruposUsuarios as $gruposUsuario) {
                    if ($gruposUsuario["IDEMAIL"] == $oIdMail) {
                        echo "<option selected value='" . $gruposUsuario["IDEMAIL"] . "'>" . $gruposUsuario["DESCRIPCION"] . "</option>";
                    } else {
                        echo "<option value='" . $gruposUsuario["IDEMAIL"] . "'>" . $gruposUsuario["DESCRIPCION"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div style="width:10%;float:left;"></div>

        <div style="width:70%;float:left;">
            <span>USO</span>
            <h2><?php echo $oConfiguracionEmail->uso(); ?></h2>
        </div>
    </div>
    <br/>
    <div style="width:100%;">
        <?php if (!$grabado) { ?>
        <div style="width:80%;float:left">
            <span class="intranet-Text">EMAIL</span>
            <br>
            <input type="text"
                   title=""
                   id="oMail"
                   name="oMail"
                   style="width: 200px"/>
            <div>
                <?php if ($verdad == false) { ?>
                <span class="intranet-Text" style="color:red">Error formato de mail incorrecto</span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
<!--#  ALTA MAIL -->
    <!--  MODIFICACIONES MAIL -->
    <?php if ($pintaModificar) { ?>
    <?php if ($modificado) { ?>
    <h2 style="color: green"> Modificado</h2>
    <?php } ?>
    <div style="width:100%;">
        <div style="width:30%;float:left;">
            <h2 class="intranet-Text">DESCRIPCION</h2>
            <p>
                <?php echo $oConfiguracionEmail->descripcion(); ?>
            </p>
        </div>

        <div style="width:10%;float:left">

            <h2 class="intranet-Text">EMAIL</h2>
            <input type="text"
                   id="oMailM"
                   name="oMail"
                   style="width: 200px"
                   value='<?php echo $oConfiguracionEmailUsu->email(); ?>'/>
            <div>
                <?php if ($verdad == false) { ?>
                <span class="intranet-Text" style="color:red">Error formato de mail incorrecto</span>
                <?php } ?>
            </div>
        </div>
        <div style="width:70%;float:left;">
            <h2>USO</h2>
            <p>
                <?php echo $oConfiguracionEmail->uso(); ?>
            </p>
        </div>
    </div>
    <input type="hidden" id="oIdRegistro" name="oIdRegistro"
           value='<?php echo $oConfiguracionEmailUsu->idregistro(); ?>'>
    <?php } ?>
<!--#  MODIFICACIONES MAIL -->
    <?php if ($llDivPie) { ?>
    <br/>
    <div id="dvPie" style="height:40px">
    </div>

    <?php } ?>
    </div>
</form>
</body>



