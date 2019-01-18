<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 13/12/2018
 * Time: 12:50
 */

namespace App\Library;


class Iniutils
{

//
// (25/08/2015) CADENAS
//

    public static function MostrarAviso($Texto)
    {
        print '<script type="text/javascript">alert ("' . $Texto . '");</script>';
    }

//
// RECIBE LA FECHA EN FORMATO dd-MM-YYYY Y LA DEVUELVE CON EL NOMBRE DEL MES dd-MES_LETRA-yyyy
//

    public static function Redireccionar($URLDestino)
    {
        print '<script type="text/javascript">window.location="' . $URLDestino . '";</script>';
    }

//
// SUMAR DIAS A UNA FECHA (dd/mm/yyyy) FUNCIONA !!!
//

    public static function RedireccionarOtraVentana($URLDestino, $NombreVentana, $Parametros)
    {
        print '<script type="text/javascript">';
        print "window.open ('" . $URLDestino . "','" . $NombreVentana . "','" . $Parametros . "')";
        print '</script>';
    }

//
// (7/ENERO/2011) GENERAR FECHA CON FORMATO EN ESPAÑOL (dia_semana, DD/mes_letra/AÑO   hora)
//

    public static function convertirNumero($PrecioTexto)
    {
        $lPrecio = $PrecioTexto;
        $lComa   = strpos($PrecioTexto, ',');
        $lPunto  = strpos($PrecioTexto, '.');
        if (($lComa <> 0) and ($lPunto <> 0)) {
            if ($lPunto < $lComa) {
                $lPrecio = str_replace('.', '', $PrecioTexto);
            } else {
                $lPrecio = str_replace(',', '.', $PrecioTexto);
            }
        } else {
            if ($lComa <> 0) {
                $lPrecio = str_replace(',', '.', $PrecioTexto);
            }
        }

        return $lPrecio;
    }

    public static function dmyTOymd($pFecha, $pSeparador = '-')
    {
        $lcRetorno = '';
        if (!empty($pFecha)) {
            $dia       = substr($pFecha, 0, 2);
            $mes       = substr($pFecha, 3, 2);
            $anyo      = substr($pFecha, 6, 4);
            $lcRetorno = "{$anyo}{$pSeparador}{$mes}{$pSeparador}{$dia}";
        }

        return $lcRetorno;
    }

//
// (21/06/2012) ENVIAR EMAIL CON EL LOGO EMBEBIDO
//

    public static function esImagen($filename)
    {
        return strpos(self::mime_content_type($filename), 'image/') !== false;
    }

//
// SACAR UN AVISO EN PANTALLA
//

    public static function esPdf($filename)
    {
        return strpos(self::mime_content_type($filename), 'application/pdf') !== false;
    }

//
// CARGAR EN EL FRAME OTRA PAGINA
//

    public static function esPeticionAjax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(
                    $_SERVER['HTTP_X_REQUESTED_WITH']
                ) === 'xmlhttprequest') || (isset($_SERVER['X-Requested-With']) && strtolower(
                    $_SERVER['X-Requested-With']
                ) === 'xmlhttprequest');
    }

//
// CARGAR EN UN DETERMINADO FRAME UNA PAGINA
//

    public static function escribeEnLog($mensaje, $forzarEscritura = false)
    {
        if ($forzarEscritura || App::getInstancia()->esModoDepuracion()) {
            $log = new KLogger(App::getInstancia()->getRutaLog(), KLogger::DEBUG);

            if (is_array($mensaje)) {
                $mensaje = implode("\n", $mensaje);
            }

            $log->LogDebug($mensaje);
        }
    }

//
// (27/FEBRERO/2011) CONVERTIR FECHA EN INGLES EN ORACLE => FECHA ESPAÑOL
//

    public static function fConvertirFecha($pFecha, $pFormato)
    {
        $lcFecha = $pFecha;

        if (empty($pFormato)) {
            $pFormato = 'dd-mm-yyyy';
        }

        $laMes = [
            '01' => 'ENERO',
            '02' => 'FEBRERO',
            '03' => 'MARZO',
            '04' => 'ABRIL',
            '05' => 'MAYO',
            '06' => 'JUNIO',
            '07' => 'JULIO',
            '08' => 'AGOSTO',
            '09' => 'SEPTIEMBRE',
            '10' => 'OCTUBRE',
            '11' => 'NOVIEMBRE',
            '12' => 'DICIEMBRE'
        ];

        switch ($pFormato) {
            case 'dd-mm-yyyy':
                $lcMes    = substr($pFecha, 3, 2);
                $lcMesLet = $laMes [$lcMes];
                $lcFecha  = substr($pFecha, 0, 3) . $lcMesLet . substr($pFecha, 5, 6);
                break;
        }

        return $lcFecha;
    }

