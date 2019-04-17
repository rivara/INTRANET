<?php
/*
1.0         01/01/2016  Creacion
1.1         01/08/2016  Normalizacion de nombre de fichero y nombre de campos
1.2         19/11/2016  Se añade parametro lastSqlExecuted, para depuraciones
1.3         29/12/2016  Se añade parametros tableName y depurarObjeto para depuraciones. Se saca el pintar en pantalla a funcion para unificar.
1.4         14/11/2017  Funcion obtenerColeccion.

Nombre de la tabla         : web_clientes
Clave principal            : CDCLIEN + CDSUCUR
Fecha creacion del fichero : 06/12/2018
*/

if (!defined('CONTROLADOR'))
	exit;

require_once 'Conexion.php';

class TableWebClientes {

// CAMPOS
	public $cdclien;
	public $cdsucur;
	public $nombre;
	public $nif;
	public $domicilio;
	public $localidad;
	public $telefono;
	public $fax;
	public $email;
	public $clave;
	public $compraSoc;
	public $compraSuc;
	public $indAdmTie;
	public $indAct;
	public $indDir;
	public $indFrky;
	public $indCartera;
	public $ultEntrada;
	public $riesgoDisp;
	public $riesgoDispLim;
	public $fecActRie;
	public $fex;
	public $indCompInd;
	public $indMovVped;
	public $indMovValb;
	public $indMovVdir;
	public $indMovVfac;
	public $indMovVpag;
	public $emailConf;
	public $tipoPrecio;
	public $indUsuWeb;
	public $indSeccTer;
	public $emailCpPed;
	public $indListaDir;
	public $formaPago;
	public $indTablon;
	public $emailAvisoTab;
	public $avisoDir;
	public $almStock;
	public $indSoloTienda;
	public $almacen;
	public $nccCodigo;
	public $nccClave;
	public $socioAnt;
	public $emailFacturas;
	public $tipNotConfor;
	public $codPostal;
	public $indExcRie;
	public $tipoCliente;
	public $tipoRiesgo;
	public $indMovVrap;
	public $indPanCdire;
	public $indPanPedf;
	public $tipoEnvio;
	public $indTieFrky;
	public $feaFky;
	public $indAudRiesgo;
	public $puntoOperacional;
	public $indFkySoloComMerch;
	public $provincia;
	public $pais;
	public $indicadorProntoPagoDirecto;
	public $formaPagoDirecto;
	public $codigoResponsableComercial;
	public $formatoFacturaDocumento;

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

	public function nombre($val = false) {
		if ($val !== false) {
			$this->nombre= $val;
		}

		return $this->nombre;
	}

	public function nif($val = false) {
		if ($val !== false) {
			$this->nif= $val;
		}

		return $this->nif;
	}

	public function domicilio($val = false) {
		if ($val !== false) {
			$this->domicilio= $val;
		}

		return $this->domicilio;
	}

	public function localidad($val = false) {
		if ($val !== false) {
			$this->localidad= $val;
		}

		return $this->localidad;
	}

	public function telefono($val = false) {
		if ($val !== false) {
			$this->telefono= $val;
		}

		return $this->telefono;
	}

	public function fax($val = false) {
		if ($val !== false) {
			$this->fax= $val;
		}

		return $this->fax;
	}

	public function email($val = false) {
		if ($val !== false) {
			$this->email= $val;
		}

		return $this->email;
	}

	public function clave($val = false) {
		if ($val !== false) {
			$this->clave= $val;
		}

		return $this->clave;
	}

	public function compraSoc($val = false) {
		if ($val !== false) {
			$this->compraSoc= $val;
		}

		return $this->compraSoc;
	}

	public function compraSuc($val = false) {
		if ($val !== false) {
			$this->compraSuc= $val;
		}

		return $this->compraSuc;
	}

	public function indAdmTie($val = false) {
		if ($val !== false) {
			$this->indAdmTie= $val;
		}

		return $this->indAdmTie;
	}

	public function indAct($val = false) {
		if ($val !== false) {
			$this->indAct= $val;
		}

		return $this->indAct;
	}

	public function indDir($val = false) {
		if ($val !== false) {
			$this->indDir= $val;
		}

		return $this->indDir;
	}

	public function indFrky($val = false) {
		if ($val !== false) {
			$this->indFrky= $val;
		}

		return $this->indFrky;
	}

	public function indCartera($val = false) {
		if ($val !== false) {
			$this->indCartera= $val;
		}

		return $this->indCartera;
	}

	public function ultEntrada($val = false) {
		if ($val !== false) {
			$this->ultEntrada= $val;
		}

		return $this->ultEntrada;
	}

	public function riesgoDisp($val = false) {
		if ($val !== false) {
			$this->riesgoDisp= $val;
		}

		return $this->riesgoDisp;
	}

	public function riesgoDispLim($val = false) {
		if ($val !== false) {
			$this->riesgoDispLim= $val;
		}

		return $this->riesgoDispLim;
	}

	public function fecActRie($val = false) {
		if ($val !== false) {
			$this->fecActRie= $val;
		}

		return $this->fecActRie;
	}

	public function fex($val = false) {
		if ($val !== false) {
			$this->fex= $val;
		}

		return $this->fex;
	}

	public function indCompInd($val = false) {
		if ($val !== false) {
			$this->indCompInd= $val;
		}

		return $this->indCompInd;
	}

	public function indMovVped($val = false) {
		if ($val !== false) {
			$this->indMovVped= $val;
		}

		return $this->indMovVped;
	}

	public function indMovValb($val = false) {
		if ($val !== false) {
			$this->indMovValb= $val;
		}

		return $this->indMovValb;
	}

	public function indMovVdir($val = false) {
		if ($val !== false) {
			$this->indMovVdir= $val;
		}

		return $this->indMovVdir;
	}

	public function indMovVfac($val = false) {
		if ($val !== false) {
			$this->indMovVfac= $val;
		}

		return $this->indMovVfac;
	}

