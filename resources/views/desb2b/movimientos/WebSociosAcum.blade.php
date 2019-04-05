<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase        = '../../';                                       // COMO IR AL DIRECTORIO BASE
$llDepurar        = false;                                          // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan         = 'Consumo totalizado por tipo de venta / producto';  // TITULO DE LA PAGINA
$llExpExcel       = false;                                          // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc         = '';                                             // NOMBRE DEL FICHERO EXCEL A GENERAR
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
$lcFechaActualizacion = 'N/D';
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

include_once $lcDirBase . 'jed_valuso.php';

$llDepurar = $laDatosClienteConf['ADM_INFOR_DBG'];                // VERIFICAR SI EL USUARIO ES DEPURADOR
//-VALIDACION ACCESOS

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////                           PAGINA VARIABLE                                  /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//+PAGINAS RESPUESTAS
$lcNomPageAct = $Rutas['Base_Intranet_Ww'] . 'Socios/Movs/tot_Index.php';
//-

//+CAPTURAR VARIABLES FORMULARIO
$oTipCom = isset($_POST['oTipCom']) ? $_POST['oTipCom'] : '';
$oEjeDes = isset($_POST['oEjeDes']) ? $_POST['oEjeDes'] : '';
$oEjeHas = isset($_POST['oEjeHas']) ? $_POST['oEjeHas'] : '';
$oTotEje = isset($_POST['oTotEje']) ? $_POST['oTotEje'] : '';
//-FIN CAPTURAR VARIABLES FORMULARIO

//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Texto      = '';
$lcLog_Grupo      = 'SOCIOS';
//REGISTROS POR PAGINA (LISTADOS)
$lnEjerIni         = 2011;
$lnEjerFin         = date('Y');
$aConsumoEjercicio = [];
//Ejercicios disponibles
if ($rowConfEmp['ejerSocSegConIni'] <> '') {
    $lnEjerIni = (int) $rowConfEmp['ejerSocSegConIni'];
}
if ($rowConfEmp['ejerSocSegConFin'] <> '') {
    $lnEjerFin = (int) $rowConfEmp['ejerSocSegConFin'];
}
if (($lnEjerIni <> 0) and ($lnEjerFin <> 0)) {
    for ($lnCntYear = $lnEjerIni; $lnCntYear <= $lnEjerFin; $lnCntYear++) {
        $aConsumoEjercicio[$lnCntYear - $lnEjerIni] = $lnCntYear;
    }
}
//
$lcBuscar_Soc = $laDatosCliente['COMPRA_SOC'];

$oAnyoSel = $oAccion;
if ($oAccion == 'TOTAL') {
    $oAnyoSel = $oTotEje;
}
if ($oAnyoSel == '') {
    $oAnyoSel = date('Y');
}
if ($oEjeHas == '') {
    $oEjeHas = date('Y');
}
if ($oEjeDes == '') {
    $oEjeDes = date('Y') - 1;
}
if ($oTotEje == '') {
    $oTotEje = $oAnyoSel;
}

$aMeses = [
    ['Enero', 0, 0, 0, 0],
    ['Febrero', 0, 0, 0, 0],
    ['Marzo', 0, 0, 0, 0],
    ['Abril', 0, 0, 0, 0],
    ['Mayo', 0, 0, 0, 0],
    ['Junio', 0, 0, 0, 0],
    ['Julio', 0, 0, 0, 0],
    ['Agosto', 0, 0, 0, 0],
    ['Septiembre', 0, 0, 0, 0],
    ['Octubre', 0, 0, 0, 0],
    ['Noviembre', 0, 0, 0, 0],
    ['Diciembre', 0, 0, 0, 0]
];

$aComparar = [
    ['Enero', 0, 0, 0],
    ['Febrero', 0, 0, 0],
    ['Marzo', 0, 0, 0],
    ['Abril', 0, 0, 0],
    ['Mayo', 0, 0, 0],
    ['Junio', 0, 0, 0],
    ['Julio', 0, 0, 0],
    ['Agosto', 0, 0, 0],
    ['Septiembre', 0, 0, 0],
    ['Octubre', 0, 0, 0],
    ['Noviembre', 0, 0, 0],
    ['Diciembre', 0, 0, 0]
];