//
// RECIBE UN NUMERO Y DEVUELVE EL NOMBRE DEL MES
//

    public static function fDepurar($pTexto)
    {
        if (is_array($pTexto)) {
            $pTexto = implode(' ', $pTexto);
        }
        echo $pTexto . '<br>';
    }

//
// (13/SEPTIEMBRE/2011) DESCRIPCION DE TERMINO DE PAGO Y FORMA DE PAGO
//

    public static function fEnviarEmail(
        $lRemite,
        $lPara,
        $lCopia,
        $lRespuesta,
        $lAsunto,
        $lTexto,
        $pFicheroAdjunto,
        $lTipo
    ) {
        //require_once 'class.phpmailer.php';
        $mail = new PHPMailer();

        $mail->CharSet = 'UTF-8';

        $mail->IsSMTP();                                      // set mailer to use SMTP
        //$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
        //$mail->SMTPAuth = true;     // turn on SMTP authentication
        //$mail->Username = "jswan";  // SMTP username
        //$mail->Password = "secret"; // SMTP password

        $mail->From     = $lRemite;
        $mail->FromName = $lRemite;
        $mail->AddAddress($lPara, $lPara);

        if ($lCopia != '') {
            $mail->AddAddress($lCopia);                  // name is optional
        }

        $mail->AddReplyTo($lRemite, $lRemite);

        $mail->WordWrap = 50;                           // set word wrap to 50 characters

        if (is_array($pFicheroAdjunto)) {
            foreach ($pFicheroAdjunto as $fila) {
                if (sizeof($fila) >= 2) {
                    $mail->AddAttachment($fila[0], $fila[1]);    // add attachments
                } else {
                    $mail->AddAttachment($fila[0]);            // add attachments
                }
            }
        } else {
            if ($pFicheroAdjunto != '') {
                $mail->AddAttachment($pFicheroAdjunto);         // add attachments
            }
        }
        //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name

        if ($lTipo == 'TXT') {
            $mail->IsHTML(false);                           // set email format to HTML
        } else {
            $mail->IsHTML(true);                            // set email format to HTML
        }

        $mail->Subject = $lAsunto;
        $mail->Body    = $lTexto;
        $mail->AltBody = $lTexto;

        if (!$mail->Send()) {
            $llError = true;
        } else {
            $llError = false;
        }

        return $llError;
    }

    public static function fEnviarEmail_ImgEmb(
        $pEmbebLogo,
        $pEmbebImagen,
        $lRemite,
        $lPara,
        $lCopia,
        $lRespuesta,
        $lAsunto,
        $lTexto,
        $pFicheroAdjunto,
        $lTipo,
        $debug = false
    ) {

        //require_once("class.phpmailer.php");
        global $rutaLog;
        $log = new KLogger($rutaLog, KLogger::DEBUG);

        $mail          = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP();                                      // set mailer to use SMTP
        //$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
        //$mail->SMTPAuth = true;     // turn on SMTP authentication
        //$mail->Username = "jswan";  // SMTP username
        //$mail->Password = "secret"; // SMTP password

//+ENVIO EMAIL
        $mail->IsSMTP();                            // telling the class to use SMTP
        $mail->Host      = "smtp.comafe.es";        // SMTP server
        $mail->SMTPDebug = 0;                        // enables SMTP debug information (for testing)
//$mail->SMTPAuth   = false;                  // enable SMTP authentication
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "";                    // sets the prefix to the servier
        $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
//$mail->Username   = "b2b@comafe.es";  		// GMAIL username

        //+07/07/2017
        //$mail->Username = "b2b";        			// GMAIL username
        //$mail->Password = "Su#1bFa86";            // GMAIL password
        $mail->Username = "no_responda";            // Username
        $mail->Password = "7aa98FHioF-H";           // password
        //-07/07/2017

//$mail->AddReplyTo($lcReplyTo, $lcReplyTo);
//$mail->AddAddress($lcDestino, $lcDestino);
//if ($lcDestino_2 <> '') { $mail->AddAddress($lcDestino_2, $lcDestino_2); }
//if ($lcDestino_3 <> '') { $mail->AddAddress($lcDestino_3, $lcDestino_3); }
//$mail->SetFrom($lcSetFrom, 'Magento Server');
//$mail->Subject = $lAsunto;
//$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
//$mail->Body     = $lcLogProcesoPrevio.$lcLogProcesoFichero.$lcLogProcesoPost;
//$mail->Send();
//-

        $mail->From     = $lRemite;
        $mail->FromName = $lRemite;
        $mail->AddAddress($lPara, $lPara);

        if ($lCopia != '') {
            $mail->AddAddress($lCopia, $lCopia);                  // name is optional
        }

        if ($pEmbebLogo != '') {
            $mail->AddEmbeddedImage($pEmbebLogo, "Logo-attach", "logotipo.png");
        }

        if ($pEmbebImagen != '') {
            $mail->AddEmbeddedImage($pEmbebImagen, "Imagen-attach", "imagen.png");
        }

        $mail->AddReplyTo($lRemite, $lRemite);

        $mail->WordWrap = 50;                           // set word wrap to 50 characters

        if (is_array($pFicheroAdjunto)) {
            foreach ($pFicheroAdjunto as $fila) {
                if (sizeof($fila) >= 2) {
                    $mail->AddAttachment($fila[0], $fila[1]);    // add attachments
                } else {
                    $mail->AddAttachment($fila[0]);            // add attachments
                }
            }
        } else {
            if ($pFicheroAdjunto != '') {
                $mail->AddAttachment($pFicheroAdjunto);         // add attachments
            }
        }
        //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name

        if ($lTipo == 'TXT') {
            $mail->IsHTML(false);                           // set email format to HTML
        } else {
            $mail->IsHTML(true);                            // set email format to HTML
        }

        $mail->Subject = $lAsunto;
        $mail->Body    = $lTexto;
        $mail->AltBody = $lTexto;

        if (!$mail->Send()) {
            $llError = true;
            if ($debug) {
                $log->LogDebug($mail->ErrorInfo);
            }
        } else {
            $llError = false;
            if ($debug) {
                $log->LogDebug("Envio realizado correctamente a {$lPara} con asunto: {$lAsunto}.");
            }
        }

        return $llError;
    }

    public static function fFechaOracle_Spanish($pFecha)
    {
        $lcFecha_F = str_replace(
            "JAN",
            "ENE",
            str_replace("APR", "ABR", str_replace("AUG", "AGO", str_replace("DEC", "DIC", $pFecha)))
        );

        return $lcFecha_F;
    }

