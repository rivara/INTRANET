<?php

require_once 'TableWebClientes.php';

class WebClientes extends TableWebClientes
{
    const ENTREGA_EN_PROVEEDOR = '0';
    
    public static function tipoClienteFiltroPlantilla($tipoCliente)
    {
        $tipoCliente = trim($tipoCliente);
        
        switch ($tipoCliente) {
            case 'SOCIO':
                $tipoClienteFiltroPlantilla = 'SOCIO';
                break;
            
            case 'TARICAT':
            case 'TARIBAL':
                $tipoClienteFiltroPlantilla = 'TARICAT';
                break;
            
            case 'CECOFERSA':
                $tipoClienteFiltroPlantilla = 'CECOFERSA';
                break;
            
            default:
                $tipoClienteFiltroPlantilla = '';
        }
        
        return $tipoClienteFiltroPlantilla;
    }
    
    public function actualizarRiesgoDisponible(PDO $pConexion, $pTotal)
    {
        $oConsulta = null;
        $lTotal    = str_replace(',', '.', $pTotal);
        
        if (($lTotal <> '')) {
            $lSql = "UPDATE {$this->tableName} c";
            $lSql .= " SET RIESGO_DISP = RIESGO_DISP - $lTotal";
            $lSql .= " , RIESGO_DISP_LIM = RIESGO_DISP_LIM - $lTotal";
            $lSql .= " WHERE (c.cdclien={$this->cdclien} AND c.cdsucur={$this->cdsucur})";
            
            $this->lastSqlExecuted = $lSql;
            $oConsulta             = $pConexion->prepare($lSql);
            $oConsulta->execute();
            $aErr = $oConsulta->errorInfo();
            if ($aErr[2] <> '') {
                $this->pintarScreen('ERROR', $aErr[2]);
            }
            if ($this->depurarObjeto) {
                $this->pintarScreen('DEBUG', $this->lastSqlExecuted);
            }
        }
    }
    
    /**
     * @param PDO $conexion
     *
     * @return bool
     */
    public function getCodigoPostal(PDO $conexion)
    {
        $codigoPostal = '';
        
        if ($this->obtenerPorId($conexion)) {
            $codigoPostal = $this->codPostal();
        }
        
        return $codigoPostal;
    }
    
    /**
     * @param PDO $conexion
     *
     * @return mixed
     */
    public function getDatosTercero(PDO $conexion)
    {
        $where     = "cdclien = {$this->cdclien()} AND cdsucur = {$this->cdsucur()}";
        $orderBy   = '';
        $limit     = '';
        $registros = $this->obtenerRegistros($conexion, $where, $orderBy, $limit);
        
        return $registros[0];
    }
    
    /**
     * @param PDO $conexion
     *
     * @return string
     */
    public function getDescripcionSucursal(PDO $conexion)
    {
        $descripcion = '';
        
        if ($this->cdsucur() === self::ENTREGA_EN_PROVEEDOR) {
            $descripcion = 'Instalaciones del proveedor';
        } else {
            if ($this->obtenerPorId($conexion)) {
                $descripcion = <<<DES
Sucursal {$this->cdsucur()}, {$this->domicilio()}, {$this->codPostal()}, {$this->localidad()}, {$this->pais()}
DES;
            }
        }
        
        return $descripcion;
    }
    
    public function getFormaPagoPredefinida(PDO $conexion)
    {
        $formaPagoPredefinida = '';
        
        if ($this->obtenerPorId($conexion)) {
            $formaPagoPredefinida = $this->formaPagoDirecto();
        }
        
        return $formaPagoPredefinida;
    }
    
    /**
     * @param PDO $conexion
     *
     * @return bool
     */
    public function getPais(PDO $conexion)
    {
        $this->obtenerPorId($conexion);
        
        return $this->pais();
    }
    
    /**
     * @param PDO $conexion
     *
     * @return bool
     */
    public function getProvincia(PDO $conexion)
    {
        $this->obtenerPorId($conexion);
        
        return $this->provincia();
    }
    