//+Totales del informe comparado
$lnAnyoAntAcu = 0;
$lnAnyoActAcu = 0;
$lnAnyoDifAcu = 0;
//-

// TIPO DE INFORME
if (($oAccion == 'COMPARAR') or ($oAccion == 'COMPARAR_D')) {
    $llTotalAnual = false;
    $lcTexLog     = 'CONSUMOS: ' . $oAccion . ' - ' . $oTipCom;
} else {
    $llTotalAnual = true;
    $lcTexLog     = 'CONSUMOS: ' . $oAnyoSel;
}

//Capturo por si el socio tiene un numero antiguo de socio
$idSocioAntiguo = isset($laDatosCliente['SOCIO_ANT']) ? $laDatosCliente['SOCIO_ANT'] : '';

// CALCULO DE VALORES
if ($llTotalAnual) {

    $oAcumuladoSocio = new WebSociosAcum();
    $oAcumuladoSocio->depurarObjeto($llDepurar);
    $arrImportes = $oAcumuladoSocio->getConsumo($pgIdentificadorEmpresa, $oAnyoSel, $lcBuscar_Soc, $idSocioAntiguo);
    /*
    $lcCadSql = fc_Consumo($pgIdentificadorEmpresa, $oAnyoSel, $lcBuscar_Soc, '', $idSocioAntiguo);
    Iniutils::escribeEnLog($lcCadSql, $llDepurar);
    $rstReg = fgEjecutarSql($connection, $lcCadSql);
    if ($rstReg) {
        while ($rowTot = fgVolcarArray($rstReg)) {
    */
    foreach ($arrImportes as $rowTot) {
        $lnMes             = ((int) $rowTot['MES']) - 1;
        $aMeses[$lnMes][1] = $rowTot['ACU_ALM_NAC'];
        $aMeses[$lnMes][2] = $rowTot['ACU_ALM_UE'];
        $aMeses[$lnMes][3] = $rowTot['ACU_ALM_IMP'];
        $aMeses[$lnMes][4] = $rowTot['ACU_ALM_DIR'];

        // FEX
        $lcFechaActualizacion = $rowTot['FEX_FOR'];
    }
    //}
} else {

    $oAnyoAnt = $oEjeDes;
    $oAnyoAct = $oEjeHas;

    $oAcumuladoSocio = new WebSociosAcum();
    $oAcumuladoSocio->depurarObjeto($llDepurar);
    $arrImportes = $oAcumuladoSocio->getConsumoComparado($pgIdentificadorEmpresa, $oAnyoAct, $oAnyoAnt, $lcBuscar_Soc, $idSocioAntiguo);
    /*
        $lcCadSql = fc_Consumo_Comparar(
            $pgIdentificadorEmpresa,
            $oAnyoAct,
            $oAnyoAnt,
            $lcBuscar_Soc,
            '',
            $idSocioAntiguo
        );
        Iniutils::escribeEnLog($lcCadSql, $llDepurar);

        $rstReg = fgEjecutarSql($connection, $lcCadSql);
        if ($rstReg) {
            while ($rowTot = fgVolcarArray($rstReg)) {
    */
    foreach ($arrImportes as $rowTot) {
        $lnMes = ((int) $rowTot['MES']) - 1;
        switch ($oTipCom) {
            case 'COM_N':
                $lnTot = $rowTot['ACU_ALM_NAC'];
                break;
            case 'COM_U':
                $lnTot = $rowTot['ACU_ALM_UE'];
                break;
            case 'COM_I':
                $lnTot = $rowTot['ACU_ALM_IMP'];
                break;
            case 'COM_A':
                $lnTot = $rowTot['ACU_ALM_NAC'] + $rowTot['ACU_ALM_UE'] + $rowTot['ACU_ALM_IMP'];
                break;
            case 'COM_D':
                $lnTot = $rowTot['ACU_ALM_DIR'];
                break;
            case 'COM_T':
                $lnTot = $rowTot['ACU_ALM_NAC'] + $rowTot['ACU_ALM_UE'] + $rowTot['ACU_ALM_IMP'] + $rowTot['ACU_ALM_DIR'];
                break;
            default:
                $lnTot = 0;
        }

        if ($rowTot['ANYO'] == $oAnyoAnt) {
            $aComparar[$lnMes][1] = $lnTot;
        } else {
            $aComparar[$lnMes][2] = $lnTot;
        }
        $aComparar[$lnMes][3] = $aComparar[$lnMes][2] - $aComparar[$lnMes][1];

        // FEX
        $lcFechaActualizacion = $rowTot['FEX_FOR'];
    }
    //}
}
//-ACCIONES