//
// (02/DIC/2012) GENERAR UNA CADENA ALEATORIA
//

    public static function fFecha_NombreMes($pMes, $mayusculas = true)
    {
        $lcMesLet = '';

        $pMes = intval($pMes) - 1;

        if ($pMes >= 0) {
            $laMes = [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ];

            $lcMesLet = $laMes[$pMes];
            if ($mayusculas) {
                $lcMesLet = strtoupper($lcMesLet);
            }
        }

        return $lcMesLet;
    }

//
// (11/02/2013) DES/CODIFICAR
//

    public static function fObtenerFechaEmail()
    {
        $laDia = [
            'Sun' => 'Domingo',
            'Mon' => 'Lunes',
            'Tue' => 'Martes',
            'Wed' => 'Miercoles',
            'Thu' => 'Jueves',
            'Fri' => 'Viernes',
            'Sat' => 'Sabado'
        ];

        $laMes = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        $lcFechaDia = $laDia [date("D")];
        $lcFechaDia .= date(", d/");
        $lcFechaDia .= $laMes [date("m")];
        $lcFechaDia .= date("/Y  G:i:s T");

        return $lcFechaDia;
    }

    public static function fgArt_UM_Descrip($pPres, $pTipo)
    {
        $lcDes = $pPres;
        switch ($pPres) {
            case 'UNID':
                $lcDes = 'UNIDAD';
                break;
            case 'BLIS':
                $lcDes = 'BLISTER';
                break;
            case '03':
                $lcDes = '#F5A9A9';
                break;
            case '04':
                $lcDes = '#F5BCA9';
                break;
            case '05':
                $lcDes = '#F5DA81';
                break;
            case '06':
                $lcDes = '#F3F781';
                break;
            case '07':
                $lcDes = '#E1F5A9';
                break;
            case '08':
                $lcDes = '#A9F5E1';
                break;
            case '09':
                $lcDes = '#A9D0F5';
                break;
            case '10':
                $lcDes = '#F5DA81';
                break;
            case '11':
                $lcDes = '#A9F5F2';
                break;
            case '12':
                $lcDes = '#F781D8';
                break;
            case '13':
                $lcDes = '#82FA58';
                break;
            case '14':
                $lcDes = '#BCA9F5';
                break;
            case '15':
                $lcDes = '#81BEF7';
                break;
            case '16':
                $lcDes = '#F78181';
                break;
            case '17':
                $lcDes = '#F7D358';
                break;
            case '18':
                $lcDes = '#F5BCA9';
                break;
            case '19':
                $lcDes = '#F5DA81';
                break;
            case '20':
                $lcDes = '#E1F5A9';
                break;
            case '21':
                $lcDes = 'F3F781';
                break;
            case '22':
                $lcDes = 'gold';
                break;
            case '23':
                $lcDes = 'black';
                break;
            case '24':
                $lcDes = 'white';
                break;
            case '25':
                $lcDes = '#A9D0F5';
                break;
            case '26':
                $lcDes = 'green';
                break;
            case '27':
                $lcDes = 'brown';
                break;
            case '28':
                $lcDes = 'black';
                break;
            case '29':
                $lcDes = 'white';
                break;
            case '30':
                $lcDes = '#F3F781';
                break;
        }

        switch ($pTipo) {
            case 'm':
            case 'F':
                $lcDes = strtolower($lcDes);
                break;

            case 'M':
                $lcDes = strtoupper($lcDes);
                break;
        }

        return ($lcDes);
    }

