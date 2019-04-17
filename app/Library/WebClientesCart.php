<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 10/04/2019
 * Time: 12:07
 */

namespace App\Library;


class WebClientesCart extends TableWebClientesCart
{

    //Especialmente para los mails
    private $Logo_Email;
    private $SocioSucursal_Email;
    private $SocioSucursal_Nombre;
    private $SocioSucursal_TipoCliente;

    public function __construct($params = false)
    {
        global $rutaLog;

        parent::__construct($params);

        //  $this->log = new KLogger($rutaLog, KLogger::DEBUG);
        $this->depurarObjeto(false);

        if (is_array($params)) {
            // Verificar si el usuario es depurador.
            $idUsuarioMagento = Helper::getFromArray($params, 'idUsuarioMagento');
            $this->depurarObjeto(!Iniutils::esPeticionAjax() && WebAdmConfUsu::usuarioPuedeDepurar($idUsuarioMagento));
        }
    }

    /**
     * @param PDO $pConexion
     * @internal param array $tickets
     */
    function CapturarDatosGenericosEmail(PDO $pConexion)
    {
        global $Rutas;

        // Obtener dato del cliente.
        $oTercero          = new WebClientes();
        $oTercero->cdclien = $this->cdclien;
        $oTercero->cdsucur = $this->cdsucur;
        if ($oTercero->obtenerPorId($pConexion)) {
            $this->SocioSucursal_Nombre      = $oTercero->nombre;
            $this->SocioSucursal_TipoCliente = $oTercero->tipoCliente;
            $this->SocioSucursal_Email       = $oTercero->emailConf;
        }

        //Logotipo emails
        $lcDirBase        = dirname(__FILE__) . '/../../';
        $this->Logo_Email = $lcDirBase . 'images/Logos/LogoFerrCash_new.png';
    }