	public function indMovVpag($val = false) {
		if ($val !== false) {
			$this->indMovVpag= $val;
		}

		return $this->indMovVpag;
	}

	public function emailConf($val = false) {
		if ($val !== false) {
			$this->emailConf= $val;
		}

		return $this->emailConf;
	}

	public function tipoPrecio($val = false) {
		if ($val !== false) {
			$this->tipoPrecio= $val;
		}

		return $this->tipoPrecio;
	}

	public function indUsuWeb($val = false) {
		if ($val !== false) {
			$this->indUsuWeb= $val;
		}

		return $this->indUsuWeb;
	}

	public function indSeccTer($val = false) {
		if ($val !== false) {
			$this->indSeccTer= $val;
		}

		return $this->indSeccTer;
	}

	public function emailCpPed($val = false) {
		if ($val !== false) {
			$this->emailCpPed= $val;
		}

		return $this->emailCpPed;
	}

	public function indListaDir($val = false) {
		if ($val !== false) {
			$this->indListaDir= $val;
		}

		return $this->indListaDir;
	}

	public function formaPago($val = false) {
		if ($val !== false) {
			$this->formaPago= $val;
		}

		return $this->formaPago;
	}

	public function indTablon($val = false) {
		if ($val !== false) {
			$this->indTablon= $val;
		}

		return $this->indTablon;
	}

	public function emailAvisoTab($val = false) {
		if ($val !== false) {
			$this->emailAvisoTab= $val;
		}

		return $this->emailAvisoTab;
	}

	public function avisoDir($val = false) {
		if ($val !== false) {
			$this->avisoDir= $val;
		}

		return $this->avisoDir;
	}

	public function almStock($val = false) {
		if ($val !== false) {
			$this->almStock= $val;
		}

		return $this->almStock;
	}

	public function indSoloTienda($val = false) {
		if ($val !== false) {
			$this->indSoloTienda= $val;
		}

		return $this->indSoloTienda;
	}

	public function almacen($val = false) {
		if ($val !== false) {
			$this->almacen= $val;
		}

		return $this->almacen;
	}

	public function nccCodigo($val = false) {
		if ($val !== false) {
			$this->nccCodigo= $val;
		}

		return $this->nccCodigo;
	}

	public function nccClave($val = false) {
		if ($val !== false) {
			$this->nccClave= $val;
		}

		return $this->nccClave;
	}

	public function socioAnt($val = false) {
		if ($val !== false) {
			$this->socioAnt= $val;
		}

		return $this->socioAnt;
	}

	public function emailFacturas($val = false) {
		if ($val !== false) {
			$this->emailFacturas= $val;
		}

		return $this->emailFacturas;
	}

	public function tipNotConfor($val = false) {
		if ($val !== false) {
			$this->tipNotConfor= $val;
		}

		return $this->tipNotConfor;
	}

	public function codPostal($val = false) {
		if ($val !== false) {
			$this->codPostal= $val;
		}

		return $this->codPostal;
	}

	public function indExcRie($val = false) {
		if ($val !== false) {
			$this->indExcRie= $val;
		}

		return $this->indExcRie;
	}

	public function tipoCliente($val = false) {
		if ($val !== false) {
			$this->tipoCliente= $val;
		}

		return $this->tipoCliente;
	}

	public function tipoRiesgo($val = false) {
		if ($val !== false) {
			$this->tipoRiesgo= $val;
		}

		return $this->tipoRiesgo;
	}

	public function indMovVrap($val = false) {
		if ($val !== false) {
			$this->indMovVrap= $val;
		}

		return $this->indMovVrap;
	}

	public function indPanCdire($val = false) {
		if ($val !== false) {
			$this->indPanCdire= $val;
		}

		return $this->indPanCdire;
	}

	public function indPanPedf($val = false) {
		if ($val !== false) {
			$this->indPanPedf= $val;
		}

		return $this->indPanPedf;
	}

	public function tipoEnvio($val = false) {
		if ($val !== false) {
			$this->tipoEnvio= $val;
		}

		return $this->tipoEnvio;
	}

	public function indTieFrky($val = false) {
		if ($val !== false) {
			$this->indTieFrky= $val;
		}

		return $this->indTieFrky;
	}

	public function feaFky($val = false) {
		if ($val !== false) {
			$this->feaFky= $val;
		}

		return $this->feaFky;
	}

	public function indAudRiesgo($val = false) {
		if ($val !== false) {
			$this->indAudRiesgo= $val;
		}

		return $this->indAudRiesgo;
	}

	public function puntoOperacional($val = false) {
		if ($val !== false) {
			$this->puntoOperacional= $val;
		}

		return $this->puntoOperacional;
	}

	public function indFkySoloComMerch($val = false) {
		if ($val !== false) {
			$this->indFkySoloComMerch= $val;
		}

		return $this->indFkySoloComMerch;
	}

	public function provincia($val = false) {
		if ($val !== false) {
			$this->provincia= $val;
		}

		return $this->provincia;
	}

	public function pais($val = false) {
		if ($val !== false) {
			$this->pais= $val;
		}

		return $this->pais;
	}

	public function indicadorProntoPagoDirecto($val = false) {
		if ($val !== false) {
			$this->indicadorProntoPagoDirecto= $val;
		}

		return $this->indicadorProntoPagoDirecto;
	}

	public function formaPagoDirecto($val = false) {
		if ($val !== false) {
			$this->formaPagoDirecto= $val;
		}

		return $this->formaPagoDirecto;
	}

	public function codigoResponsableComercial($val = false) {
		if ($val !== false) {
			$this->codigoResponsableComercial= $val;
		}

		return $this->codigoResponsableComercial;
	}