//
// (29/01/2014) AVERIGUA LA EXTENSION DE UN FICHERO
//

    public static function fgAveriguaExtencionFic($pNomFic)
    {
        $lcExtension = substr($pNomFic, strrpos($pNomFic, '.') + 1);

        return $lcExtension;
    }

//
// (22/NOVIEMBRE/2014) GENERAR ALBARAN
//
//
// ESTA FUNCION ESTA EN MOVIMIENTOS ALBARANES (PARA GENERAR EL .DAT) DE UN ALBARAN SELECCIONADO
//

    public static function fgDesEncripta($string, $key)
    {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char    = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char    = chr(ord($char) - ord($keychar));
            $result  .= $char;
        }

        return $result;
    }

    public static function fgDesFormaPago($pSemilla, $pFormato)
    {

        $lcDescrip = $pSemilla;

        if ($pFormato == 'FER') {

            switch ($pSemilla) {
                case '001':
                    $lcDescrip = 'TRANSFERENCIA';
                    break;
                case '003':
                    $lcDescrip = 'EFECTIVO';
                    break;
                case '004':
                    $lcDescrip = 'TALON';
                    break;
                case '005':
                    $lcDescrip = 'RECIBO';
                    break;
                case '008':
                    $lcDescrip = 'TRANSFERENCIA';
                    break;
                case '011':
                    $lcDescrip = 'TRANSFERENCIA';
                    break;
            }
        } else {

            switch ($pSemilla) {
                case '001':
                    $lcDescrip = 'TRANSFERENCIA';
                    break;
                case '003':
                    $lcDescrip = 'EFECTIVO';
                    break;
                case '004':
                    $lcDescrip = 'CRÉDITO';
                    break;
                case '005':
                    $lcDescrip = 'RECIBO';
                    break;
                case '007':
                    $lcDescrip = 'CONFIRMING';
                    break;
                case '011':
                    $lcDescrip = 'TRANSFERENCIA';
                    break;
            }
        }

        return $lcDescrip;
    }

    public static function fgDesTerminoPago($pSemilla, $pFormato)
    {

        $lcFacMet = $pSemilla;

        switch ($pSemilla) {
            case '000':
                $lcFacMet = 'CONTADO';
                break;
            case '04':
                $lcFacMet = '90 DIAS';
                break;
            case '001':
                $lcFacMet = 'CONTADO';
                break;
            case '002':
                $lcFacMet = '30 DIAS';
                break;
            case '030':
                $lcFacMet = '30 DIAS';
                break;
            case '003':
                $lcFacMet = '60 DIAS';
                break;
            case '005':
                $lcFacMet = '120 DIAS';
                break;
            case '060':
                $lcFacMet = '60 DIAS';
                break;
            case '004':
                $lcFacMet = '90 DIAS';
                break;
            case '008':
                $lcFacMet = '30, 60 y 85 DIAS';
                break;
            case '009':
                $lcFacMet = '90 y 120 DIAS';
                break;
            case '010':
                $lcFacMet = '90, 120 y 150 DIAS';
                break;
            case '011':
                $lcFacMet = '60 y 90 DIAS';
                break;
            case '016':
                $lcFacMet = '30,60,90 y 120 DIAS';
                break;
            case '019':
                $lcFacMet = '180 DIAS';
                break;
            case '021':
                $lcFacMet = '75 DIAS';
                break;
            case '023':
                $lcFacMet = '85 DIAS';
                break;
            case '024':
                $lcFacMet = '70 DIAS';
                break;
            case '045':
                $lcFacMet = '45 DIAS';
                break;
            case '050':
                $lcFacMet = '50 DIAS';
                break;
            case '085':
                $lcFacMet = '85 DIAS';
                break;
            case '090':
                $lcFacMet = '90 DIAS';
                break;
            case '120':
                $lcFacMet = '120 DIAS';
                break;
            case '150':
                $lcFacMet = '150 DIAS';
                break;
            case '960':
                $lcFacMet = '';
                break;
            case '997':
                $lcFacMet = '90 DIAS (SIN RECARGO)';
                break;
            case '998':
                $lcFacMet = '60 DIAS (SIN RECARGO)';
                break;
            case '999':
                $lcFacMet = '30 DIAS (SIN RECARGO)';
                break;
        }

        return $lcFacMet;
    }

    public static function fgEncripta($string, $key)
    {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char    = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char    = chr(ord($char) + ord($keychar));
            $result  .= $char;
        }

        return base64_encode($result);
    }

    public static function fgEsEfectivo_FP($pFormaPago)
    {

        $lEsEfectivo = false;

        switch ($pFormaPago) {
            case '001':            /* TRANSFERENCIA PROVEED.NACIONAL */
                $lEsEfectivo = true;
                break;
            case '002':            /* TRANSFERENCIA (SOLO EXTRANJ). */
                $lEsEfectivo = true;
                break;
            case '003':            /* EFECTIVO */
                $lEsEfectivo = true;
                break;
            case '004':            /* CRÉDITO DOCUMENTARIO */
                $lEsEfectivo = true;
                break;
            case '005':            /* RECIBO */
                $lEsEfectivo = false;
                break;
            case '006':            /* TALÓN BANCARIO */
                $lEsEfectivo = true;
                break;
            case '007':            /* CONFIRMING */
                $lEsEfectivo = true;
                break;
            case '008':            /* CONTR BELLOTA HERRAM- PAGARÉ */
                $lEsEfectivo = true;
                break;
            case '009':            /* CONTRATO SNA */
                $lEsEfectivo = true;
                break;
            case '010':            /* RECIBO DOMICILIADO */
                $lEsEfectivo = true;
                break;
            case '011':            /* TRANSFERENCIA */
                $lEsEfectivo = true;
                break;
            case 'EFECT':        /* EFECT */
                $lEsEfectivo = true;
                break;
            case 'RECIBO':        /* RECIBO TPV */
                $lEsEfectivo = false;
                break;
            case 'TALON':        /* TALON */
                $lEsEfectivo = true;
                break;
            case 'TARJ':        /* TARJ */
                $lEsEfectivo = true;
                break;
            case 'VALE':        /* VALE */
                $lEsEfectivo = false;
                break;
        }

        return $lEsEfectivo;
    }

