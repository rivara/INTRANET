<?php
/**
 * 29/11/2014
 * 09/11/2017  Nueva plantilla (Botonera en el titulo, excel PHP Excel, Clases)
 */

//+PARAMETROS GENERAL PAGINA
$lcDirBase        = '../../';                                       // COMO IR AL DIRECTORIO BASE
$llDepurar        = false;                                          // OJO TAMBIEN SE PUEDE HABILITAR DESPUES DEL INCLUDE
$lcTitPan         = 'Resumen de rappels por proveedor';             // TITULO DE LA PAGINA
$llExpExcel       = false;                                          // ACCION DE EXPORTAR A EXCEL EN ESTA PAGINA
$lcFicExc         = 'SEGUIMIENTO_RAPPEL.XLS';                       // NOMBRE DEL FICHERO EXCEL A GENERAR
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
$lcTotAlm               = 0;
$lcTotDir               = 0;
$lnImpVal               = 0;
$lcRazon                = '';
$llSeguimiento          = false;
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
$lcNomPageTot = $Rutas['Base_Intranet_Ww'] . 'Socios/Movs/rap_Indice.php';
$lcNomPagePro = $Rutas['Base_Intranet_Ww'] . 'Socios/Movs/rap_Proveedor.php';
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
$lcProCod     = Helper::post('oSelPro');
$oAnRap       = Helper::post('oAnRap');
//-FIN CAPTURAR VARIABLES FORMULARIO

//+ACCIONES
$lcMensajeProceso = '';
$lcLog_Grupo      = 'RAPPEL';
$lcLog_Texto      = 'PROVEEDOR: ' . $lcProCod;
//REGISTROS POR PAGINA (LISTADOS)
if ($rowConfEmp['ejerRappel'] <> '') {
    if ($oAnRap == '') {
        $oAnRap = (int) $rowConfEmp['ejerRappel'];
    }
}
if ($oAnRap == '') {
    $oAnRap = $gAnyoRappelSoc;
}
//ESPECIAL PARA QUE HAYA JUEGO CON LOS USUARIOS
$lcUser        = $laDatosCliente ['COMPRA_SOC'];
$lcSuc         = $laDatosCliente ['COMPRA_SUC'];
$lcTituloSegui = '';
//CAPTURO DATOS CLIENTE INTRANET (SOCIO_ANT)
$laDatosClienteIntranet = fDatosTercero($connection, $DBTableNames['Socios'], $UserID, $UserSuc);
//
$llPintarIndice       = true;
$llPintarResumen      = false;
$llPintarActualizando = false;
//
//$lAnyo = $gAnyoRappelSoc;
$lAnyo    = $oAnRap;
$pAnyo    = isset($pAnyo) ? $pAnyo : '';
$oAnyoDet = isset($oAnyoDet) ? $oAnyoDet : '';
if ($pAnyo <> '') {
    $lAnyo = $pAnyo;
}
if ($oAnyoDet <> '') {
    $lAnyo = $oAnyoDet;
}
//
$llPintarDatosProveedor = true;
$llPintarImprimir       = true;
$llPintarIndice         = true;
if (($oAccion == 'S') or ($oAccion == 'EX')) {

    $llPintarIndice  = false;
    $llPintarResumen = true;

    $lcLog_Texto = "RAP PROV LISTA ({$lAnyo} / {$oAccion} )";

}
//
$lcMensajeAyuda  = '';
$lcAyudaPantalla = "Tip('" . $lcMensajeAyuda . "');";
//
$lblLabelSeguimiento = 'TODOS';
if ($llSeguimiento) {
    $lblLabelSeguimiento = 'SEGUIMIENTO';
}
//-ACCIONES

//+LOG
AccesoDatos::grabarLog(Conexion::getInstancia(), $pgIdentificadorEmpresa, $UserID, $UserSuc, $lcLog_Grupo, $lcLog_Texto, $lnIdUsuMagento);
//-LOG