    /**
     * @param PDO $pConexion
     * @param int $pAutor
     * @param string $pAviso
     * @param string $pPantalla
     * @param string $pAccion
     * @param array $arrLineas
     */
    function EnviarCorreos(PDO $pConexion, $pAutor = 0, $pAviso = '', $pPantalla = '', $pAccion = '', $arrLineas = [])
    {
        global $pgNombreWeb;
        global $EmailsCliente;
        global $glServidorDevelop;
        global $pgIdentificadorEmpresa;
        global $UsersWebAdmin;

        $this->CapturarDatosGenericosEmail($pConexion);

        $arrEmailsSocio = [];
        $lTipoEmail     = 'FALTAS';
        $lTipCliente    = $this->SocioSucursal_TipoCliente;

        $lRemiteCli = $this->SocioSucursal_Email;

        //+Averiguar a que emails tengo que enviar los correos
        $webEmailsUsuarios = new WebAdmEmailUsuarios();
        $webEmailsUsuarios->depurarObjeto($this->depurarObjeto());
        $arrEmails = $webEmailsUsuarios->getIdEmail(Conexion::getInstancia(), $pgIdentificadorEmpresa, $this->cdclien(), $this->cdsucur(), 'INTRANET', $lTipCliente, $lTipoEmail);
        if (count($arrEmails) > 0) {
            $iCnt = 0;
            foreach ($arrEmails as $aEmail) {
                if ($aEmail['EMAIL']  <> '') {
                    $arrEmailsSocio[$iCnt] = $aEmail['EMAIL'];
                    $iCnt++;
                }
            }
        } else {
            $arrEmailsSocio[0] = $lRemiteCli;
        }
        //-Averiguar a que emails tengo que enviar los correos

        $lTextArts = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">";
        $lTextArts .= "<tr>";
        $lTextArts .= "<td width='120' align='center' class='td_fila_titulo'><span class='td_letra_titulo'>PEDIDO </span></td>";
        $lTextArts .= "<td width='100' align='center' class='td_fila_titulo'><span class='td_letra_titulo'>FECHA </span></td>";
        $lTextArts .= "<td width='125' align='center' class='td_fila_titulo'><span class='td_letra_titulo'>ARTICULO </span></td>";
        $lTextArts .= "<td align='left' class='td_fila_titulo'><span class='td_letra_titulo'>DESCRIPCION </span></td>";
        $lTextArts .= "<td width='120' align='right' class='td_fila_titulo'><span class='td_letra_titulo'>CANTIDAD&nbsp; </span></td>";
        $lTextArts .= "<td width='150' align='left' class='td_fila_titulo'><span class='td_letra_titulo'>&nbsp;PRESENTACION </span></td>";
        $lTextArts .= "</tr>";

        $lnNumeroArticulos = 0;
        foreach ($arrLineas as $aLinea) {

            $lnNumeroArticulos = $lnNumeroArticulos + 1;

            $lcColor = '';
            if ($lnNumeroArticulos % 2 == 0) {
                $lcColor = '#FFFFCC';
            }
            $lcFecha = Iniutils::ymdTOdmy($aLinea['FECHA'], '/');

            $lTextArts .= "<tr>";
            $lTextArts .= "<td bgcolor='{$lcColor}' align='center' valign='top'><span class='letra_email'>{$aLinea['PEDIDO']}</span></td>";
            $lTextArts .= "<td bgcolor='{$lcColor}' align='center' valign='top'><span class='letra_email'>{$lcFecha}</span></td>";
            $lTextArts .= "<td bgcolor='{$lcColor}' align='center' valign='top'><span class='letra_email'>{$aLinea['CDARTI']}</span></td>";
            $lTextArts .= "<td bgcolor='{$lcColor}' align='left' valign='top'><span class='letra_email'>{$aLinea['DESCRIP']}</span></td>";
            $lTextArts .= "<td bgcolor='{$lcColor}' align='right' valign='top'><span class='letra_email'>{$aLinea['CANTIDAD']}&nbsp;</span></td>";
            $lTextArts .= "<td bgcolor='{$lcColor}' align='left' valign='top'><span class='letra_email'>&nbsp;{$aLinea['UM']}</span></td>";
            $lTextArts .= "</tr>";
        }

        $lTextArts .= "</table>";

        //Datos genericos para cualquier email
        $lLogo    = $this->Logo_Email;
        $lcAsunto = "({$pgNombreWeb}). ELIMINAR FALTAS ( {$this->cdclien()} / {$this->cdsucur()} ) - {$pAccion}.";

        $laDatosEmail ['AVISO']    = $pAviso;
        $laDatosEmail ['CLI_SUC']  = "{$this->cdclien()}/{$this->cdsucur()} $this->SocioSucursal_Nombre";
        $laDatosEmail ['PANTALLA'] = $pPantalla;
        $laDatosEmail ['ACCION']   = $pAccion;
        $laDatosEmail ['LINEAS']   = $lTextArts;
        $laDatosEmail ['PIE']      = "";

        //Enviar los correos al cliente
        if (count($arrEmailsSocio) > 0) {

            $lcCuerpo = fg_Genera_Body_Email($pgIdentificadorEmpresa, $lTipoEmail, $laDatosEmail);

            for($iCnt = 0; $iCnt < count($arrEmailsSocio); $iCnt++) {

                $lRemiteCli = $arrEmailsSocio[$iCnt];

                if (($this->depurarObjeto) or ($glServidorDevelop)) {
                    $this->log->LogDebug("Tendria que enviar el email a: {$lRemiteCli} aunque se lo envio a: {$UsersWebAdmin[0]}");
                    $lRemiteCli = $UsersWebAdmin[0];
                }

                // Enviar correo al cliente
                Iniutils::fEnviarEmail_ImgEmb(
                    $lLogo,
                    '',
                    $EmailsCliente['DireccionPaginaWeb'],
                    $lRemiteCli,
                    '',
                    '',
                    $lcAsunto,
                    $lcCuerpo,
                    '',
                    'HTML'
                );

            }

        }
        $laDatosEmail['PIE'] = " GRABADO POR $pAutor - ENVIADA A: {$lRemiteCli}";

        // Enviar correos a administradores.
        for ($NumDir = 0; $NumDir < count($UsersWebAdmin); $NumDir++) {

            $lcCuerpo = fg_Genera_Body_Email($pgIdentificadorEmpresa, 'FALTAS', $laDatosEmail);

            $lcPara = $UsersWebAdmin[$NumDir];

            if ($this->depurarObjeto) {
                $this->pintarScreen('DEBUG', "Enviar email pedido para los admin: $lcPara");
            }

            Iniutils::fEnviarEmail_ImgEmb(
                $lLogo,
                '',
                $EmailsCliente['DireccionPaginaWeb'],
                $lcPara,
                '',
                '',
                "{$lcAsunto} (COPIA)",
                $lcCuerpo,
                '',
                'HTML'
            );
        }
    }


    /**
     * @param PDO $pConexion
     * @param integer $pSocio
     * @param integer $pSucursal
     */
    public function vaciar(PDO $pConexion, $pSocio, $pSucursal) {

    }
}