// 08/02/2016

    public static function fgObtenerColorFamilia($pFamilia)
    {
        $lcColor = 'gold';
        switch ($pFamilia) {
            case '01':
                $lcColor = '#58FA82';
                break;
            case '02':
                $lcColor = '#BE81F7';
                break;
            case '03':
                $lcColor = '#F5A9A9';
                break;
            case '04':
                $lcColor = '#F5BCA9';
                break;
            case '05':
                $lcColor = '#F5DA81';
                break;
            case '06':
                $lcColor = '#F3F781';
                break;
            case '07':
                $lcColor = '#E1F5A9';
                break;
            case '08':
                $lcColor = '#A9F5E1';
                break;
            case '09':
                $lcColor = '#A9D0F5';
                break;
            case '10':
                $lcColor = '#F5DA81';
                break;
            case '11':
                $lcColor = '#A9F5F2';
                break;
            case '12':
                $lcColor = '#F781D8';
                break;
            case '13':
                $lcColor = '#82FA58';
                break;
            case '14':
                $lcColor = '#BCA9F5';
                $lcColor = '#BCA9F5';
                break;
            case '15':
                $lcColor = '#81BEF7';
                break;
            case '16':
                $lcColor = '#F78181';
                break;
            case '17':
                $lcColor = '#F7D358';
                break;
            case '18':
                $lcColor = '#F5BCA9';
                break;
            case '19':
                $lcColor = '#F5DA81';
                break;
            case '20':
                $lcColor = '#E1F5A9';
                break;
            case '21':
                $lcColor = 'F3F781';
                break;
            case '22':
                $lcColor = 'gold';
                break;
            case '23':
                $lcColor = 'black';
                break;
            case '24':
                $lcColor = 'white';
                break;
            case '25':
                $lcColor = '#A9D0F5';
                break;
            case '26':
                $lcColor = 'green';
                break;
            case '27':
                $lcColor = 'brown';
                break;
            case '28':
                $lcColor = 'black';
                break;
            case '29':
                $lcColor = 'white';
                break;
            case '30':
                $lcColor = '#F3F781';
                break;
        }

        return ($lcColor);
    }

    public static function fgRandomString($length = 10, $uc = true, $n = true, $sc = false)
    {
        $rstr   = "";
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if ($uc == 1) {
            $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($n == 1) {
            $source .= '1234567890';
        }
        if ($sc == 1) {
            $source .= '|@#~$%()=^*+[]{}-_';
        }
        if ($length > 0) {

            //$source = str_split($source,1);
            for ($i = 1; strlen($rstr) < $length; $i++) {
                mt_srand((double) microtime() * 1000000);
                $num  = mt_rand(1, strlen($source));
                $rstr .= substr($source, $num, 1);
            }
        }

        return $rstr;
    }

    public static function fgSoloLetra($pTipo, $pCadena)
    {
        // Compruebo que los caracteres sean los permitidos.
        $permitidos = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
        for ($i = 0; $i < strlen($pCadena); $i++) {
            if (strpos($permitidos, substr($pCadena, $i, 1)) === false) {
                return false;
            }
        }

        return true;
    }

    public static function fgSoloNumero($pTipo, $pCadena)
    {
        //compruebo que los caracteres sean los permitidos
        $permitidos = "1234567890";
        if ($pTipo == 'DEC') {
            $permitidos = "1234567890.,";
        }
        for ($i = 0; $i < strlen($pCadena); $i++) {
            if (strpos($permitidos, substr($pCadena, $i, 1)) === false) {
                return false;
            }
        }

        return true;
    }

    public static function fgStr_Limpia($pCadena, $pSusCarEsp)
    {
        //$string = rtrim(ltrim($pCadena));
        $string = trim($pCadena);

        $string = str_replace(
            ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],
            ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'],
            $string
        );

        $string = str_replace(
            ['é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'],
            ['e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'],
            $string
        );

        $string = str_replace(
            ['í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'],
            ['i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'],
            $string
        );

        $string = str_replace(
            ['ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'],
            ['o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'],
            $string
        );

        $string = str_replace(
            ['ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'],
            ['u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'],
            $string
        );

        $string = str_replace(
            ['ñ', 'Ñ', 'ç', 'Ç'],
            ['n', 'N', 'c', 'C',],
            $string
        );

        // Esta parte se encarga de eliminar cualquier caracter extraño.
        /*
            $string = str_replace(
                array("\", "¨", "º", "-", "~",
                     "#", "@", "|", "!", """,
                     "·", "$", "%", "&", "/",
                     "(", ")", "?", "'", "¡",
                     "¿", "[", "^", "<code>", "]",
                     "+", "}", "{", "¨", "´",
                     ">", "< ", ";", ",", ":",
                     ".", " "),
                '',
                $string
            );
        */
        $string = str_replace(
            [
                "¨",
                "º",
                "-",
                "~",
                "#",
                "@",
                "|",
                "!",
                "·",
                "$",
                "%",
                "&",
                "/",
                "(",
                ")",
                "?",
                "'",
                "¡",
                "¿",
                "[",
                "^",
                "<code>",
                "]",
                "+",
                "}",
                "{",
                "¨",
                "´",
                ">",
                "< ",
                ";",
                ",",
                ":",
                ".",
                "_"
            ],
            $pSusCarEsp,
            $string
        );

        return $string;
    }

    public static function fgSuma_Dias_Fecha($pfFecha, $pnDias)
    {
        $nuevafecha = $pfFecha; // Si el formato de la fecha es incorrecto, devolvemos la cadena de entrada.
        $pfFecha    = str_replace('/', '-', $pfFecha);

        if (preg_match('/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/', $pfFecha)) {
            list($dia, $mes, $anyo) = explode("-", $pfFecha);
            $fecNueva   = mktime(0, 0, 0, $mes, $dia, $anyo) + $pnDias * 24 * 60 * 60;
            $nuevafecha = date('d/m/Y', $fecNueva);
        }

        return $nuevafecha;
    }

    public static function mime_content_type($filename)
    {
        $mime_types = [

            'txt'  => 'text/plain',
            'htm'  => 'text/html',
            'html' => 'text/html',
            'php'  => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'xml'  => 'application/xml',
            'swf'  => 'application/x-shockwave-flash',
            'flv'  => 'video/x-flv',

            // images
            'png'  => 'image/png',
            'jpe'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'gif'  => 'image/gif',
            'bmp'  => 'image/bmp',
            'ico'  => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif'  => 'image/tiff',
            'svg'  => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip'  => 'application/zip',
            'rar'  => 'application/x-rar-compressed',
            'exe'  => 'application/x-msdownload',
            'msi'  => 'application/x-msdownload',
            'cab'  => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3'  => 'audio/mpeg',
            'qt'   => 'video/quicktime',
            'mov'  => 'video/quicktime',

            // adobe
            'pdf'  => 'application/pdf',
            'psd'  => 'image/vnd.adobe.photoshop',
            'ai'   => 'application/postscript',
            'eps'  => 'application/postscript',
            'ps'   => 'application/postscript',

            // ms office
            'doc'  => 'application/msword',
            'rtf'  => 'application/rtf',
            'xls'  => 'application/vnd.ms-excel',
            'ppt'  => 'application/vnd.ms-powerpoint',

            // open office
            'odt'  => 'application/vnd.oasis.opendocument.text',
            'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
        ];

        $ext = strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo    = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);

            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

    public static function pintaMensajeDepuracion($mensaje, $pintarMensaje = false)
    {
        if ($pintarMensaje || App::getInstancia()->esModoDepuracion()) {
            if (is_array($mensaje)) {
                $mensaje = '<li>' . implode('</li><li>', $mensaje) . '</li>';
            } else {
                $mensaje = "<li>{$mensaje}</li>";
            }

            $mensaje     = str_ireplace('<li>', '<li class="margen-izquierda-10">', $mensaje);
            $mensajeHTML = <<<MSG
<div class="cmf-table margen-arriba-10 margen-abajo-10">
    <div class="cmf-table-row">
        <div class="cmf-table-head izquierda azul">Depuración</div>
    </div>
    <div class="cmf-table-row">
        <div class="cmf-table-head izquierda azul">{$mensaje}</div>
    </div>
</div>
MSG;

            echo $mensajeHTML;
        }
    }

    /*
     * @return boolean
      */

    public static function ymdTOdmy($pFecha, $pSeparador = '-')
    {
        $lcRetorno = '';
        if (!empty($pFecha)) {
            $dia       = substr($pFecha, 8, 2);
            $mes       = substr($pFecha, 5, 2);
            $anyo      = substr($pFecha, 0, 4);
            $lcRetorno = "{$dia}{$pSeparador}{$mes}{$pSeparador}{$anyo}";
        }

        return $lcRetorno;
    }

    /**
     * (22/SEP/2011) RECIBE UNA EMPRESA, USUARIO y SECCION Y NOS DICE SUS PERMISOS ADM
     *
     * @param $pEmpresa
     * @param $pUsuario
     * @param $pSucursal
     * @param $pSeccion
     * @param $pPermiso
     * @return bool
     */
    function fPermisos($pEmpresa, $pUsuario, $pSucursal, $pSeccion, $pPermiso)
    {

        $llPermiso = false;
        $lcUser    = (int) $pUsuario . '-' . (int) $pSucursal;

        if ($pEmpresa == 'COM') {
            // COMAFE
            if (($lcUser == '888-36') or ($lcUser == '888-45') or ($lcUser == '888-68')) {
                $llPermiso = true;
            } else {
                // SECCIONES DE LA WEB
                switch ($pSeccion) {
                    case 'APROV_C':
                        if ($lcUser == '888-26') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-100') {
                            $llPermiso = true;
                        }
                    case 'APROV_F':
                        switch ($pPermiso) {
                            case 'ADM':
                                if ($lcUser == '888-26') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-100') {
                                    $llPermiso = true;
                                }
                                break;
                            case 'CON':
                                if ($lcUser == '888-26') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-100') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-1') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-113') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-6') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-201') {
                                    $llPermiso = true;
                                }
                                break;
                        }
                        break;
                    case 'ASAMBLEA':
                        if ($lcUser == '888-66') {
                            $llPermiso = true;
                        }
                    case 'PORTADA':
                        if ($lcUser == '888-11') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-19') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-95') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-999') {
                            $llPermiso = true;
                        }
                        break;
                    case 'PROVEEDOR':
                        switch ($pPermiso) {
                            case 'ADM_CONF':
                                //if ($lcUser == '888-101') { $llPermiso = true; }
                                if ($lcUser == '888-1') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-6') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-26') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-100') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-113') {
                                    $llPermiso = true;
                                }
                                break;
                            case 'COFEDAL':
                                break;
                            case 'CONSULTA':
                                if ($lcUser == '888-66') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-69') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-79') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-124') {
                                    $llPermiso = true;
                                }
                                break;
                            case 'PAGOS':
                                if ($lcUser == '888-66') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-69') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-79') {
                                    $llPermiso = true;
                                }
                                break;
                            case '183':
                                if ($lcUser == '888-67') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-73') {
                                    $llPermiso = true;
                                }
                                break;
                            case '183_CERO':
                                if ($lcUser == '888-1') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-6') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-113') {
                                    $llPermiso = true;
                                }
                                break;
                            case 'INC_RIESGO':
                                if ($lcUser == '888-66') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-69') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-124') {
                                    $llPermiso = true;
                                }
                                break;
                            case 'INC_IMPAGO':
                                if ($lcUser == '888-26') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '888-100') {
                                    $llPermiso = true;
                                }
                                break;
                        }
                        break;
                    case 'TABLON':
                        if ($lcUser == '888-1') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-11') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-19') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-39') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-66') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-82') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-93') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-95') {
                            $llPermiso = true;
                        }
                        break;
                    case 'CONF_IMP':
                        if ($lcUser == '888-26') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-100') {
                            $llPermiso = true;
                        }
                        break;
                    case 'CONS_RIECLI':
                        if ($lcUser == '888-66') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-69') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '888-124') {
                            $llPermiso = true;
                        }
                        break;
                }
            }
        } else {
            //FERRCASH
            if (($lcUser == '183-36') or ($lcUser == '183-45') or ($lcUser == '183-68')) {
                $llPermiso = true;
            } else {
                // SECCIONES DE LA WEB
                switch ($pSeccion) {
                    case 'PORTADA':
                        if ($lcUser == '183-11') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '183-95') {
                            $llPermiso = true;
                        }
                        break;
                    case 'PROVEEDOR':
                        switch ($pPermiso) {
                            case 'INC_RIESGO':
                                if ($lcUser == '183-11') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '183-73') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '183-95') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '183-66') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '183-69') {
                                    $llPermiso = true;
                                }
                                if ($lcUser == '183-124') {
                                    $llPermiso = true;
                                }
                                break;
                        }
                        break;
                    case 'TABLON':
                        if ($lcUser == '183-11') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '183-67') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '183-73') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '183-95') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '183-106') {
                            $llPermiso = true;
                        }
                        break;
                    case 'CONS_RIECLI':
                        if ($lcUser == '183-106') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '183-107') {
                            $llPermiso = true;
                        }
                        if ($lcUser == '183-108') {
                            $llPermiso = true;
                        }
                        break;
                }
            }
        }

        return $llPermiso;
    }

}