	public function formatoFacturaDocumento($val = false) {
		if ($val !== false) {
			$this->formatoFacturaDocumento= $val;
		}

		return $this->formatoFacturaDocumento;
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
		$this->cdclien(isset($params['CDCLIEN'])? $params['CDCLIEN']: 0);
		$this->cdsucur(isset($params['CDSUCUR'])? $params['CDSUCUR']: 0);
		$this->nombre(isset($params['NOMBRE'])? $params['NOMBRE']: false);
		$this->nif(isset($params['NIF'])? $params['NIF']: false);
		$this->domicilio(isset($params['DOMICILIO'])? $params['DOMICILIO']: false);
		$this->localidad(isset($params['LOCALIDAD'])? $params['LOCALIDAD']: false);
		$this->telefono(isset($params['TELEFONO'])? $params['TELEFONO']: false);
		$this->fax(isset($params['FAX'])? $params['FAX']: false);
		$this->email(isset($params['EMAIL'])? $params['EMAIL']: false);
		$this->clave(isset($params['CLAVE'])? $params['CLAVE']: false);
		$this->compraSoc(isset($params['COMPRA_SOC'])? $params['COMPRA_SOC']: 0);
		$this->compraSuc(isset($params['COMPRA_SUC'])? $params['COMPRA_SUC']: 0);
		$this->indAdmTie(isset($params['IND_ADM_TIE'])? $params['IND_ADM_TIE']: 0);
		$this->indAct(isset($params['IND_ACT'])? $params['IND_ACT']: 0);
		$this->indDir(isset($params['IND_DIR'])? $params['IND_DIR']: 0);
		$this->indFrky(isset($params['IND_FRKY'])? $params['IND_FRKY']: 0);
		$this->indCartera(isset($params['IND_CARTERA'])? $params['IND_CARTERA']: 0);
		$this->ultEntrada(isset($params['ULT_ENTRADA'])? $params['ULT_ENTRADA']: 'now()');
		$this->riesgoDisp(isset($params['RIESGO_DISP'])? $params['RIESGO_DISP']: 0);
		$this->riesgoDispLim(isset($params['RIESGO_DISP_LIM'])? $params['RIESGO_DISP_LIM']: 0);
		$this->fecActRie(isset($params['FEC_ACT_RIE'])? $params['FEC_ACT_RIE']: 'now()');
		$this->fex(isset($params['FEX'])? $params['FEX']: 'now()');
		$this->indCompInd(isset($params['IND_COMP_IND'])? $params['IND_COMP_IND']: 0);
		$this->indMovVped(isset($params['IND_MOV_VPED'])? $params['IND_MOV_VPED']: 0);
		$this->indMovValb(isset($params['IND_MOV_VALB'])? $params['IND_MOV_VALB']: 0);
		$this->indMovVdir(isset($params['IND_MOV_VDIR'])? $params['IND_MOV_VDIR']: 0);
		$this->indMovVfac(isset($params['IND_MOV_VFAC'])? $params['IND_MOV_VFAC']: 0);
		$this->indMovVpag(isset($params['IND_MOV_VPAG'])? $params['IND_MOV_VPAG']: 0);
		$this->emailConf(isset($params['EMAIL_CONF'])? $params['EMAIL_CONF']: false);
		$this->tipoPrecio(isset($params['TIPO_PRECIO'])? $params['TIPO_PRECIO']: false);
		$this->indUsuWeb(isset($params['IND_USU_WEB'])? $params['IND_USU_WEB']: 0);
		$this->indSeccTer(isset($params['IND_SECC_TER'])? $params['IND_SECC_TER']: 0);
		$this->emailCpPed(isset($params['EMAIL_CP_PED'])? $params['EMAIL_CP_PED']: false);
		$this->indListaDir(isset($params['IND_LISTA_DIR'])? $params['IND_LISTA_DIR']: 0);
		$this->formaPago(isset($params['FORMA_PAGO'])? $params['FORMA_PAGO']: false);
		$this->indTablon(isset($params['IND_TABLON'])? $params['IND_TABLON']: 0);
		$this->emailAvisoTab(isset($params['EMAIL_AVISO_TAB'])? $params['EMAIL_AVISO_TAB']: false);
		$this->avisoDir(isset($params['AVISO_DIR'])? $params['AVISO_DIR']: false);
		$this->almStock(isset($params['ALM_STOCK'])? $params['ALM_STOCK']: false);
		$this->indSoloTienda(isset($params['IND_SOLO_TIENDA'])? $params['IND_SOLO_TIENDA']: 0);
		$this->almacen(isset($params['ALMACEN'])? $params['ALMACEN']: false);
		$this->nccCodigo(isset($params['NCC_CODIGO'])? $params['NCC_CODIGO']: false);
		$this->nccClave(isset($params['NCC_CLAVE'])? $params['NCC_CLAVE']: false);
		$this->socioAnt(isset($params['SOCIO_ANT'])? $params['SOCIO_ANT']: false);
		$this->emailFacturas(isset($params['EMAIL_FACTURAS'])? $params['EMAIL_FACTURAS']: false);
		$this->tipNotConfor(isset($params['TIP_NOT_CONFOR'])? $params['TIP_NOT_CONFOR']: false);
		$this->codPostal(isset($params['COD_POSTAL'])? $params['COD_POSTAL']: false);
		$this->indExcRie(isset($params['IND_EXC_RIE'])? $params['IND_EXC_RIE']: 0);
		$this->tipoCliente(isset($params['TIPO_CLIENTE'])? $params['TIPO_CLIENTE']: false);
		$this->tipoRiesgo(isset($params['TIPO_RIESGO'])? $params['TIPO_RIESGO']: false);
		$this->indMovVrap(isset($params['IND_MOV_VRAP'])? $params['IND_MOV_VRAP']: 0);
		$this->indPanCdire(isset($params['IND_PAN_CDIRE'])? $params['IND_PAN_CDIRE']: 0);
		$this->indPanPedf(isset($params['IND_PAN_PEDF'])? $params['IND_PAN_PEDF']: 0);
		$this->tipoEnvio(isset($params['TIPO_ENVIO'])? $params['TIPO_ENVIO']: false);
		$this->indTieFrky(isset($params['IND_TIE_FRKY'])? $params['IND_TIE_FRKY']: 0);
		$this->feaFky(isset($params['FEA_FKY'])? $params['FEA_FKY']: 'now()');
		$this->indAudRiesgo(isset($params['IND_AUD_RIESGO'])? $params['IND_AUD_RIESGO']: 0);
		$this->puntoOperacional(isset($params['PUNTO_OPERACIONAL'])? $params['PUNTO_OPERACIONAL']: false);
		$this->indFkySoloComMerch(isset($params['IND_FKY_SOLO_COM_MERCH'])? $params['IND_FKY_SOLO_COM_MERCH']: 0);
		$this->provincia(isset($params['PROVINCIA'])? $params['PROVINCIA']: false);
		$this->pais(isset($params['PAIS'])? $params['PAIS']: false);
		$this->indicadorProntoPagoDirecto(isset($params['INDICADOR_PRONTO_PAGO_DIRECTO'])? $params['INDICADOR_PRONTO_PAGO_DIRECTO']: 0);
		$this->formaPagoDirecto(isset($params['FORMA_PAGO_DIRECTO'])? $params['FORMA_PAGO_DIRECTO']: false);
		$this->codigoResponsableComercial(isset($params['CODIGO_RESPONSABLE_COMERCIAL'])? $params['CODIGO_RESPONSABLE_COMERCIAL']: false);
		$this->formatoFacturaDocumento(isset($params['FORMATO_FACTURA_DOCUMENTO'])? $params['FORMATO_FACTURA_DOCUMENTO']: false);
		$this->tableName = 'web_clientes';
		//Codigo propio
		//trim($_SERVER['REMOTE_ADDR']);
	}

// VACIAR DATOS OBJETO
	public function limpiar() {
		$this->cdclien = 0;
		$this->cdsucur = 0;
		$this->nombre = NULL;
		$this->nif = NULL;
		$this->domicilio = NULL;
		$this->localidad = NULL;
		$this->telefono = NULL;
		$this->fax = NULL;
		$this->email = NULL;
		$this->clave = NULL;
		$this->compraSoc = 0;
		$this->compraSuc = 0;
		$this->indAdmTie = 0;
		$this->indAct = 0;
		$this->indDir = 0;
		$this->indFrky = 0;
		$this->indCartera = 0;
		$this->ultEntrada = 'now()';
		$this->riesgoDisp = 0;
		$this->riesgoDispLim = 0;
		$this->fecActRie = 'now()';
		$this->fex = 'now()';
		$this->indCompInd = 0;
		$this->indMovVped = 0;
		$this->indMovValb = 0;
		$this->indMovVdir = 0;
		$this->indMovVfac = 0;
		$this->indMovVpag = 0;
		$this->emailConf = NULL;
		$this->tipoPrecio = NULL;
		$this->indUsuWeb = 0;
		$this->indSeccTer = 0;
		$this->emailCpPed = NULL;
		$this->indListaDir = 0;
		$this->formaPago = NULL;
		$this->indTablon = 0;
		$this->emailAvisoTab = NULL;
		$this->avisoDir = NULL;
		$this->almStock = NULL;
		$this->indSoloTienda = 0;
		$this->almacen = NULL;
		$this->nccCodigo = NULL;
		$this->nccClave = NULL;
		$this->socioAnt = NULL;
		$this->emailFacturas = NULL;
		$this->tipNotConfor = NULL;
		$this->codPostal = NULL;
		$this->indExcRie = 0;
		$this->tipoCliente = NULL;
		$this->tipoRiesgo = NULL;
		$this->indMovVrap = 0;
		$this->indPanCdire = 0;
		$this->indPanPedf = 0;
		$this->tipoEnvio = NULL;
		$this->indTieFrky = 0;
		$this->feaFky = 'now()';
		$this->indAudRiesgo = 0;
		$this->puntoOperacional = NULL;
		$this->indFkySoloComMerch = 0;
		$this->provincia = NULL;
		$this->pais = NULL;
		$this->indicadorProntoPagoDirecto = 0;
		$this->formaPagoDirecto = NULL;
		$this->codigoResponsableComercial = NULL;
		$this->formatoFacturaDocumento = NULL;
	}

// FUNCIONES SQLs EDICION DATOS
	public function sqlInsert() {
		$strCadSql  = 'INSERT INTO '.$this->tableName.' (';
		$strCadSql .= 'CDCLIEN,CDSUCUR,NOMBRE,NIF,DOMICILIO,LOCALIDAD,TELEFONO,FAX,EMAIL,CLAVE,COMPRA_SOC,COMPRA_SUC,IND_ADM_TIE,IND_ACT,IND_DIR,IND_FRKY,IND_CARTERA,ULT_ENTRADA,RIESGO_DISP,RIESGO_DISP_LIM,FEC_ACT_RIE,FEX,IND_COMP_IND,IND_MOV_VPED,IND_MOV_VALB,IND_MOV_VDIR,IND_MOV_VFAC,IND_MOV_VPAG,EMAIL_CONF,TIPO_PRECIO,IND_USU_WEB,IND_SECC_TER,EMAIL_CP_PED,IND_LISTA_DIR,FORMA_PAGO,IND_TABLON,EMAIL_AVISO_TAB,AVISO_DIR,ALM_STOCK,IND_SOLO_TIENDA,ALMACEN,NCC_CODIGO,NCC_CLAVE,SOCIO_ANT,EMAIL_FACTURAS,TIP_NOT_CONFOR,COD_POSTAL,IND_EXC_RIE,TIPO_CLIENTE,TIPO_RIESGO,IND_MOV_VRAP,IND_PAN_CDIRE,IND_PAN_PEDF,TIPO_ENVIO,IND_TIE_FRKY,FEA_FKY,IND_AUD_RIESGO,PUNTO_OPERACIONAL,IND_FKY_SOLO_COM_MERCH,PROVINCIA,PAIS,INDICADOR_PRONTO_PAGO_DIRECTO,FORMA_PAGO_DIRECTO,CODIGO_RESPONSABLE_COMERCIAL,FORMATO_FACTURA_DOCUMENTO';
		$strCadSql .= ') VALUES (';
		$strCadSql .= $this->cdclien;
		$strCadSql .= ','.$this->cdsucur;
		$strCadSql .= ','."'".$this->nombre."'";
		$strCadSql .= ','."'".$this->nif."'";
		$strCadSql .= ','."'".$this->domicilio."'";
		$strCadSql .= ','."'".$this->localidad."'";
		$strCadSql .= ','."'".$this->telefono."'";
		$strCadSql .= ','."'".$this->fax."'";
		$strCadSql .= ','."'".$this->email."'";
		$strCadSql .= ','."'".$this->clave."'";
		$strCadSql .= ','.$this->compraSoc;
		$strCadSql .= ','.$this->compraSuc;
		$strCadSql .= ','.$this->indAdmTie;
		$strCadSql .= ','.$this->indAct;
		$strCadSql .= ','.$this->indDir;
		$strCadSql .= ','.$this->indFrky;
		$strCadSql .= ','.$this->indCartera;
		if (strtoupper(str_replace(' ','',$this->ultEntrada)) == 'NOW()') { 
			$strCadSql .= ','.$this->ultEntrada;
		} else {
			if ($this->ultEntrada == NULL) {
				$strCadSql .= ', NULL';
			} else {
				$strCadSql .= ",'".$this->ultEntrada."'";
			}
		}
		$strCadSql .= ','.$this->riesgoDisp;
		$strCadSql .= ','.$this->riesgoDispLim;
		if (strtoupper(str_replace(' ','',$this->fecActRie)) == 'NOW()') { 
			$strCadSql .= ','.$this->fecActRie;
		} else {
			if ($this->fecActRie == NULL) {
				$strCadSql .= ', NULL';
			} else {
				$strCadSql .= ",'".$this->fecActRie."'";
			}
		}
		if (strtoupper(str_replace(' ','',$this->fex)) == 'NOW()') { 
			$strCadSql .= ','.$this->fex;
		} else {
			if ($this->fex == NULL) {
				$strCadSql .= ', NULL';
			} else {
				$strCadSql .= ",'".$this->fex."'";
			}
		}
		$strCadSql .= ','.$this->indCompInd;
		$strCadSql .= ','.$this->indMovVped;
		$strCadSql .= ','.$this->indMovValb;
		$strCadSql .= ','.$this->indMovVdir;
		$strCadSql .= ','.$this->indMovVfac;
		$strCadSql .= ','.$this->indMovVpag;
		$strCadSql .= ','."'".$this->emailConf."'";
		$strCadSql .= ','."'".$this->tipoPrecio."'";
		$strCadSql .= ','.$this->indUsuWeb;
		$strCadSql .= ','.$this->indSeccTer;
		$strCadSql .= ','."'".$this->emailCpPed."'";
		$strCadSql .= ','.$this->indListaDir;
		$strCadSql .= ','."'".$this->formaPago."'";
		$strCadSql .= ','.$this->indTablon;
		$strCadSql .= ','."'".$this->emailAvisoTab."'";
		$strCadSql .= ','."'".$this->avisoDir."'";
		$strCadSql .= ','."'".$this->almStock."'";
		$strCadSql .= ','.$this->indSoloTienda;
		$strCadSql .= ','."'".$this->almacen."'";
		$strCadSql .= ','."'".$this->nccCodigo."'";
		$strCadSql .= ','."'".$this->nccClave."'";
		$strCadSql .= ','."'".$this->socioAnt."'";
		$strCadSql .= ','."'".$this->emailFacturas."'";
		$strCadSql .= ','."'".$this->tipNotConfor."'";
		$strCadSql .= ','."'".$this->codPostal."'";
		$strCadSql .= ','.$this->indExcRie;
		$strCadSql .= ','."'".$this->tipoCliente."'";
		$strCadSql .= ','."'".$this->tipoRiesgo."'";
		$strCadSql .= ','.$this->indMovVrap;
		$strCadSql .= ','.$this->indPanCdire;
		$strCadSql .= ','.$this->indPanPedf;
		$strCadSql .= ','."'".$this->tipoEnvio."'";
		$strCadSql .= ','.$this->indTieFrky;
		if (strtoupper(str_replace(' ','',$this->feaFky)) == 'NOW()') { 
			$strCadSql .= ','.$this->feaFky;
		} else {
			if ($this->feaFky == NULL) {
				$strCadSql .= ', NULL';
			} else {
				$strCadSql .= ",'".$this->feaFky."'";
			}
		}
		$strCadSql .= ','.$this->indAudRiesgo;
		$strCadSql .= ','."'".$this->puntoOperacional."'";
		$strCadSql .= ','.$this->indFkySoloComMerch;
		$strCadSql .= ','."'".$this->provincia."'";
		$strCadSql .= ','."'".$this->pais."'";
		$strCadSql .= ','.$this->indicadorProntoPagoDirecto;
		$strCadSql .= ','."'".$this->formaPagoDirecto."'";
		$strCadSql .= ','."'".$this->codigoResponsableComercial."'";
		$strCadSql .= ','."'".$this->formatoFacturaDocumento."'";
		$strCadSql .= ')';
		return $strCadSql;
	}

