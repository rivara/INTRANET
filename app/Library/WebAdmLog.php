<?php
namespace App\Library;

use App\Library\TableWebAdmLog;
use App\Library\Iniutils;
use App\Library\Conexion;
use PDO;


class WebAdmLog extends TableWebAdmLog
{

    public function getRegistros($fechaDesde, $fechaHasta, $itemDesde = null, $cantidad = null)
    {

        $where = '1 = 1';
        
        if (!empty($this->userMag)) {
            $where .= " AND user_mag = '{$this->userMag}'";
        }
        
        if (!empty($this->cdclien)) {
            $where .= " AND cdclien = '{$this->cdclien}'";
        }
        
        if (!empty($this->cdsucur)) {
            $where .= " AND cdsucur = '{$this->cdsucur}'";
        }
        
        if (!empty($fechaDesde)) {
            // Obtenemos la fecha con el formato yyyymmdd.
           //$fechaDesdeYYYYMMDD = Iniutils::dmyTOymd($fechaDesde, '');
            $fechaDesdeYYYYMMDD=str_replace("-","",$fechaDesde);
           $where              .= " AND DATE_FORMAT(fex, '%Y%m%d') >= '{$fechaDesdeYYYYMMDD}'";


        }
        
        if (!empty($fechaHasta)) {
            // Obtenemos la fecha con el formato yyyymmdd.
           // $fechaHastaYYYYMMDD = Iniutils::dmyTOymd($fechaHasta, '');
            $fechaHastaYYYYMMDD=str_replace("-","",$fechaHasta);
            $where              .= " AND DATE_FORMAT(fex, '%Y%m%d') <= '{$fechaHastaYYYYMMDD}'";
        }
        
        if (!empty($this->empresa)) {
            $where .= " AND empresa ='{$this->empresa}'";
        }
        
        if (!empty($this->seccion)) {
            $where .= " AND seccion IN ('{$this->seccion}')";
        }
        
        if (!empty($this->des)) {
            $where .= " AND des LIKE '%{$this->des}%'";
        }
        
        $orderBy = 'fex, idlog';
        $limit   = !is_null($itemDesde) && !is_null($cantidad) ? "LIMIT {$itemDesde},{$cantidad}" : '';


        $sql = <<<SQL
SELECT
  user_mag AS USER_MAG,
  empresa AS EMPRESA,
  seccion AS SECCION,
  cdclien AS CDCLIEN,
  cdsucur AS CDSUCUR,
  des AS DES,
  DATE_FORMAT(fex,'%d/%m/%Y %k:%i') AS FOR_FEC
FROM {$this->tableName}
WHERE {$where}
ORDER BY {$orderBy}
{$limit}
SQL;



        $this->lastSqlExecuted($sql);

        $query = Conexion::getInstancia()->prepare($sql);

        $query->execute();


        $errores = $query->errorInfo();
        if (!empty($errores[2])) {
            $this->pintarScreen('ERROR', $errores[2] . ' (SQL: ' . $sql . ' )');
            $this->log->LogError($sql);
        }
        if ($this->depurarObjeto) {
            $this->log->LogDebug(__METHOD__ . ": {$this->lastSqlExecuted}");
        }
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