//+CONFIGURACION CONTENIDO
$lcAyuTex           = '* <B>CONSUMO ALM</B> = Consumo en productos almacen.<BR>';
$lcAyuTex          .= '* <B>CONSUMO DIR</B> = Consumo en directos.<BR>';
$lcAyuTex          .= '* <B>RAPPEL ACTUAL</B> = Rappel correspondiente.<BR>';
$lcAyuTex          .= '* <B>IMPORTE RAPPEL</B> = Importe rappel.<BR>';
$lcAyuTex          .= '* <B>SIGUIENTE RAPPEL</B> = Siguiente nivel de Rappel.<BR>';
$lcAyuTex          .= '* <B>LIMITE SIGUIENTE RAPPEL</B> = Limite para llegar al siguiente nivel de rappel.<BR>';
$lcAyuTex          .= '* <B>DIFERENCIA</B> = Diferencia entre el consumo actual y el siguiente nivel de rappel.<BR><BR>';
$lcAyuTex          .= '* En rojo aparecerian los proveedores que estan de baja en el momento de sacar el listado.<BR>';
$lcAyudaPantalla_P  = "Tip('" . $lcAyuTex . "');";
$lcObjetoFoco       = "document.getElementById('oBusCod').focus()";
$lcObjetoFoco       = "";
$llDivTitulo        = true;
$llDivExplicacion   = false;
$llDivBotones       = true;
$llDivBotones_Sup   = true;
$llDivBotones_Inf   = true;
$llDivPie           = false;
$llDivHiddenDown    = true;
//-CONFIGURACION CONTENIDO

//Color corporativo
$colorCorporativo = App::getInstancia()->getColorCorporativo();

if ($llPintarIndice) {
    $llDivTitulo       = false;
} else {
    if ($llPintarResumen) {
        $lcTitPan = "PROYECCION CONSUMOS RAPPEL ANUAL ( {$lAnyo} )";
    }

}