	public function sqlUpdate() {
		$strCadSql  = 'UPDATE '.$this->tableName.' SET ';
		$strCadSql .= 'NOMBRE = '."'".$this->nombre."'";
		$strCadSql .= ',NIF = '."'".$this->nif."'";
		$strCadSql .= ',DOMICILIO = '."'".$this->domicilio."'";
		$strCadSql .= ',LOCALIDAD = '."'".$this->localidad."'";
		$strCadSql .= ',TELEFONO = '."'".$this->telefono."'";
		$strCadSql .= ',FAX = '."'".$this->fax."'";
		$strCadSql .= ',EMAIL = '."'".$this->email."'";
		$strCadSql .= ',CLAVE = '."'".$this->clave."'";
		$strCadSql .= ',COMPRA_SOC = '.$this->compraSoc;
		$strCadSql .= ',COMPRA_SUC = '.$this->compraSuc;
		$strCadSql .= ',IND_ADM_TIE = '.$this->indAdmTie;
		$strCadSql .= ',IND_ACT = '.$this->indAct;
		$strCadSql .= ',IND_DIR = '.$this->indDir;
		$strCadSql .= ',IND_FRKY = '.$this->indFrky;
		$strCadSql .= ',IND_CARTERA = '.$this->indCartera;
		if (strtoupper(str_replace(' ','',$this->ultEntrada)) == 'NOW()') { 
			$strCadSql .= ',ULT_ENTRADA = '.$this->ultEntrada;
		} else {
			if ($this->ultEntrada == NULL) {
				$strCadSql .= ', ULT_ENTRADA = NULL';
			} else {
				$strCadSql .= ', ULT_ENTRADA = '."'".$this->ultEntrada."'";
			}
		}
		$strCadSql .= ',RIESGO_DISP = '.$this->riesgoDisp;
		$strCadSql .= ',RIESGO_DISP_LIM = '.$this->riesgoDispLim;
		if (strtoupper(str_replace(' ','',$this->fecActRie)) == 'NOW()') { 
			$strCadSql .= ',FEC_ACT_RIE = '.$this->fecActRie;
		} else {
			if ($this->fecActRie == NULL) {
				$strCadSql .= ', FEC_ACT_RIE = NULL';
			} else {
				$strCadSql .= ', FEC_ACT_RIE = '."'".$this->fecActRie."'";
			}
		}
		if (strtoupper(str_replace(' ','',$this->fex)) == 'NOW()') { 
			$strCadSql .= ',FEX = '.$this->fex;
		} else {
			if ($this->fex == NULL) {
				$strCadSql .= ', FEX = NULL';
			} else {
				$strCadSql .= ', FEX = '."'".$this->fex."'";
			}
		}
		$strCadSql .= ',IND_COMP_IND = '.$this->indCompInd;
		$strCadSql .= ',IND_MOV_VPED = '.$this->indMovVped;
		$strCadSql .= ',IND_MOV_VALB = '.$this->indMovValb;
		$strCadSql .= ',IND_MOV_VDIR = '.$this->indMovVdir;
		$strCadSql .= ',IND_MOV_VFAC = '.$this->indMovVfac;
		$strCadSql .= ',IND_MOV_VPAG = '.$this->indMovVpag;
		$strCadSql .= ',EMAIL_CONF = '."'".$this->emailConf."'";
		$strCadSql .= ',TIPO_PRECIO = '."'".$this->tipoPrecio."'";
		$strCadSql .= ',IND_USU_WEB = '.$this->indUsuWeb;
		$strCadSql .= ',IND_SECC_TER = '.$this->indSeccTer;
		$strCadSql .= ',EMAIL_CP_PED = '."'".$this->emailCpPed."'";
		$strCadSql .= ',IND_LISTA_DIR = '.$this->indListaDir;
		$strCadSql .= ',FORMA_PAGO = '."'".$this->formaPago."'";
		$strCadSql .= ',IND_TABLON = '.$this->indTablon;
		$strCadSql .= ',EMAIL_AVISO_TAB = '."'".$this->emailAvisoTab."'";
		$strCadSql .= ',AVISO_DIR = '."'".$this->avisoDir."'";
		$strCadSql .= ',ALM_STOCK = '."'".$this->almStock."'";
		$strCadSql .= ',IND_SOLO_TIENDA = '.$this->indSoloTienda;
		$strCadSql .= ',ALMACEN = '."'".$this->almacen."'";
		$strCadSql .= ',NCC_CODIGO = '."'".$this->nccCodigo."'";
		$strCadSql .= ',NCC_CLAVE = '."'".$this->nccClave."'";
		$strCadSql .= ',SOCIO_ANT = '."'".$this->socioAnt."'";
		$strCadSql .= ',EMAIL_FACTURAS = '."'".$this->emailFacturas."'";
		$strCadSql .= ',TIP_NOT_CONFOR = '."'".$this->tipNotConfor."'";
		$strCadSql .= ',COD_POSTAL = '."'".$this->codPostal."'";
		$strCadSql .= ',IND_EXC_RIE = '.$this->indExcRie;
		$strCadSql .= ',TIPO_CLIENTE = '."'".$this->tipoCliente."'";
		$strCadSql .= ',TIPO_RIESGO = '."'".$this->tipoRiesgo."'";
		$strCadSql .= ',IND_MOV_VRAP = '.$this->indMovVrap;
		$strCadSql .= ',IND_PAN_CDIRE = '.$this->indPanCdire;
		$strCadSql .= ',IND_PAN_PEDF = '.$this->indPanPedf;
		$strCadSql .= ',TIPO_ENVIO = '."'".$this->tipoEnvio."'";
		$strCadSql .= ',IND_TIE_FRKY = '.$this->indTieFrky;
		if (strtoupper(str_replace(' ','',$this->feaFky)) == 'NOW()') { 
			$strCadSql .= ',FEA_FKY = '.$this->feaFky;
		} else {
			if ($this->feaFky == NULL) {
				$strCadSql .= ', FEA_FKY = NULL';
			} else {
				$strCadSql .= ', FEA_FKY = '."'".$this->feaFky."'";
			}
		}
		$strCadSql .= ',IND_AUD_RIESGO = '.$this->indAudRiesgo;
		$strCadSql .= ',PUNTO_OPERACIONAL = '."'".$this->puntoOperacional."'";
		$strCadSql .= ',IND_FKY_SOLO_COM_MERCH = '.$this->indFkySoloComMerch;
		$strCadSql .= ',PROVINCIA = '."'".$this->provincia."'";
		$strCadSql .= ',PAIS = '."'".$this->pais."'";
		$strCadSql .= ',INDICADOR_PRONTO_PAGO_DIRECTO = '.$this->indicadorProntoPagoDirecto;
		$strCadSql .= ',FORMA_PAGO_DIRECTO = '."'".$this->formaPagoDirecto."'";
		$strCadSql .= ',CODIGO_RESPONSABLE_COMERCIAL = '."'".$this->codigoResponsableComercial."'";
		$strCadSql .= ',FORMATO_FACTURA_DOCUMENTO = '."'".$this->formatoFacturaDocumento."'";
		$strCadSql .= ' WHERE CDCLIEN = '.$this->cdclien;
		$strCadSql .= ' AND CDSUCUR = '.$this->cdsucur;
		return $strCadSql;
	}

