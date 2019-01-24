<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 13/12/2018
 * Time: 12:38
 */

namespace App\Library;


class TableWebAdmLog
{

// CAMPOS
    public $idlog;
    public $cdclien;
    public $cdsucur;
    public $empresa;
    public $seccion;
    public $des;
    public $fex;
    public $dirIp;
    public $userMag;
    public $lastSqlExecuted;
    public $tableName;
    public $depurarObjeto;

// GET GENERAL
    public function get($name) {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
    }


// SET GENERAL
    public function set($name, $value) {
        if (!isset($this->vars[$name])) {
            $this->vars[$name] = $value;
        }
    }


// SET/GET DE LOS CAMPOS
    public function idlog($val = false) {
        if ($val !== false) {
            $this->idlog= $val;
        }

        return $this->idlog;
    }

    public function cdclien($val = false) {
        if ($val !== false) {
            $this->cdclien= $val;
        }

        return $this->cdclien;
    }

    public function cdsucur($val = false) {
        if ($val !== false) {
            $this->cdsucur= $val;
        }

        return $this->cdsucur;
    }

    public function empresa($val = false) {
        if ($val !== false) {
            $this->empresa= $val;
        }

        return $this->empresa;
    }

    public function seccion($val = false) {
        if ($val !== false) {
            $this->seccion= $val;
        }

        return $this->seccion;
    }

    public function des($val = false) {
        if ($val !== false) {
            $this->des= $val;
        }

        return $this->des;
    }

    public function fex($val = false) {
        if ($val !== false) {
            $this->fex= $val;
        }

        return $this->fex;
    }

    public function dirIp($val = false) {
        if ($val !== false) {
            $this->dirIp= $val;
        }

        return $this->dirIp;
    }

    public function userMag($val = false) {
        if ($val !== false) {
            $this->userMag= $val;
        }

        return $this->userMag;
    }

    public function lastSqlExecuted($val = false) {
        if ($val !== false) {
            $this->lastSqlExecuted = $val;
        }

        return $this->lastSqlExecuted;
    }

    public function tableName($val = false) {
        if ($val !== false) {
            $this->tableName = $val;
        }

        return $this->tableName;
    }

    public function depurarObjeto($val = false) {
        if ($val !== false) {
            $this->depurarObjeto = $val;
        }

        return $this->depurarObjeto;
    }


// CONSTRUCTOR
    public function __construct($params = false) {
        global $rutaLog;
        //$this->log = new KLogger($rutaLog, KLogger::DEBUG);
        $this->idlog(isset($params['IDLOG'])? $params['IDLOG']: 0);
        $this->cdclien(isset($params['CDCLIEN'])? $params['CDCLIEN']: false);
        $this->cdsucur(isset($params['CDSUCUR'])? $params['CDSUCUR']: false);
        $this->empresa(isset($params['EMPRESA'])? $params['EMPRESA']: false);
        $this->seccion(isset($params['SECCION'])? $params['SECCION']: false);
        $this->des(isset($params['DES'])? $params['DES']: false);
        $this->fex(isset($params['FEX'])? $params['FEX']: 'now()');
        $this->dirIp(isset($params['DIR_IP'])? $params['DIR_IP']: false);
        $this->userMag(isset($params['USER_MAG'])? $params['USER_MAG']: false);
        $this->tableName = 'web_adm_log';
        //Codigo propio
        //trim($_SERVER['REMOTE_ADDR']);
    }

// VACIAR DATOS OBJETO
    public function limpiar() {
        $this->idlog = 0;
        $this->cdclien = NULL;
        $this->cdsucur = NULL;
        $this->empresa = NULL;
        $this->seccion = NULL;
        $this->des = NULL;
        $this->fex = 'now()';
        $this->dirIp = NULL;
        $this->userMag = NULL;
    }

// FUNCIONES SQLs EDICION DATOS
    public function sqlInsert() {
        $strCadSql  = 'INSERT INTO '.$this->tableName.' (';
        $strCadSql .= 'IDLOG,CDCLIEN,CDSUCUR,EMPRESA,SECCION,DES,FEX,DIR_IP,USER_MAG';
        $strCadSql .= ') VALUES (';
        $strCadSql .= $this->idlog;
        $strCadSql .= ','."'".$this->cdclien."'";
        $strCadSql .= ','."'".$this->cdsucur."'";
        $strCadSql .= ','."'".$this->empresa."'";
        $strCadSql .= ','."'".$this->seccion."'";
        $strCadSql .= ','."'".$this->des."'";
        if (strtoupper(str_replace(' ','',$this->fex)) == 'NOW()') {
            $strCadSql .= ','.$this->fex;
        } else {
            if ($this->fex == NULL) {
                $strCadSql .= ', NULL';
            } else {
                $strCadSql .= ",'".$this->fex."'";
            }
        }
        $strCadSql .= ','."'".$this->dirIp."'";
        $strCadSql .= ','."'".$this->userMag."'";
        $strCadSql .= ')';
        return $strCadSql;
    }