    /**
     * @param $prefijo
     *
     * @return mixed|string
     */
    public function getProvinciaByPrefijoCodigoPostal($prefijo)
    {
        $provincia = '';
        
        $prefijos = [
            '01' => 'ALAVA',
            '02' => 'ALBACETE',
            '03' => 'ALICANTE',
            '04' => 'ALMERIA',
            '05' => 'AVILA',
            '06' => 'BADAJOZ',
            '07' => 'BALEARES',
            '08' => 'BARCELONA',
            '09' => 'BURGOS',
            '10' => 'CACERES',
            '11' => 'CADIZ',
            '12' => 'CASTELLON',
            '13' => 'CIUDAD REAL',
            '14' => 'CORDOBA',
            '15' => 'LA CORUÑA',
            '16' => 'CUENCA',
            '17' => 'GERONA',
            '18' => 'GRANADA',
            '19' => 'GUADALAJARA',
            '20' => 'GUIPUZCOA',
            '21' => 'HUELVA',
            '22' => 'HUESCA',
            '23' => 'JAEN',
            '24' => 'LEON',
            '25' => 'LERIDA',
            '26' => 'LOGROÑO',
            '27' => 'LUGO',
            '28' => 'MADRID',
            '29' => 'MALAGA',
            '30' => 'MURCIA',
            '31' => 'NAVARRA',
            '32' => 'ORENSE',
            '33' => 'ASTURIAS',
            '34' => 'PALENCIA',
            '35' => 'GRAN CANARIA',
            '36' => 'PONTEVEDRA',
            '37' => 'SALAMANCA',
            '38' => 'TENERIFE',
            '39' => 'SANTANDER',
            '40' => 'SEGOVIA',
            '41' => 'SEVILLA',
            '42' => 'SORIA',
            '43' => 'TARRAGONA',
            '44' => 'TERUEL',
            '45' => 'TOLEDO',
            '46' => 'VALENCIA',
            '47' => 'VALLADOLID',
            '48' => 'VIZCAYA',
            '49' => 'ZAMORA',
            '50' => 'ZARAGOZA',
            '51' => 'CEUTA',
            '52' => 'MELILLA'
        ];
        
        if (in_array($prefijo, $prefijos)) {
            $provincia = $prefijos[$prefijo];
        }
        
        return $provincia;
    }
    
    /**
     * @param $prefijo
     *
     * @return int|string
     */
    public function getRegion($prefijo)
    {
        $region = '';
        
        $regiones = [
            'PENINSULA'       => [
                '01',
                '02',
                '03',
                '04',
                '05',
                '06',
                '08',
                '09',
                '10',
                '11',
                '12',
                '13',
                '14',
                '15',
                '16',
                '17',
                '18',
                '19',
                '20',
                '21',
                '22',
                '23',
                '24',
                '25',
                '26',
                '27',
                '28',
                '29',
                '30',
                '31',
                '32',
                '33',
                '34',
                '36',
                '37',
                '39',
                '40',
                '41',
                '42',
                '43',
                '44',
                '45',
                '46',
                '47',
                '48',
                '49',
                '50'
            ],
            'BALEARES'        => ['07'],
            'CANARIAS'        => ['35', '38'],
            'CEUTA Y MELILLA' => ['51', '52']
        ];
        
        foreach ($regiones as $nombreRegion => $prefijos) {
            if (in_array($prefijo, $prefijos)) {
                $region = $nombreRegion;
            }
        }
        
        return $region;
    }
    
    /**
     * @param $conexion
     *
     * @return array
     */
    public function getSucursales(PDO $conexion)
    {
        $sucursales = [];
        
        if ($this->cdclien()) {
            $where      = "cdclien = {$this->cdclien()} AND IND_ACT = 1 AND IND_DIR = 1";
            $orderBy    = '';
            $limit      = '';
            $sucursales = $this->obtenerRegistros($conexion, $where, $orderBy, $limit);
        }
        
        return $sucursales;
    }
    
    /**
     * @param PDO $conexion
     *
     * @return string
     */
    public function getZonaGeografica(PDO $conexion)
    {
        $zona = '';
        
        $pais = mb_strtoupper($this->getPais($conexion));
        
        switch ($pais) {
            case 'ESPAÑA':
                $provincia = $this->getProvincia($conexion);
                if (!empty($provincia)) {
                    if ($this->esProvinciaCanaria($provincia)) {
                        $zona = 'CANARIAS';
                    } else if ($this->esProvinciaBalear($provincia)) {
                        $zona = 'BALEARES';
                    } else if ($this->esProvinciaAfricana($provincia)) {
                        $zona = 'CEUTA Y MELILLA';
                    } else {
                        $zona = 'PENINSULA';
                    }
                }
                break;
            
            case 'PORTUGAL':
                $zona = $pais;
                break;
        }
        
        return $zona;
    }
    
    public function usaProntoPago(PDO $conexion)
    {
        $usaProntoPago = false;
        
        if ($this->obtenerPorId($conexion)) {
            $usaProntoPago = $this->indicadorProntoPagoDirecto() == 1;
        }
        
        return $usaProntoPago;
    }
    