//+LOG
AccesoDatos::grabarLog(Conexion::getInstancia(), $pgIdentificadorEmpresa, $UserID, $UserSuc, $lcLog_Grupo, $lcLog_Texto, $lnIdUsuMagento);
//-LOG

//+CONFIGURACION CONTENIDO
$lcAyuTex          = '* LOS TOTALES POR CATEGORIA PUEDEN CAMBIAR, SEGUN SE CAMBIE LA TIPIFICACION DEL ARTICULOS, NO ASI LOS TOTALES GLOBALES.<BR>* SON IMPORTES SIN IVA, SOLO DE ARTICULOS ALMACENABLES (NO SE INCLUYEN CARGOS/CUOTAS/...).';
$lcAyudaPantalla_P = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco      = "document.getElementById('oBusCod').focus()";
$lcObjetoFoco      = "";
$llDivTitulo       = true;
$llDivExplicacion  = false;
$llDivBotones_Sup  = true;
$llDivBotones_Inf  = false;
$llDivPie          = true;
$llDivHiddenDown   = true;
//-FIN CONFIGURACION CONTENIDO

//Cambio el titulo del formulario
$lcTitPan = "ACUMULADOS ANUALES (SOCIO: {$lcBuscar_Soc} )";

//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();

//CONFIGURACION DE EXPORTACION EXCEL
if ($llExpExcel) {

    die('Complemento EXCEL desactualizado. Por favor contacte con el Dpto. Informatica para su posible resolucion');

} else {
?>

<body onLoad="<?php echo $lcObjetoFoco; ?>">
<form id="frmDatos" name="frmDatos" method="post" action="">
    <INPUT type="hidden" id="pSession" name="pSession" value="<?php echo $pSession; ?>">
    <INPUT type="hidden" id="pUrl" name="pUrl" value="">
    <INPUT type="hidden" id="oAccion" name="oAccion" value="">
    <INPUT type="hidden" id="oDetalle" name="oDetalle" value="">
    <!-- CAMPOS DEL FORMULARIO -->
    <input type="hidden" id="oAnyoSel" name="oAnyoSel" value="<?php echo $oAnyoSel; ?>" />
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
                    <i class="fa fa-question fa-icono-header" style="cursor: none"
                       title=""
                       onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
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

        <br />
        <div id="dvBotonesMed">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="45%" align="left" valign="top">

                        <select name="oTotEje" class="" tabindex="1" style="WIDTH: 80px" title="">
                            <?php
                            $consumoEjercicioSize = count($aConsumoEjercicio);
                            for ($lnTmpCnt = 0; $lnTmpCnt < $consumoEjercicioSize; $lnTmpCnt++) {
                            $lnEjer = $aConsumoEjercicio[$lnTmpCnt];
                            if ($lnEjer <> '') {
                            ?>
                            <option value="<?php echo $lnEjer; ?>"
                            <?php if ($oTotEje == $lnEjer) {
                                echo ' SELECTED ';
                            } ?>><?php echo $lnEjer; ?></option>
                            <?php
                            }
                            }
                            ?>
                        </select>

                        &nbsp;&nbsp;&nbsp;
                        <button class="button"
                                title="Ver total del ejercicio seleccionado"
                                type="button"
                                onClick="fAccion('','<?php echo $lcNomPageAct; ?>','TOTAL')">
                            <span><span>  Ver total  </span></span></button>
                    </td>
                    <td></td>
                    <td width="50%" valign="top">

                        <table width="100%" border="0" cellspacing="0" cellpadding="0" rules="none">
                            <tr>
                                <td valign="top">
                                    <span class="letra_sec">&nbsp;</span>
                                    <select name="oTipCom" tabindex="1" style="WIDTH: 100px" title="">
                                        <option value="COM_T"
                                        <?php if ($oTipCom == 'COM_T') {
                                            echo ' SELECTED ';
                                        } ?>>TOTAL
                                        </option>
                                        <option value="COM_A"
                                        <?php if ($oTipCom == 'COM_A') {
                                            echo ' SELECTED ';
                                        } ?>>ALMACEN
                                        </option>
                                        <option value="COM_D"
                                        <?php if ($oTipCom == 'COM_D') {
                                            echo ' SELECTED ';
                                        } ?>>DIRECTO
                                        </option>
                                        <option value="COM_N"
                                        <?php if ($oTipCom == 'COM_N') {
                                            echo ' SELECTED ';
                                        } ?>>NACIONAL
                                        </option>
                                        <option value="COM_U"
                                        <?php if ($oTipCom == 'COM_U') {
                                            echo ' SELECTED ';
                                        } ?>>UE
                                        </option>
                                        <option value="COM_I"
                                        <?php if ($oTipCom == 'COM_I') {
                                            echo ' SELECTED ';
                                        } ?>>IMP
                                        </option>
                                    </select>
                                </td>
                                <td align="center" valign="top">
                                    <label>
                                        <select name="oEjeDes" tabindex="1" style="WIDTH: 80px">
                                            <?php
                                            $consumoEjercicioSize = count($aConsumoEjercicio);
                                            for ($lnTmpCnt = 0; $lnTmpCnt < $consumoEjercicioSize; $lnTmpCnt++) {
                                            $lnEjer = $aConsumoEjercicio[$lnTmpCnt];
                                            if ($lnEjer <> '') {
                                            ?>
                                            <option value="<?php echo $lnEjer; ?>"
                                            <?php if ($oEjeDes == $lnEjer) {
                                                echo ' SELECTED ';
                                            } ?>><?php echo $lnEjer; ?></option>
                                            <?php
                                            }
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </td>
                                <td align="center" valign="top">
                                    <label>
                                        <select name="oEjeHas"
                                                class="text_edi_tv"
                                                tabindex="1"
                                                style="WIDTH: 80px">
                                            <?php
                                            for ($lnTmpCnt = 0; $lnTmpCnt < count(
                                                $aConsumoEjercicio
                                            ); $lnTmpCnt++) {
                                            //echo ;
                                            $lnEjer = $aConsumoEjercicio[$lnTmpCnt];
                                            if ($lnEjer <> '') {
                                            ?>
                                            <option value="<?php echo $lnEjer; ?>"
                                            <?php if ($oEjeHas == $lnEjer) {
                                                echo ' SELECTED ';
                                            } ?>><?php echo $lnEjer; ?></option>
                                            <?php
                                            }
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </td>
                                <td valign="top">
                                    <button class="button"
                                            title="Comparar"
                                            type="button"
                                            onClick="fAccion('','<?php echo $lcNomPageAct; ?>','COMPARAR')">
                                        <span><span>  Comparar  </span></span>
                                    </button>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
        </div>


        <br />
        <span><strong><u></u></strong></span>
        <div id="dvContenido">
            <!-- CONTENIDO -->
            <?php
            switch ($oAccion) {

            case 'COMPARAR':
            ?>

            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="20%" height="25"><span><b>A&ntilde;o: <?php echo $oAnyoSel; ?></b></span>
                    </td>
                    <td width="2%"></td>
                    <td style="background: #b5bf00" width="12%" align="center">
                        <span style="color:#FFFFFF"><?php echo $oEjeDes; ?></span></td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="12%" align="center">
                        <span style="color:#FFFFFF"><?php echo $oEjeHas; ?></span></td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="14%" align="center">
                        <span style="color:#FFFFFF">VARIACION</span>
                    </td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="14%" align="center">
                        <span style="color:#FFFFFF">PORCENTAJE</span>
                    </td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="12%" align="center"><span style="color:#FFFFFF">% ACUM</span>
                    </td>
                    <td style="background: #b5bf00" width="2%"></td>
                </tr>

                <?php
                $lnFila = 0;
                foreach ($aComparar as $aMes) {
                $lnFila       = $lnFila + 1;
                $lcClassFil   = ($lnFila % 2) == 0 ? '' : 'style="background: #E9E9E9"';
                $lnPorcentaje = $aMes[1] != 0 ? ($aMes[3] / $aMes[1]) * 100 : 0;

                $lcAnyoAnt    = number_format($aMes[1], 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAnyoAct    = number_format($aMes[2], 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAnyoDif    = number_format($aMes[3], 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcPorcentaje = number_format($lnPorcentaje, 2, ',', '.') . '&nbsp;%&nbsp;';

                $lnAnyoAntAcu = $lnAnyoAntAcu + $aMes[1];
                $lnAnyoActAcu = $lnAnyoActAcu + $aMes[2];
                $lnAnyoDifAcu = $lnAnyoDifAcu + $aMes[3];

                if ($lnAnyoAntAcu <> 0) {
                    $lnPorcentajeAcu = (($lnAnyoDifAcu / $lnAnyoAntAcu) * 100);
                } else {
                    $lnPorcentajeAcu = 0;
                }
                $lcPorcentajeAcu = number_format($lnPorcentajeAcu, 2, ',', '.') . '&nbsp;%&nbsp;';
                ?>
                <tr>
                    <td><b><span class="intranet-Text">&nbsp;<?php echo $aMes[0]; ?></span></b></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAnyoAnt; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAnyoAct; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAnyoDif; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcPorcentaje; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcPorcentajeAcu; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                </tr>
                <tr>
                    <td height="1"></td>
                </tr>
                <?php
                }
                ?>
                <?php
                //Total del informe comparado
                $lcAnyoAntAcu = number_format($lnAnyoAntAcu, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAnyoActAcu = number_format($lnAnyoActAcu, 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAnyoDifAcu = number_format($lnAnyoDifAcu, 2, ',', '.') . '&nbsp;€&nbsp;';
                if ($lnAnyoAntAcu <> 0) {
                    $lnPorcentaje = (($lnAnyoDifAcu / $lnAnyoAntAcu) * 100);
                } else {
                    $lnPorcentaje = 0;
                }
                $lcPorcentaje = number_format($lnPorcentaje, 2, ',', '.') . '&nbsp;%&nbsp;';
                ?>
                <tr>
                    <td><span>&nbsp;</span></td>
                    <td>&nbsp;</td>
                    <td align="right"><b><span class="intranet-Text"><?php echo $lcAnyoAntAcu; ?></span></b>
                    </td>
                    <td>&nbsp;</td>
                    <td align="right"><b><span class="intranet-Text"><?php echo $lcAnyoActAcu; ?></span></b>
                    </td>
                    <td>&nbsp;</td>
                    <td align="right"><b><span class="intranet-Text"><?php echo $lcAnyoDifAcu; ?></span></b>
                    </td>
                    <td>&nbsp;</td>
                    <td align="right"><b><span class="intranet-Text"><?php echo $lcPorcentaje; ?></span></b>
                    </td>
                    <td>&nbsp;</td>
                    <td align="right"><b><span class="intranet-Text"><?php echo $lcPorcentaje; ?></span></b>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <?php
                ?>
            </table>
            <?php
            break;

            case 'COMPARAR_D':
                ?>
                        <?php
                break;

            default:
            ?>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="" width="20%" height="25">
                        <span><b>A&ntilde;o: <?php echo $oAnyoSel; ?></b></span></td>
                    <td style="" width="2%"></td>
                    <td style="background: #b5bf00" width="14%" align="center">
                        <span style="color:#FFFFFF">NAC</span></td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="14%" align="center">
                        <span style="color:#FFFFFF">UE</span></td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="14%" align="center">
                        <span style="color:#FFFFFF">IMP</span></td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="14%" align="center">
                        <span style="color:#FFFFFF">DIRECTOS</span>
                    </td>
                    <td style="background: #b5bf00" width="2%"></td>
                    <td style="background: #b5bf00" width="14%" align="center">
                        <span style="color:#FFFFFF">TOTAL</span></td>
                </tr>

                <?php
                $lnFila   = 0;
                $lnTotNac = 0;
                $lnTotUe  = 0;
                $lnTotImp = 0;
                $lnTotDir = 0;
                $lnTotTot = 0;
                foreach ($aMeses as $aMes) {
                $lnFila     = $lnFila + 1;
                $lcClassFil = ($lnFila % 2) == 0 ? '' : 'style="background: #E9E9E9"';
                $lnAcuTot   = $aMes[1] + $aMes[2] + $aMes[3] + $aMes[4];

                $lnTotNac = $lnTotNac + $aMes[1];
                $lnTotUe  = $lnTotUe + $aMes[2];
                $lnTotImp = $lnTotImp + $aMes[3];
                $lnTotDir = $lnTotDir + $aMes[4];
                $lnTotTot = $lnTotTot + $lnAcuTot;

                $lcAcuNac = number_format($aMes[1], 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAcuUe  = number_format($aMes[2], 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAcuImp = number_format($aMes[3], 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAcuDir = number_format($aMes[4], 2, ',', '.') . '&nbsp;€&nbsp;';
                $lcAcuTot = number_format($lnAcuTot, 2, ',', '.') . '&nbsp;€&nbsp;';
                ?>
                <tr>
                    <td <?php echo $lcClassFil; ?> height="24">
                        <b><span class="intranet-Text">&nbsp;<?php echo $aMes[0]; ?></span></b></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAcuNac; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAcuUe; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAcuImp; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAcuDir; ?></span></td>
                    <td <?php echo $lcClassFil; ?>>&nbsp;</td>
                    <td <?php echo $lcClassFil; ?> align="right">
                        <span class="intranet-Text"><?php echo $lcAcuTot; ?></span></td>
                </tr>
                <tr>
                    <td height="1"></td>
                </tr>
            <?php
            }
            $lcTotNac = number_format($lnTotNac, 2, ',', '.') . '&nbsp;€&nbsp;';
            $lcTotUe  = number_format($lnTotUe, 2, ',', '.') . '&nbsp;€&nbsp;';
            $lnTotImp = number_format($lnTotImp, 2, ',', '.') . '&nbsp;€&nbsp;';
            $lcTotDir = number_format($lnTotDir, 2, ',', '.') . '&nbsp;€&nbsp;';
            $lcTotTot = number_format($lnTotTot, 2, ',', '.') . '&nbsp;€&nbsp;';
            ?>
            <!-- FILA TOTALES DE ABAJO -->
                <tr>
                    <td></td>
                    <td class="intranet-FilaTotal">&nbsp;</td>
                    <td class="intranet-FilaTotal" align="right">
                        <span><b><?php echo $lcTotNac; ?></b></span>
                    </td>
                    <td class="intranet-FilaTotal">&nbsp;</td>
                    <td class="intranet-FilaTotal" align="right"><span><b><?php echo $lcTotUe; ?></b></span>
                    </td>
                    <td class="intranet-FilaTotal">&nbsp;</td>
                    <td class="intranet-FilaTotal" align="right">
                        <span><b><?php echo $lnTotImp; ?></b></span>
                    </td>
                    <td class="intranet-FilaTotal">&nbsp;</td>
                    <td class="intranet-FilaTotal" align="right">
                        <span><b><?php echo $lcTotDir; ?></b></span>
                    </td>
                    <td class="intranet-FilaTotal">&nbsp;</td>
                    <td class="intranet-FilaTotal" align="right">
                        <span><b><?php echo $lcTotTot; ?></b></span>
                    </td>
                </tr>
                <!-- FIN FILA TOTALES DE ABAJO -->
            </table>
        <?php
        break;
        }
        ?>
        <!-- FIN CONTENIDO-->
        </div>

        <?php if ($lcMensajeProceso <> '') { ?>
        <br />
        <div id="dvMensajes" style="text-align:center">
            <span class="intranet-LetraError"><?php echo $lcMensajeProceso; ?></span>
        </div>
        <?php } ?>

        <?php if ($llDivBotones_Inf) { ?>
        <br />
        <div id="dvBotonesInf" style="text-align:center">
        </div>
        <?php } ?>

        <?php if ($llDivPie) { ?>
        <br />
        <div id="dvPie">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="right" class="intranet-FilaPie">
                        <span><b>Actualizado a <?php echo $lcFechaActualizacion; ?>&nbsp;&nbsp;</b></span>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="intranet-FilaPie">
                        <span><b>*** No engloba consumos con numero de socio/asociado anteriores.&nbsp;&nbsp;</b></span>
                    </td>
                </tr>
            </table>
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