	public function sqlDelete() {
		$strCadSql  = 'DELETE FROM '.$this->tableName;
		$strCadSql .= ' WHERE CDCLIEN = '.$this->cdclien;
		$strCadSql .= ' AND CDSUCUR = '.$this->cdsucur;
		return $strCadSql;
	}

// FUNCIONES ACCESO A DATOS
	public function sqlSelect($pTipo, $pSqlWhe, $pOrden = '', $pLimitSql = '') {
		$strCadSql  = 'SELECT *';
		$strCadSql .= " FROM ".$this->tableName;
		if ($pTipo == 'PK') {
			$strCadSql .= ' WHERE CDCLIEN = '.$this->cdclien;
			$strCadSql .= ' AND CDSUCUR = '.$this->cdsucur;
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
		echo 'CDCLIEN: => '.$this->cdclien.'<br>';
		echo 'CDSUCUR: => '.$this->cdsucur.'<br>';
		echo 'NOMBRE: => '.$this->nombre.'<br>';
		echo 'NIF: => '.$this->nif.'<br>';
		echo 'DOMICILIO: => '.$this->domicilio.'<br>';
		echo 'LOCALIDAD: => '.$this->localidad.'<br>';
		echo 'TELEFONO: => '.$this->telefono.'<br>';
		echo 'FAX: => '.$this->fax.'<br>';
		echo 'EMAIL: => '.$this->email.'<br>';
		echo 'CLAVE: => '.$this->clave.'<br>';
		echo 'COMPRA_SOC: => '.$this->compraSoc.'<br>';
		echo 'COMPRA_SUC: => '.$this->compraSuc.'<br>';
		echo 'IND_ADM_TIE: => '.$this->indAdmTie.'<br>';
		echo 'IND_ACT: => '.$this->indAct.'<br>';
		echo 'IND_DIR: => '.$this->indDir.'<br>';
		echo 'IND_FRKY: => '.$this->indFrky.'<br>';
		echo 'IND_CARTERA: => '.$this->indCartera.'<br>';
		echo 'ULT_ENTRADA: => '.$this->ultEntrada.'<br>';
		echo 'RIESGO_DISP: => '.$this->riesgoDisp.'<br>';
		echo 'RIESGO_DISP_LIM: => '.$this->riesgoDispLim.'<br>';
		echo 'FEC_ACT_RIE: => '.$this->fecActRie.'<br>';
		echo 'FEX: => '.$this->fex.'<br>';
		echo 'IND_COMP_IND: => '.$this->indCompInd.'<br>';
		echo 'IND_MOV_VPED: => '.$this->indMovVped.'<br>';
		echo 'IND_MOV_VALB: => '.$this->indMovValb.'<br>';
		echo 'IND_MOV_VDIR: => '.$this->indMovVdir.'<br>';
		echo 'IND_MOV_VFAC: => '.$this->indMovVfac.'<br>';
		echo 'IND_MOV_VPAG: => '.$this->indMovVpag.'<br>';
		echo 'EMAIL_CONF: => '.$this->emailConf.'<br>';
		echo 'TIPO_PRECIO: => '.$this->tipoPrecio.'<br>';
		echo 'IND_USU_WEB: => '.$this->indUsuWeb.'<br>';
		echo 'IND_SECC_TER: => '.$this->indSeccTer.'<br>';
		echo 'EMAIL_CP_PED: => '.$this->emailCpPed.'<br>';
		echo 'IND_LISTA_DIR: => '.$this->indListaDir.'<br>';
		echo 'FORMA_PAGO: => '.$this->formaPago.'<br>';
		echo 'IND_TABLON: => '.$this->indTablon.'<br>';
		echo 'EMAIL_AVISO_TAB: => '.$this->emailAvisoTab.'<br>';
		echo 'AVISO_DIR: => '.$this->avisoDir.'<br>';
		echo 'ALM_STOCK: => '.$this->almStock.'<br>';
		echo 'IND_SOLO_TIENDA: => '.$this->indSoloTienda.'<br>';
		echo 'ALMACEN: => '.$this->almacen.'<br>';
		echo 'NCC_CODIGO: => '.$this->nccCodigo.'<br>';
		echo 'NCC_CLAVE: => '.$this->nccClave.'<br>';
		echo 'SOCIO_ANT: => '.$this->socioAnt.'<br>';
		echo 'EMAIL_FACTURAS: => '.$this->emailFacturas.'<br>';
		echo 'TIP_NOT_CONFOR: => '.$this->tipNotConfor.'<br>';
		echo 'COD_POSTAL: => '.$this->codPostal.'<br>';
		echo 'IND_EXC_RIE: => '.$this->indExcRie.'<br>';
		echo 'TIPO_CLIENTE: => '.$this->tipoCliente.'<br>';
		echo 'TIPO_RIESGO: => '.$this->tipoRiesgo.'<br>';
		echo 'IND_MOV_VRAP: => '.$this->indMovVrap.'<br>';
		echo 'IND_PAN_CDIRE: => '.$this->indPanCdire.'<br>';
		echo 'IND_PAN_PEDF: => '.$this->indPanPedf.'<br>';
		echo 'TIPO_ENVIO: => '.$this->tipoEnvio.'<br>';
		echo 'IND_TIE_FRKY: => '.$this->indTieFrky.'<br>';
		echo 'FEA_FKY: => '.$this->feaFky.'<br>';
		echo 'IND_AUD_RIESGO: => '.$this->indAudRiesgo.'<br>';
		echo 'PUNTO_OPERACIONAL: => '.$this->puntoOperacional.'<br>';
		echo 'IND_FKY_SOLO_COM_MERCH: => '.$this->indFkySoloComMerch.'<br>';
		echo 'PROVINCIA: => '.$this->provincia.'<br>';
		echo 'PAIS: => '.$this->pais.'<br>';
		echo 'INDICADOR_PRONTO_PAGO_DIRECTO: => '.$this->indicadorProntoPagoDirecto.'<br>';
		echo 'FORMA_PAGO_DIRECTO: => '.$this->formaPagoDirecto.'<br>';
		echo 'CODIGO_RESPONSABLE_COMERCIAL: => '.$this->codigoResponsableComercial.'<br>';
		echo 'FORMATO_FACTURA_DOCUMENTO: => '.$this->formatoFacturaDocumento.'<br>';
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
			$aObjetos[] = new TableWebClientes($aReg);
		}
		return($aObjetos);
	}

// ASIGNAR VALORES DE UN ARRAY AL OBJETO
	public function asignarValores($paArray) {
		$this->limpiar();
		$this->cdclien = $paArray ['CDCLIEN'];
		$this->cdsucur = $paArray ['CDSUCUR'];
		$this->nombre = $paArray ['NOMBRE'];
		$this->nif = $paArray ['NIF'];
		$this->domicilio = $paArray ['DOMICILIO'];
		$this->localidad = $paArray ['LOCALIDAD'];
		$this->telefono = $paArray ['TELEFONO'];
		$this->fax = $paArray ['FAX'];
		$this->email = $paArray ['EMAIL'];
		$this->clave = $paArray ['CLAVE'];
		$this->compraSoc = $paArray ['COMPRA_SOC'];
		$this->compraSuc = $paArray ['COMPRA_SUC'];
		$this->indAdmTie = $paArray ['IND_ADM_TIE'];
		$this->indAct = $paArray ['IND_ACT'];
		$this->indDir = $paArray ['IND_DIR'];
		$this->indFrky = $paArray ['IND_FRKY'];
		$this->indCartera = $paArray ['IND_CARTERA'];
		$this->ultEntrada = $paArray ['ULT_ENTRADA'];
		$this->riesgoDisp = $paArray ['RIESGO_DISP'];
		$this->riesgoDispLim = $paArray ['RIESGO_DISP_LIM'];
		$this->fecActRie = $paArray ['FEC_ACT_RIE'];
		$this->fex = $paArray ['FEX'];
		$this->indCompInd = $paArray ['IND_COMP_IND'];
		$this->indMovVped = $paArray ['IND_MOV_VPED'];
		$this->indMovValb = $paArray ['IND_MOV_VALB'];
		$this->indMovVdir = $paArray ['IND_MOV_VDIR'];
		$this->indMovVfac = $paArray ['IND_MOV_VFAC'];
		$this->indMovVpag = $paArray ['IND_MOV_VPAG'];
		$this->emailConf = $paArray ['EMAIL_CONF'];
		$this->tipoPrecio = $paArray ['TIPO_PRECIO'];
		$this->indUsuWeb = $paArray ['IND_USU_WEB'];
		$this->indSeccTer = $paArray ['IND_SECC_TER'];
		$this->emailCpPed = $paArray ['EMAIL_CP_PED'];
		$this->indListaDir = $paArray ['IND_LISTA_DIR'];
		$this->formaPago = $paArray ['FORMA_PAGO'];
		$this->indTablon = $paArray ['IND_TABLON'];
		$this->emailAvisoTab = $paArray ['EMAIL_AVISO_TAB'];
		$this->avisoDir = $paArray ['AVISO_DIR'];
		$this->almStock = $paArray ['ALM_STOCK'];
		$this->indSoloTienda = $paArray ['IND_SOLO_TIENDA'];
		$this->almacen = $paArray ['ALMACEN'];
		$this->nccCodigo = $paArray ['NCC_CODIGO'];
		$this->nccClave = $paArray ['NCC_CLAVE'];
		$this->socioAnt = $paArray ['SOCIO_ANT'];
		$this->emailFacturas = $paArray ['EMAIL_FACTURAS'];
		$this->tipNotConfor = $paArray ['TIP_NOT_CONFOR'];
		$this->codPostal = $paArray ['COD_POSTAL'];
		$this->indExcRie = $paArray ['IND_EXC_RIE'];
		$this->tipoCliente = $paArray ['TIPO_CLIENTE'];
		$this->tipoRiesgo = $paArray ['TIPO_RIESGO'];
		$this->indMovVrap = $paArray ['IND_MOV_VRAP'];
		$this->indPanCdire = $paArray ['IND_PAN_CDIRE'];
		$this->indPanPedf = $paArray ['IND_PAN_PEDF'];
		$this->tipoEnvio = $paArray ['TIPO_ENVIO'];
		$this->indTieFrky = $paArray ['IND_TIE_FRKY'];
		$this->feaFky = $paArray ['FEA_FKY'];
		$this->indAudRiesgo = $paArray ['IND_AUD_RIESGO'];
		$this->puntoOperacional = $paArray ['PUNTO_OPERACIONAL'];
		$this->indFkySoloComMerch = $paArray ['IND_FKY_SOLO_COM_MERCH'];
		$this->provincia = $paArray ['PROVINCIA'];
		$this->pais = $paArray ['PAIS'];
		$this->indicadorProntoPagoDirecto = $paArray ['INDICADOR_PRONTO_PAGO_DIRECTO'];
		$this->formaPagoDirecto = $paArray ['FORMA_PAGO_DIRECTO'];
		$this->codigoResponsableComercial = $paArray ['CODIGO_RESPONSABLE_COMERCIAL'];
		$this->formatoFacturaDocumento = $paArray ['FORMATO_FACTURA_DOCUMENTO'];
	}

// DEVOLVER LA CLAVE DE LA TABLA
	public function devolverClave($pTipDev) {
		$strDevolver = '';
		if ($pTipDev == 'NOM_CAMPO') {
			$strDevolver = 'CDSUCUR';
		} else {
			if ($pTipDev == 'VALOR') {
				$strDevolver = $this->cdsucur;
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