if ($llExpExcel) {

    //Sobre el fichero
    $lcNombrePestana = "RAPEL_{$lAnyo}";
    //Cabeceras informe
    $cabeceras = [
        'PROVEEDOR',
        'NOMBRE',
        'CONSUMO ALMACEN',
        'CONSUMO DIRECTO',
        'RAPPEL ACTUAL',
        'IMPORTE RAPPEL',
        'SIGUIENTE RAPPEL',
        'LIMITE SIGUIENTE RAPPEL',
        'DIFERENCIA',
        'LIMITE SIGUIENTE RAPPEL -1-',
        'RAPPEL',
        'LIMITE SIGUIENTE RAPPEL -2-',
        'RAPPEL',
        'LIMITE SIGUIENTE RAPPEL -3-',
        'RAPPEL',
        'LIMITE SIGUIENTE RAPPEL -4-',
        'RAPPEL',
        'LIMITE SIGUIENTE RAPPEL -5-',
        'RAPPEL',
        'LIMITE SIGUIENTE RAPPEL -6-',
        'RAPPEL',
        'LIMITE SIGUIENTE RAPPEL -7-',
        'RAPPEL',
    ];
    //Datos
    $lMes      = 12;
    $lSocio    = $lcUser;
    $lSocioAnt = $laDatosClienteIntranet ['SOCIO_ANT'];

    $oConsumoSocioProveedor = new WebSociosAcumProv();
    $oConsumoSocioProveedor->depurarObjeto($llDepurar);
    $arrConsumos = $oConsumoSocioProveedor->getConsumoAgrupadoPorEjercicio($pgIdentificadorEmpresa, $lAnyo, $lMes, '', $lSocio, $lSocioAnt, true);

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

    foreach ($arrConsumos as $row) {

        $columna = 'A';

        $lPro     = $row['PRO'];
        $lRaz     = $row['RAZ'];
        $lnConAlm = $row['ACU_ALM'];
        $lnConDir = $row['ACU_DIR'];

        $lnRapAct              = 0;
        $lnSigNivRappel        = 0;
        $lnSigNivDesde         = 0;
        $lnRapObt              = 0;
        $lnConsumoSiguienteNiv = 0;
        $lnTotCon              = 0;
        $lcSigRap_1            = '';
        $lcRap_1               = '';
        $lcSigRap_2            = '';
        $lcRap_2               = '';
        $lcSigRap_3            = '';
        $lcRap_3               = '';
        $lcSigRap_4            = '';
        $lcRap_4               = '';
        $lcSigRap_5            = '';
        $lcRap_5               = '';
        $lcSigRap_6            = '';
        $lcRap_6               = '';
        $lcSigRap_7            = '';
        $lcRap_7               = '';

        $oRapeles = new WebProveedorRap();
        $oRapeles->depurarObjeto($llDepurar);
        $oRapeles->empresa($pgIdentificadorEmpresa);
        $oRapeles->cdprove($lPro);
        $oRapeles->anyo($lAnyo);
        $arrRapeles = $oRapeles->getRapeles(Conexion::getInstancia());

        foreach ($arrRapeles as $rowPro) {

            $lnImporteDesde = $rowPro ['DESDE'];
            $lnImporteHasta = $rowPro ['HASTA'];

            if ($rowPro['IND_RAP_DIR'] == 0) {
                $lnImpVal = ($lnConAlm);
                if (($lnImpVal > $lnImporteDesde) and (($lnImpVal < $lnImporteHasta) or (($lnImporteHasta == 0) and ($lnSigNivRappel == 0)))) {
                    $lnRapAct = $rowPro ['POR_RAP'];
                    $lnRapObt = $lnImpVal * ($lnRapAct / 100);
                    $lnSigNivRappel = 0;
                    $lnSigNivDesde  = 0;
                } else {
                    if ($lnSigNivRappel == 0) {
                        $lnSigNivRappel = $rowPro ['POR_RAP'];
                        $lnSigNivDesde  = $rowPro ['DESDE'];
                    }
                }
            } else {
                $lnImpVal = ($lnConAlm + $lnConDir);
                if (($lnImpVal > $lnImporteDesde) and (($lnImpVal < $lnImporteHasta) or ($lnImporteHasta == 0))) {
                    $lnRapAct = $rowPro ['POR_RAP'];
                    $lnRapObt = $lnImpVal * ($lnRapAct / 100);
                } else {
                    if ($lnSigNivRappel == 0) {
                        if ($lnImporteDesde > $lnImpVal) {
                            $lnSigNivRappel = $rowPro ['POR_RAP'];
                            $lnSigNivDesde  = $rowPro ['DESDE'];
                        }
                    }
                }
            }

            if ($lnSigNivRappel <> '') {
                if ($lnSigNivDesde < $rowPro ['DESDE']) {
                    if ($lcSigRap_1 == '') {
                        $lcSigRap_1 = $rowPro ['DESDE'];
                        $lcRap_1    = $rowPro ['POR_RAP'];
                    } else {
                        if ($lcSigRap_2 == '') {
                            $lcSigRap_2 = $rowPro ['DESDE'];
                            $lcRap_2    = $rowPro ['POR_RAP'];
                        } else {
                            if ($lcSigRap_3 == '') {
                                $lcSigRap_3 = $rowPro ['DESDE'];
                                $lcRap_3    = $rowPro ['POR_RAP'];
                            } else {
                                if ($lcSigRap_4 == '') {
                                    $lcSigRap_4 = $rowPro ['DESDE'];
                                    $lcRap_4    = $rowPro ['POR_RAP'];
                                } else {
                                    if ($lcSigRap_5 == '') {
                                        $lcSigRap_5 = $rowPro ['DESDE'];
                                        $lcRap_5    = $rowPro ['POR_RAP'];
                                    } else {
                                        if ($lcSigRap_6 == '') {
                                            $lcSigRap_6 = $rowPro ['DESDE'];
                                            $lcRap_6    = $rowPro ['POR_RAP'];
                                        } else {
                                            if ($lcSigRap_7 == '') {
                                                $lcSigRap_7 = $rowPro ['DESDE'];
                                                $lcRap_7    = $rowPro ['POR_RAP'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (($lnSigNivDesde == 0) and ($lnImpVal > 0)) {
            $lnDiferencia = 0;
        } else {
            $lnDiferencia = $lnSigNivDesde - ($lnImpVal);
        }

        $lcColorLetra = '';
        if ($row ['IND_ACT'] == 0) {
            $lcColorLetra = 'intranet-LetraError';
        }

        $hojaActiva->setCellValue($columna++ . $fila, $lPro);
        $hojaActiva->setCellValue($columna++ . $fila, $lRaz);
        $hojaActiva->setCellValue($columna++ . $fila, $lnConAlm);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lnConDir);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lnRapAct);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lnRapObt);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lnSigNivRappel);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lnSigNivDesde);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lnDiferencia);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');

        $hojaActiva->setCellValue($columna++ . $fila, $lcSigRap_1);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcRap_1);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcSigRap_2);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcRap_2);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcSigRap_3);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcRap_3);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcSigRap_4);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcRap_4);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcSigRap_5);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcRap_5);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcSigRap_6);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcRap_6);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcSigRap_7);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        $hojaActiva->setCellValue($columna++ . $fila, $lcRap_7);
        $hojaActiva->getStyle("{$columna}{$fila}")->getNumberFormat()->setFormatCode('0.0000');
        /*


*/
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
    <!-- CAMPOS DEL FORMULARIO -->
    <input type="hidden" id="oSelPro" name="oSelPro" value="" />
    <input type="hidden" id="oSelNombre" name="oSelNombre" value="" />
    <input type="hidden" id="oAnyoDet" name="oAnyoDet" value="<?php echo $lAnyo; ?>" />
    <input type="hidden" id="oDetalle" name="oDetalle" value="" />
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
                    <?php if ($llPintarResumen) { ?>
                    <i class="fa fa-file-excel-o fa-icono-header"
                       title="Exportar a excel el listado de rappels"
                       onClick="fAccion('frmDescarga', '<?php echo $lcNomPageTot; ?>', 'EX');"></i>
                    <?php } ?>
                    <i class="fa fa-question fa-icono-header" style="cursor: none"
                       title=""
                       onmouseover="<?php echo $lcAyudaPantalla_P; ?>"></i>
                    <?php if ($llPintarResumen) { ?>
                    <i class="fa fa-arrow-left fa-icono-header"
                       title="Volver a la página anterior"
                       onClick="fAccion('', '<?php echo $lcNomPageTot; ?>', '');"></i>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>


        <?php if ($llPintarIndice) { ?>
        <br />
        <div id="dvIndice">
            <table width="100%" border="0" cellspacing="3" cellpadding="3">
                <tr>
                    <td style="background: #8c8d8e" align="center" valign="middle" height="32"><a href="#A"
                                                                                                  style="color: white">A</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#B" style="color: white">B</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#C" style="color: white">C</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#D" style="color: white">D</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#E" style="color: white">E</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#F" style="color: white">F</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#G" style="color: white">G</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#H" style="color: white">H</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#I" style="color: white">I</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#J" style="color: white">J</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#K" style="color: white">K</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#L" style="color: white">L</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#M" style="color: white">M</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#N" style="color: white">N</a>
                    </td>
                    <td class="" align="center" valign="middle"><span>&nbsp;
<select id="oAnRap"
        name="oAnRap"
        style="width:69px; font-size:12px"
        onChange="fAccion('', '<?php echo $lcNomPageTot; ?>', 'C')"
        title="">
<?php
    $lEjerAct = (int) $rowConfEmp['ejerRappel'];
    $lEjerAnt = $lEjerAct - 1;
    ?>
    <option value="<?php echo $lEjerAnt; ?>" <?php if ((int) $oAnRap == $lEjerAnt) {
        echo ' SELECTED';
    } ?>><?php echo $lEjerAnt; ?></option>
<option value="<?php echo $lEjerAct; ?>" <?php if ((int) $oAnRap == $lEjerAct) {
    echo ' SELECTED';
} ?>><?php echo $lEjerAct; ?></option>
</select>
		&nbsp;</span></td>
                    <td></td>
                </tr>
                <tr>
                    <td height="5"></td>
                </tr>
                <tr>
                    <td style="background: #8c8d8e" align="center" valign="middle" height="20"><a href="#Ñ"
                                                                                                  style="color: white">Ñ</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#O" style="color: white">O</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#P" style="color: white">P</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#Q" style="color: white">Q</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#R" style="color: white">R</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#S" style="color: white">S</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#T" style="color: white">T</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#U" style="color: white">U</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#V" style="color: white">V</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#W" style="color: white">W</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#X" style="color: white">X</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#Y" style="color: white">Y</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#Z" style="color: white">Z</a>
                    </td>
                    <td style="background: #8c8d8e" align="center" valign="middle"><a href="#Numero" style="color: white">1-9</a>
                    </td>
                    <td align="center" valign="middle" width="100">
                        <button type="button"
                                id="oSeg"
                                name="oSeg"
                                class="button"
                                onClick="fAccion('', '<?php echo $lcNomPageTot; ?>', 'S')"><span><span>  Todos  </span></span>
                        </button>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
            <div style="OVERFLOW: auto; WIDTH: 100%; HEIGHT: 650px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <?php
                    $oProveedor = new WebProveedor();
                    $oProveedor->depurarObjeto($llDepurar);
                    $arrProveedores = $oProveedor->getProveedoresConRappel($pgIdentificadorEmpresa, $lAnyo);

                    $gnNumCol = 3;
                    $lnAncCol = (int) (100 / $gnNumCol);
                    $lnNumReg = 0;
                    $lnRegFil = 0;
                    $lcPriLet = '';
                    $lcLetAnt = '';

                    foreach ($arrProveedores as $row) {

                        $oProveedor = new WebProveedor($row);

                        $llPintar = false;
                        $llCambio = false;

                        if ($oProveedor->indRapSoc() == 1) {
                            $llPintar = true;
                            $lnRegFil = $lnRegFil + 1;
                            $lcRazon  = $oProveedor->nomcomer();
                            $lcRazon  = str_replace('?', 'Ñ', $lcRazon);
                            $lcPriLet = substr($lcRazon, 0, 1);
                        }

                        if ($llPintar) {

                            $lcColor = '';

                            if ($lcLetAnt <> '') {
                                if ($lcPriLet <> $lcLetAnt) {
                                    $llCambio = true;
                                    //if ($lcColor == 'yellow') { $lcColor = ''; } else { $lcColor = 'yellow'; }
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
                                    $lcPintar   = '<span style="color: white; background: black;">' . $lcPriLet . '</span><a name="' . $lcPriLet . '"></a>';
                                    $lcColorLet = 'black';
                                }
                                echo '<td width="3%" style="background: ' . $lcColorLet . ';" align="center">' . $lcPintar . '</td>';
                            }

                            //echo '<td width="'.$lnAncCol .'%" valign="top" align="left" bgcolor="'.$lcColor.'">&nbsp;';
                            echo '<td width="' . $lnAncCol . '%" valign="top" align="left" bgcolor="' . $lcColor . '">&nbsp;';
                            //echo '<a href="#" onclick="fCargar('.$row ['CDPROVE'].', '."'".$row ['NOMCOMER']."'".')">';
                            ///echo '<a href="#">';
                            //echo '<span style="color: blue" onclick="fAccionDetalle'."('', '".$lcNomPagePro."', '".$row ['CDPROVE']."', '".$row ['NOMCOMER']."'".')">'.$lcRazon.'</span>';
                            echo '<span style="color: blue; cursor: pointer;" onclick="' . "fAccionDetalle('', '" . $lcNomPagePro . "', '" . $row ['CDPROVE'] . "', '" . $row ['NOMCOMER'] . "')" . '">' . $lcRazon . '</span>';
                            //echo '<span style="color: blue" onclick="'."alert('joder')".'">'.$lcRazon.'</span>';
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
        </div>
        <?php } ?>


        <?php if ($llPintarResumen) { ?>
        <input type="hidden" id="oDetalle" name="pIdMagento" value="<?php echo $pIdMagento; ?>" />
        <input type="hidden" id="oAnRap" name="oAnRap" value="<?php echo $oAnRap; ?>" />
        <br />
        <div id="dvResumen">

            <div class="table-flex" style="border: 1px solid <?php echo "#{$colorCorporativo}"; ?>;">

                <div class="tr-flex th-flex" style="color: #ffffff; background-color: <?php echo "#{$colorCorporativo}"; ?>">
                    <div class="td-flex" style="justify-content: center">PROVEEDOR</div>
                    <div class="td-flex texto-con-saltos" style="flex: 4; justify-content: left">NOMBRE</div>
                    <div class="td-flex" style="justify-content: center">CONSUMO ALM</div>
                    <div class="td-flex" style="justify-content: center">CONSUMO DIR</div>
                    <div class="td-flex" style="justify-content: center">RAPPEL ACTUAL</div>
                    <div class="td-flex" style="justify-content: center">IMPORTE RAPPEL</div>
                    <div class="td-flex" style="justify-content: center">SIGUIENTE RAPPEL</div>
                    <div class="td-flex" style="justify-content: center">LIMITE SIGUIENTE RAPPEL</div>
                    <div class="td-flex" style="justify-content: center">DIFERENCIA</div>
                </div>

                <?php

                $lMes      = 12;
                $lSocio    = $lcUser;
                $lSocioAnt = $laDatosClienteIntranet ['SOCIO_ANT'];

                $oConsumoSocioProveedor = new WebSociosAcumProv();
                $oConsumoSocioProveedor->depurarObjeto($llDepurar);
                $arrConsumos = $oConsumoSocioProveedor->getConsumoAgrupadoPorEjercicio($pgIdentificadorEmpresa, $lAnyo, $lMes, '', $lSocio, $lSocioAnt, true);

                $lnCon    = 0;
                $lnTotAlm = 0;
                $lnTotDir = 0;
                $lnTotRap = 0;

                //while ($row = fgVolcarArray($stmtSec)) {
                foreach ($arrConsumos as $row) {

                $lcProv   = $row['PRO'];
                $lcRaz    = $row['RAZ'];
                $lnConAlm = $row['ACU_ALM'];
                $lnConDir = $row['ACU_DIR'];

                $lnTotAlm = $lnTotAlm + $lnConAlm;
                $lnTotDir = $lnTotDir + $lnConDir;

                $lnRapAct       = 0;
                $lnSigNivRappel = 0;
                $lnSigNivDesde  = 0;
                $lnRapObt       = 0;

                $lnConsumoSiguienteNiv = 0;
                $lnTotCon              = 0;

                $oRapeles = new WebProveedorRap();
                $oRapeles->depurarObjeto($llDepurar);
                $oRapeles->empresa($pgIdentificadorEmpresa);
                $oRapeles->cdprove($lcProv);
                $oRapeles->anyo($lAnyo);
                $arrRapeles = $oRapeles->getRapeles(Conexion::getInstancia());

                //while ($rowPro = fgVolcarArray($stmtSecPro)) {
                foreach ($arrRapeles as $rowPro) {

                    $lnImporteDesde = $rowPro ['DESDE'];
                    $lnImporteHasta = $rowPro ['HASTA'];

                    if ($rowPro['IND_RAP_DIR'] == 0) {
                        $lnImpVal = ($lnConAlm);
                        //$lnImpVal = $lnImpVal+ (($lnImpVal / $lMes) * (12 - $lMes));
                        if (($lnImpVal > $lnImporteDesde) and (($lnImpVal < $lnImporteHasta) or (($lnImporteHasta == 0) and ($lnSigNivRappel == 0)))) {
                            $lnRapAct = $rowPro ['POR_RAP'];
                            $lnRapObt = $lnImpVal * ($lnRapAct / 100);
                            //$lnTotRap += $lnRapObt;
                            $lnSigNivRappel = 0;
                            $lnSigNivDesde  = 0;
                        } else {
                            if ($lnSigNivRappel == 0) {
                                $lnSigNivRappel = $rowPro ['POR_RAP'];
                                $lnSigNivDesde  = $rowPro ['DESDE'];
                                //$lnConsumoSiguienteNiv = round ((($lnSigNivDesde - ($lnConAlm)) / (12 - $lMes)),0);
                            }
                        }
                    } else {
                        $lnImpVal = ($lnConAlm + $lnConDir);
                        //$lnImpVal = $lnImpVal+ (($lnImpVal / $lMes) * (12 - $lMes));
                        if (($lnImpVal > $lnImporteDesde) and (($lnImpVal < $lnImporteHasta) or ($lnImporteHasta == 0))) {
                            $lnRapAct = $rowPro ['POR_RAP'];
                            $lnRapObt = $lnImpVal * ($lnRapAct / 100);
                            //$lnTotRap += $lnRapObt;
                        } else {
                            if ($lnSigNivRappel == 0) {
                                if ($lnImporteDesde > $lnImpVal) {
                                    $lnSigNivRappel = $rowPro ['POR_RAP'];
                                    $lnSigNivDesde  = $rowPro ['DESDE'];
                                    //$lnConsumoSiguienteNiv = round ((($lnSigNivDesde - ($lnConAlm + $lnConDir)) / (12 - $lMes)),0)+1;
                                }
                            }
                        }
                    }

                }

                $lnTotRap = $lnTotRap + $lnRapObt;

                //+Normalizar campos para pintarlos correctamente
                $lcConAlm       = number_format($lnConAlm, 2, ',', '.');
                $lcConDir       = number_format($lnConDir, 2, ',', '.');
                $lcRapAct       = number_format($lnRapAct, 2, ',', '.');
                $lcRapObt       = number_format($lnRapObt, 2, ',', '.');
                $lcSigNivRappel = number_format($lnSigNivRappel, 2, ',', '.');
                $lcSigNivDesde  = number_format($lnSigNivDesde, 2, ',', '.');
                //$lcConsumoSiguienteNiv  = number_format($lnConsumoSiguienteNiv, 2, ',', '');
                if (($lnSigNivDesde == 0) and ($lnImpVal > 0)) {
                    $lcDiferencia = '0.00';
                } else {
                    $lcDiferencia = number_format($lnSigNivDesde - ($lnImpVal), 2, ',', '.');
                }
                //-Normalizar campos para pintarlos correctamente

                $llPintarProv = true;

                $lcColorLetra = '';
                if ($row ['IND_ACT'] == 0) {
                    $lcColorLetra = 'intranet-LetraError';
                }

                if ($llPintarProv) {

                $lnCon   = $lnCon + 1;

                ?>
                <div class="tr-flex">
                    <div class="td-flex <?php echo $lcColorLetra; ?>" style="justify-content: center"><?php echo $lcProv; ?></div>
                    <div class="td-flex texto-con-saltos <?php echo $lcColorLetra; ?>" style="flex: 4; justify-content: flex-start"><?php echo $lcRaz; ?></div>
                    <div class="td-flex" style="justify-content: flex-end"><?php echo $lcConAlm; ?> &euro;</div>
                    <div class="td-flex" style="justify-content: flex-end"><?php echo $lcConDir; ?> &euro;</div>
                    <div class="td-flex" style="justify-content: center"><?php echo $lcRapAct; ?> %</div>
                    <div class="td-flex" style="justify-content: flex-end"><?php echo $lcRapObt; ?> &euro;</div>
                    <div class="td-flex" style="justify-content: center"><?php echo $lcSigNivRappel; ?> %</div>
                    <div class="td-flex" style="justify-content: flex-end"><?php echo $lcSigNivDesde; ?> &euro;</div>
                    <div class="td-flex" style="justify-content: flex-end"><?php echo $lcDiferencia; ?> &euro;</div>
                </div>

                <?php
                }
                }

                $lcTotAlm = number_format($lnTotAlm, 2, ',', '.');
                $lcTotDir = number_format($lnTotDir, 2, ',', '.');
                $lcTotRap = number_format($lnTotRap, 2, ',', '.');
                ?>

                <div class="tr-flex">
                    <div class="td-flex intranet-FilaTotal" style=""></div>
                    <div class="td-flex intranet-FilaTotal" style="flex: 4"></div>
                    <div class="td-flex intranet-FilaTotal" style="justify-content: flex-end"><?php echo $lcTotAlm; ?>  &euro;</div>
                    <div class="td-flex intranet-FilaTotal" style="justify-content: flex-end"><?php echo $lcTotDir; ?>  &euro;</div>
                    <div class="td-flex intranet-FilaTotal" style=""></div>
                    <div class="td-flex intranet-FilaTotal" style="justify-content: flex-end"><?php echo $lcTotRap; ?>  &euro;</div>
                    <div class="td-flex intranet-FilaTotal" style=""></div>
                    <div class="td-flex intranet-FilaTotal" style=""></div>
                    <div class="td-flex intranet-FilaTotal" style="justify-content: flex-end"></div>
                </div>

            </div>

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

</html>

<?php
}
?>