    /**
     * @param $pImporteOperacion
     * @param $pTipoAvisoError
     *
     * @return bool
     */
    public function validarRiesgoDisponible($pImporteOperacion, &$pTipoAvisoError)
    {
        global $gnImporteMaximoConformidad, $gnImporteMinimoRiesgo;
        
        require_once dirname(__FILE__) . '/../Const_Vars.inc';
        
        if ($this->depurarObjeto) {
            $this->pintarScreen('DEBUG', 'Empresa Ferrcash');
        }
        
        $lAprobar            = true;
        $pTipoAvisoError     = '';
        $lnCli_RiesgoDisp    = $this->riesgoDisp;
        $lnCli_RiesgoDispLim = 0;

        if ($this->depurarObjeto) {
            /** @noinspection PhpUndefinedVariableInspection */
            $this->pintarScreen(
                'DEBUG',
                implode(
                    ' / ',
                    [
                        "Cli/Suc: {$this->cdclien}/{$this->cdsucur}",
                        "IndAct / IndDir: {$this->indAct()}/{$this->indDir()}",
                        "Forma Pago: {$this->formaPago()}",
                        "Importe Operacion: {$pImporteOperacion}",
                        "Riesgo disp: {$lnCli_RiesgoDisp}",
                        "Limite en: {$lnCli_RiesgoDispLim}",
                        "Importe Maximo Permitido: {$gnImporteMaximoConformidad}",
                        "Importe Minimo Permitido: {$gnImporteMinimoRiesgo}"
                    ]
                )
            );
        }
        
        // Si el importe supera el maximo permitido.
        if (($pImporteOperacion > $gnImporteMaximoConformidad)) {
            $lAprobar        = false;
            $pTipoAvisoError = 'MAX';
            if ($this->depurarObjeto) {
                $this->pintarScreen('DEBUG', "Importe mayor que: $gnImporteMaximoConformidad");
            }
        } else {
            // Valido riesgo, si el el importe es mayor del minimo.
            if ($pImporteOperacion > $gnImporteMinimoRiesgo) {
                //El total es mayor que el riesgo disponible
                if ($pImporteOperacion > $lnCli_RiesgoDisp) {
                    $lAprobar        = false;
                    $pTipoAvisoError = 'RIE';
                    //Validar el riesgo Limite
                    if ($lnCli_RiesgoDispLim > $pImporteOperacion) {
                        $lAprobar        = true;
                        $pTipoAvisoError = 'LIM';
                    }
                }
            } else {
                if ($this->depurarObjeto) {
                    $this->pintarScreen('DEBUG', "No se valida porque el importe mayor que: $gnImporteMinimoRiesgo");
                }
            }
        }
        
        if ($this->depurarObjeto) {
            $this->pintarScreen('DEBUG', "Aprobado: $lAprobar / Aviso Riesgo: $pTipoAvisoError");
        }
        
        return ($lAprobar);
    }
    
    //02/03/2017
    
    /**
     * @param $provincia
     *
     * @return bool
     */
    private function esProvinciaAfricana($provincia)
    {
        return in_array($provincia, ['CEUTA', 'MELILLA']);
    }
    
    /**
     * @param $provincia
     *
     * @return bool
     */
    private function esProvinciaBalear($provincia)
    {
        return in_array($provincia, ['PALMA DE MALLORCA', 'MAÓ']);
    }
    
    /**
     * @param $provincia
     *
     * @return bool
     */
    private function esProvinciaCanaria($provincia)
    {
        return in_array(mb_strtoupper($provincia), ['LAS PALMAS', 'SANTA CRUZ DE TENERIFE']);
    }

    /**
     * @return bool
     */
    public function esPrepago()
    {
        return ( ($this->formaPago() == '000') or ($this->indDir() == 0)  );
    }

    /**
     * @param $conexion
     *
     * @return array
     */
    public function getGlns(PDO $conexion)
    {
        $lcCadSql  = "SELECT 'CLI' TIPO, punto_operacional GLN, nif NIF, cdclien CDCLIEN, cdsucur CDSUCUR, ind_act IND_ACT";
        $lcCadSql .= ", NOMBRE NOMBRE, DOMICILIO DOMICILIO, COD_POSTAL CP, LOCALIDAD LOCALIDAD, ' ' PROVINCIA";
        $lcCadSql .= " FROM  {$this->tableName()}";
        $lcCadSql .= " WHERE punto_operacional <> '' AND ind_act = 1";

        $this->lastSqlExecuted($lcCadSql);
        $oConsulta = $conexion->prepare($lcCadSql);
        $oConsulta->execute();
        $aErr = $oConsulta->errorInfo();
        if ($aErr[2] <> '') {
            $this->pintarScreen('ERROR', $aErr[2].' (SQL: '.$lcCadSql.' )');
            $this->log->LogError($lcCadSql);
        }
        if ($this->depurarObjeto) { $this->pintarScreen('DEBUG', $this->lastSqlExecuted); }
        $arrGlns = $oConsulta->fetchAll(PDO::FETCH_ASSOC);

        return $arrGlns;
    }

    /**
     * @return string
     */
    public function DevolverCampoPrecio()
    {
        $lCampoPrecio = '';
        if (($this->tipoCliente() == 'CLIENTE') or ($this->tipoCliente() == '')) {
            $lCampoPrecio = 'PVP_CASH';
        } else {
            if (($this->tipoCliente() == 'FERROKEY') or ($this->tipoCliente() == 'CADENA-A')) {
                $lCampoPrecio = 'PVP_FRKY';
            } else {
                if( ($this->tipoCliente() == 'TARIFA-B') or ($this->tipoCliente() == 'CADENA-B')) {
                    $lCampoPrecio = 'PVP_CADB';
                }
            }
        }

        return $lCampoPrecio;
    }
}
