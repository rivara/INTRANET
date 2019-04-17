<?php
/*
1.0         01/01/2016  Creacion
1.1         01/08/2016  Normalizacion de nombre de fichero y nombre de campos
1.2         19/11/2016  Se añade parametro lastSqlExecuted, para depuraciones
1.3         29/12/2016  Se añade parametros tableName y depurarObjeto para depuraciones. Se saca el pintar en pantalla a funcion para unificar.
1.4         14/11/2017  Funcion obtenerColeccion.

Nombre de la tabla         : web_socios_cart
Clave principal            : PEDIDO + N_LINEA
Fecha creacion del fichero : 06/12/2018
*/



class TableWebSociosCart {

// CAMPOS
	public $pedido;
	public $cdclien;
	public $cdsucur;
	public $fecha;
	public $pedidoCli;
	public $tipo;
	public $cdcampa;
	public $nLinea;
	public $cdarti;
	public $descrip;
	public $cantidad;
	public $um;
	public $indUrgen;
	public $estado;
	public $fex;
	public $indResto;
	public $almacen;
	public $indAdaia;

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
	public function pedido($val = false) {
		if ($val !== false) {
			$this->pedido= $val;
		}

		return $this->pedido;
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

	public function fecha($val = false) {
		if ($val !== false) {
			$this->fecha= $val;
		}

		return $this->fecha;
	}

	public function pedidoCli($val = false) {
		if ($val !== false) {
			$this->pedidoCli= $val;
		}

		return $this->pedidoCli;
	}

	public function tipo($val = false) {
		if ($val !== false) {
			$this->tipo= $val;
		}

		return $this->tipo;
	}

	public function cdcampa($val = false) {
		if ($val !== false) {
			$this->cdcampa= $val;
		}

		return $this->cdcampa;
	}

	public function nLinea($val = false) {
		if ($val !== false) {
			$this->nLinea= $val;
		}

		return $this->nLinea;
	}

	public function cdarti($val = false) {
		if ($val !== false) {
			$this->cdarti= $val;
		}

		return $this->cdarti;
	}

	public function descrip($val = false) {
		if ($val !== false) {
			$this->descrip= $val;
		}

		return $this->descrip;
	}

	public function cantidad($val = false) {
		if ($val !== false) {
			$this->cantidad= $val;
		}

		return $this->cantidad;
	}

	public function um($val = false) {
		if ($val !== false) {
			$this->um= $val;
		}

		return $this->um;
	}

	public function indUrgen($val = false) {
		if ($val !== false) {
			$this->indUrgen= $val;
		}

		return $this->indUrgen;
	}

	public function estado($val = false) {
		if ($val !== false) {
			$this->estado= $val;
		}

		return $this->estado;
	}

	public function fex($val = false) {
		if ($val !== false) {
			$this->fex= $val;
		}

		return $this->fex;
	}

	public function indResto($val = false) {
		if ($val !== false) {
			$this->indResto= $val;
		}

		return $this->indResto;
	}

	public function almacen($val = false) {
		if ($val !== false) {
			$this->almacen= $val;
		}

		return $this->almacen;
	}

	public function indAdaia($val = false) {
		if ($val !== false) {
			$this->indAdaia= $val;
		}

		return $this->indAdaia;
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
		$this->log = new KLogger($rutaLog, KLogger::DEBUG);
		$this->pedido(isset($params['PEDIDO'])? $params['PEDIDO']: false);
		$this->cdclien(isset($params['CDCLIEN'])? $params['CDCLIEN']: 0);
		$this->cdsucur(isset($params['CDSUCUR'])? $params['CDSUCUR']: 0);
		$this->fecha(isset($params['FECHA'])? $params['FECHA']: 'now()');
		$this->pedidoCli(isset($params['PEDIDO_CLI'])? $params['PEDIDO_CLI']: false);
		$this->tipo(isset($params['TIPO'])? $params['TIPO']: false);
		$this->cdcampa(isset($params['CDCAMPA'])? $params['CDCAMPA']: false);
		$this->nLinea(isset($params['N_LINEA'])? $params['N_LINEA']: false);
		$this->cdarti(isset($params['CDARTI'])? $params['CDARTI']: 0);
		$this->descrip(isset($params['DESCRIP'])? $params['DESCRIP']: false);
		$this->cantidad(isset($params['CANTIDAD'])? $params['CANTIDAD']: 0);
		$this->um(isset($params['UM'])? $params['UM']: false);
		$this->indUrgen(isset($params['IND_URGEN'])? $params['IND_URGEN']: 0);
		$this->estado(isset($params['ESTADO'])? $params['ESTADO']: false);
		$this->fex(isset($params['FEX'])? $params['FEX']: 'now()');
		$this->indResto(isset($params['IND_RESTO'])? $params['IND_RESTO']: 0);
		$this->almacen(isset($params['ALMACEN'])? $params['ALMACEN']: false);
		$this->indAdaia(isset($params['IND_ADAIA'])? $params['IND_ADAIA']: 0);
		$this->tableName = 'web_socios_cart';
		//Codigo propio
		//trim($_SERVER['REMOTE_ADDR']);
	}

// VACIAR DATOS OBJETO
	public function limpiar() {
		$this->pedido = NULL;
		$this->cdclien = 0;
		$this->cdsucur = 0;
		$this->fecha = 'now()';
		$this->pedidoCli = NULL;
		$this->tipo = NULL;
		$this->cdcampa = NULL;
		$this->nLinea = NULL;
		$this->cdarti = 0;
		$this->descrip = NULL;
		$this->cantidad = 0;
		$this->um = NULL;
		$this->indUrgen = 0;
		$this->estado = NULL;
		$this->fex = 'now()';
		$this->indResto = 0;
		$this->almacen = NULL;
		$this->indAdaia = 0;
	}

// FUNCIONES SQLs EDICION DATOS
	public function sqlInsert() {
		$strCadSql  = 'INSERT INTO '.$this->tableName.' (';
		$strCadSql .= 'PEDIDO,CDCLIEN,CDSUCUR,FECHA,PEDIDO_CLI,TIPO,CDCAMPA,N_LINEA,CDARTI,DESCRIP,CANTIDAD,UM,IND_URGEN,ESTADO,FEX,IND_RESTO,ALMACEN,IND_ADAIA';
		$strCadSql .= ') VALUES (';
		$strCadSql .= "'".$this->pedido."'";
		$strCadSql .= ','.$this->cdclien;
		$strCadSql .= ','.$this->cdsucur;
		if (strtoupper(str_replace(' ','',$this->fecha)) == 'NOW()') { 
			$strCadSql .= ','.$this->fecha;
		} else {
			if ($this->fecha == NULL) {
				$strCadSql .= ', NULL';
			} else {
				$strCadSql .= ",'".$this->fecha."'";
			}
		}
		$strCadSql .= ','."'".$this->pedidoCli."'";
		$strCadSql .= ','."'".$this->tipo."'";
		$strCadSql .= ','."'".$this->cdcampa."'";
		$strCadSql .= ','."'".$this->nLinea."'";
		$strCadSql .= ','.$this->cdarti;
		$strCadSql .= ','."'".$this->descrip."'";
		$strCadSql .= ','.$this->cantidad;
		$strCadSql .= ','."'".$this->um."'";
		$strCadSql .= ','.$this->indUrgen;
		$strCadSql .= ','."'".$this->estado."'";
		if (strtoupper(str_replace(' ','',$this->fex)) == 'NOW()') { 
			$strCadSql .= ','.$this->fex;
		} else {
			if ($this->fex == NULL) {
				$strCadSql .= ', NULL';
			} else {
				$strCadSql .= ",'".$this->fex."'";
			}
		}
		$strCadSql .= ','.$this->indResto;
		$strCadSql .= ','."'".$this->almacen."'";
		$strCadSql .= ','.$this->indAdaia;
		$strCadSql .= ')';
		return $strCadSql;
	}

	public function sqlUpdate() {
		$strCadSql  = 'UPDATE '.$this->tableName.' SET ';
		$strCadSql .= 'CDCLIEN = '.$this->cdclien;
		$strCadSql .= ',CDSUCUR = '.$this->cdsucur;
		if (strtoupper(str_replace(' ','',$this->fecha)) == 'NOW()') { 
			$strCadSql .= ',FECHA = '.$this->fecha;
		} else {
			if ($this->fecha == NULL) {
				$strCadSql .= ', FECHA = NULL';
			} else {
				$strCadSql .= ', FECHA = '."'".$this->fecha."'";
			}
		}
		$strCadSql .= ',PEDIDO_CLI = '."'".$this->pedidoCli."'";
		$strCadSql .= ',TIPO = '."'".$this->tipo."'";
		$strCadSql .= ',CDCAMPA = '."'".$this->cdcampa."'";
		$strCadSql .= ',CDARTI = '.$this->cdarti;
		$strCadSql .= ',DESCRIP = '."'".$this->descrip."'";
		$strCadSql .= ',CANTIDAD = '.$this->cantidad;
		$strCadSql .= ',UM = '."'".$this->um."'";
		$strCadSql .= ',IND_URGEN = '.$this->indUrgen;
		$strCadSql .= ',ESTADO = '."'".$this->estado."'";
		if (strtoupper(str_replace(' ','',$this->fex)) == 'NOW()') { 
			$strCadSql .= ',FEX = '.$this->fex;
		} else {
			if ($this->fex == NULL) {
				$strCadSql .= ', FEX = NULL';
			} else {
				$strCadSql .= ', FEX = '."'".$this->fex."'";
			}
		}
		$strCadSql .= ',IND_RESTO = '.$this->indResto;
		$strCadSql .= ',ALMACEN = '."'".$this->almacen."'";
		$strCadSql .= ',IND_ADAIA = '.$this->indAdaia;
		$strCadSql .= ' WHERE PEDIDO = '.'\''.$this->pedido.'\'';
		$strCadSql .= ' AND N_LINEA = '.'\''.$this->nLinea.'\'';
		return $strCadSql;
	}

	public function sqlDelete() {
		$strCadSql  = 'DELETE FROM '.$this->tableName;
		$strCadSql .= ' WHERE PEDIDO = '.'\''.$this->pedido.'\'';
		$strCadSql .= ' AND N_LINEA = '.'\''.$this->nLinea.'\'';
		return $strCadSql;
	}

// FUNCIONES ACCESO A DATOS
	public function sqlSelect($pTipo, $pSqlWhe, $pOrden = '', $pLimitSql = '') {
		$strCadSql  = 'SELECT *';
		$strCadSql .= " FROM ".$this->tableName;
		if ($pTipo == 'PK') {
			$strCadSql .= ' WHERE PEDIDO = '.'\''.$this->pedido.'\'';
			$strCadSql .= ' AND N_LINEA = '.'\''.$this->nLinea.'\'';
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
		echo 'PEDIDO: => '.$this->pedido.'<br>';
		echo 'CDCLIEN: => '.$this->cdclien.'<br>';
		echo 'CDSUCUR: => '.$this->cdsucur.'<br>';
		echo 'FECHA: => '.$this->fecha.'<br>';
		echo 'PEDIDO_CLI: => '.$this->pedidoCli.'<br>';
		echo 'TIPO: => '.$this->tipo.'<br>';
		echo 'CDCAMPA: => '.$this->cdcampa.'<br>';
		echo 'N_LINEA: => '.$this->nLinea.'<br>';
		echo 'CDARTI: => '.$this->cdarti.'<br>';
		echo 'DESCRIP: => '.$this->descrip.'<br>';
		echo 'CANTIDAD: => '.$this->cantidad.'<br>';
		echo 'UM: => '.$this->um.'<br>';
		echo 'IND_URGEN: => '.$this->indUrgen.'<br>';
		echo 'ESTADO: => '.$this->estado.'<br>';
		echo 'FEX: => '.$this->fex.'<br>';
		echo 'IND_RESTO: => '.$this->indResto.'<br>';
		echo 'ALMACEN: => '.$this->almacen.'<br>';
		echo 'IND_ADAIA: => '.$this->indAdaia.'<br>';
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
			$aObjetos[] = new TableWebSociosCart($aReg);
		}
		return($aObjetos);
	}

// ASIGNAR VALORES DE UN ARRAY AL OBJETO
	public function asignarValores($paArray) {
		$this->limpiar();
		$this->pedido = $paArray ['PEDIDO'];
		$this->cdclien = $paArray ['CDCLIEN'];
		$this->cdsucur = $paArray ['CDSUCUR'];
		$this->fecha = $paArray ['FECHA'];
		$this->pedidoCli = $paArray ['PEDIDO_CLI'];
		$this->tipo = $paArray ['TIPO'];
		$this->cdcampa = $paArray ['CDCAMPA'];
		$this->nLinea = $paArray ['N_LINEA'];
		$this->cdarti = $paArray ['CDARTI'];
		$this->descrip = $paArray ['DESCRIP'];
		$this->cantidad = $paArray ['CANTIDAD'];
		$this->um = $paArray ['UM'];
		$this->indUrgen = $paArray ['IND_URGEN'];
		$this->estado = $paArray ['ESTADO'];
		$this->fex = $paArray ['FEX'];
		$this->indResto = $paArray ['IND_RESTO'];
		$this->almacen = $paArray ['ALMACEN'];
		$this->indAdaia = $paArray ['IND_ADAIA'];
	}

// DEVOLVER LA CLAVE DE LA TABLA
	public function devolverClave($pTipDev) {
		$strDevolver = '';
		if ($pTipDev == 'NOM_CAMPO') {
			$strDevolver = 'N_LINEA';
		} else {
			if ($pTipDev == 'VALOR') {
				$strDevolver = $this->nLinea;
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