    public function sqlUpdate() {
        $strCadSql  = 'UPDATE '.$this->tableName.' SET ';
        $strCadSql .= 'CDCLIEN = '."'".$this->cdclien."'";
        $strCadSql .= ',CDSUCUR = '."'".$this->cdsucur."'";
        $strCadSql .= ',EMPRESA = '."'".$this->empresa."'";
        $strCadSql .= ',SECCION = '."'".$this->seccion."'";
        $strCadSql .= ',DES = '."'".$this->des."'";
        if (strtoupper(str_replace(' ','',$this->fex)) == 'NOW()') {
            $strCadSql .= ',FEX = '.$this->fex;
        } else {
            if ($this->fex == NULL) {
                $strCadSql .= ', FEX = NULL';
            } else {
                $strCadSql .= ', FEX = '."'".$this->fex."'";
            }
        }
        $strCadSql .= ',DIR_IP = '."'".$this->dirIp."'";
        $strCadSql .= ',USER_MAG = '."'".$this->userMag."'";
        $strCadSql .= ' WHERE IDLOG = '.$this->idlog;
        return $strCadSql;
    }

    public function sqlDelete() {
        $strCadSql  = 'DELETE FROM '.$this->tableName;
        $strCadSql .= ' WHERE IDLOG = '.$this->idlog;
        return $strCadSql;
    }

// FUNCIONES ACCESO A DATOS
    public function sqlSelect($pTipo, $pSqlWhe, $pOrden = '', $pLimitSql = '') {
        $strCadSql  = 'SELECT *';
        $strCadSql .= " FROM ".$this->tableName;
        if ($pTipo == 'PK') {
            $strCadSql .= ' WHERE IDLOG = '.$this->idlog;
        } else {
            if ($pTipo == 'WHE') {
                if ($pSqlWhe <> '') { $strCadSql .= ' WHERE '.$pSqlWhe; }
            }
        }
        if ($pOrden <> '') {
            $strCadSql .= ' ORDER BY '.$pOrden;
        } else {
            $strCadSql .= ' ORDER BY 1';
        }
        if ($pLimitSql <> '') {  $strCadSql .= ' '.$pLimitSql;  }
        return $strCadSql;
    }

// FUNCION PARA GUARDAR EN LA BASE DE DATOS
    public function guardar(PDO $pConexion) {
        $resultado    = true;
        $oConsulta    = null;
        $bolModificar = true;
        if (!$this->buscarPorId($pConexion)) { $bolModificar = false; }

        if ($bolModificar) {
            $lSql = $this->sqlUpdate();
        } else {
            $lSql = $this->sqlInsert();
        }
        $this->lastSqlExecuted($lSql);
        $oConsulta = $pConexion->prepare($lSql);
        $oConsulta->execute();
        $aErr = $oConsulta->errorInfo();
        if ($aErr[2] <> '') {
            $this->pintarScreen('ERROR', $aErr[2].' (SQL: '.$lSql.' )');
            $resultado = false;
        }
        if ($this->depurarObjeto) {
            $log = new KLogger(App::getInstancia()->getRutaLog(), KLogger::DEBUG);
            $log->LogDebug(__METHOD__.": {$this->lastSqlExecuted}");
        }

        return $resultado;
    }

// FUNCION PARA ELIMINAR UN REGISTRO EN LA BASE DE DATOS
    public function eliminar(PDO $pConexion) {
        $resultado = true;
        $lSql = $this->sqlDelete();
        $this->lastSqlExecuted($lSql);
        $oConsulta = $pConexion->prepare($lSql);
        $oConsulta->execute();
        $aErr = $oConsulta->errorInfo();
        if ($aErr[2] <> '') {
            $this->pintarScreen('ERROR', $aErr[2].' (SQL: '.$lSql.' )');
            $resultado = false;
        }
        if ($this->depurarObjeto) {
            $log = new KLogger(App::getInstancia()->getRutaLog(), KLogger::DEBUG);
            $log->LogDebug(__METHOD__.": {$this->lastSqlExecuted}");
        }
        $this->limpiar();

        return $resultado;
    }

// FUNCIONES DE MUESTRA DE DATOS
    public function verContenido() {
        echo 'IDLOG: => '.$this->idlog.'<br>';
        echo 'CDCLIEN: => '.$this->cdclien.'<br>';
        echo 'CDSUCUR: => '.$this->cdsucur.'<br>';
        echo 'EMPRESA: => '.$this->empresa.'<br>';
        echo 'SECCION: => '.$this->seccion.'<br>';
        echo 'DES: => '.$this->des.'<br>';
        echo 'FEX: => '.$this->fex.'<br>';
        echo 'DIR_IP: => '.$this->dirIp.'<br>';
        echo 'USER_MAG: => '.$this->userMag.'<br>';
    }

// FUNCIONES VERIFICAR SI EXISTE UN VALOR
    public function buscarPorId(PDO $pConexion) {
        $lSql = $this->sqlSelect('PK', '', '', '');
        $this->lastSqlExecuted($lSql);
        $oConsulta = $pConexion->prepare($lSql);
        $oConsulta->execute();
        $aErr = $oConsulta->errorInfo();
        if ($aErr[2] <> '') { $this->pintarScreen('ERROR', $aErr[2].' (SQL: '.$lSql.' )'); }
        if ($this->depurarObjeto) {
            $log = new KLogger(App::getInstancia()->getRutaLog(), KLogger::DEBUG);
            $log->LogDebug(__METHOD__.": {$this->lastSqlExecuted}");
        }
        $registro = $oConsulta->fetch();
        if ($registro) {
            return true;
        } else {
            return false;
        }
    }

// FUNCIONES OBTENER EL CONTENIDO DE UN VALOR
    public function obtenerPorId(PDO $pConexion) {
        $lSql = $this->sqlSelect('PK', '', '', '');
        $this->lastSqlExecuted($lSql);
        $oConsulta = $pConexion->prepare($lSql);
        $oConsulta->execute();
        $aErr = $oConsulta->errorInfo();
        if ($aErr[2] <> '') { $this->pintarScreen('ERROR', $aErr[2].' (SQL: '.$lSql.' )'); }
        if ($this->depurarObjeto) {
            $log = new KLogger(App::getInstancia()->getRutaLog(), KLogger::DEBUG);
            $log->LogDebug(__METHOD__.": {$this->lastSqlExecuted}");
        }
        $registro = $oConsulta->fetch();
        if ($registro) {
            $this->asignarValores($registro);
            return true;
        } else {
            return false;
        }
    }

// FUNCIONES OBTENER REGISTROS
    public function obtenerRegistros(PDO $pConexion, $pWhere, $pOrden = '', $pLimitSql = '') {
        $lSql = $this->sqlSelect('WHE',  $pWhere, $pOrden, $pLimitSql);
        $this->lastSqlExecuted($lSql);
        $oConsulta = $pConexion->prepare($lSql);
        $oConsulta->execute();
        $aErr = $oConsulta->errorInfo();
        if ($aErr[2] <> '') {
            $this->pintarScreen('ERROR', $aErr[2].' (SQL: '.$lSql.' )');
            $this->log->LogError($lSql);
            $this->log->LogError(Helper::generateCallTrace());
        }
        if ($this->depurarObjeto) {
            $log = new KLogger(App::getInstancia()->getRutaLog(), KLogger::DEBUG);
            $log->LogDebug(__METHOD__.": {$this->lastSqlExecuted}");
        }
        $aRegs = $oConsulta->fetchAll(PDO::FETCH_ASSOC);
        return($aRegs);
    }

// FUNCION OBTENER COLECCION: Devuelve un array de objetos
    public function obtenerColeccion(PDO $pConexion, $pWhere, $pOrden = '', $pLimitSql = '') {
        $lSql = $this->sqlSelect('WHE',  $pWhere, $pOrden, $pLimitSql);
        $this->lastSqlExecuted($lSql);
        $oConsulta = $pConexion->prepare($lSql);
        $oConsulta->execute();
        $aErr = $oConsulta->errorInfo();
        if ($aErr[2] <> '') {
            $this->pintarScreen('ERROR', $aErr[2].' (SQL: '.$lSql.' )');
            $this->log->LogError($lSql);
            $this->log->LogError(Helper::generateCallTrace());
        }
        if ($this->depurarObjeto) {
            $log = new KLogger(App::getInstancia()->getRutaLog(), KLogger::DEBUG);
            $log->LogDebug(__METHOD__.": {$this->lastSqlExecuted}");
        }
        $aRegs = $oConsulta->fetchAll(PDO::FETCH_ASSOC);
        $aObjetos = [];
        foreach ($aRegs as $aReg) {
            $aObjetos[] = new TableWebAdmLog($aReg);
        }
        return($aObjetos);
    }

// ASIGNAR VALORES DE UN ARRAY AL OBJETO
    public function asignarValores($paArray) {
        $this->limpiar();
        $this->idlog = $paArray ['IDLOG'];
        $this->cdclien = $paArray ['CDCLIEN'];
        $this->cdsucur = $paArray ['CDSUCUR'];
        $this->empresa = $paArray ['EMPRESA'];
        $this->seccion = $paArray ['SECCION'];
        $this->des = $paArray ['DES'];
        $this->fex = $paArray ['FEX'];
        $this->dirIp = $paArray ['DIR_IP'];
        $this->userMag = $paArray ['USER_MAG'];
    }

// DEVOLVER LA CLAVE DE LA TABLA
    public function devolverClave($pTipDev) {
        $strDevolver = '';
        if ($pTipDev == 'NOM_CAMPO') {
            $strDevolver = 'IDLOG';
        } else {
            if ($pTipDev == 'VALOR') {
                $strDevolver = $this->idlog;
            }
        }
        return $strDevolver;
    }

// PINTA UN TEXTO EN EL SCREEN
    protected function pintarScreen($pTipo, $pMensaje) {
        if ($pTipo == 'ERROR') { echo '<span style="font-weight:bold; color:red;">(ERROR) '.$pMensaje.' </span><BR>'; }
        if ($pTipo == 'DEBUG') { echo '<span style="font-weight:bold; color:blue;">(DBG) '.$pMensaje.' </span><BR>'; }
    }

// OBTENER ARRAY ASOCIATIVO A PARTIR DE UN OBJETO
    public function toArray() {
        return get_object_vars($this);
    }

}