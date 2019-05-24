<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 11/04/2019
 * Time: 10:43
 */

namespace App\Http\Controllers\Auth;

use App\Exports\Sheet;
use App\Exports\SheetLeyenda;
use App\Exports\SheetsExports;
use Cblink\ExcelZip\ExcelZip;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use ZipArchive;


class reportingController
{
    public function actionReportingRedirect(Request $request)
    {
        return view('/reporting/index', ['option' => $request['option']]);
    }

    /**
     * @param Request $request
     */
    public function actionindiceDeRotacion(Request $request)
    {




        //VARIABLES
        $almacen = $request["almacen"];
        $fechaDesde = $request["fechaDesde"];
        $fechaHasta = $request["fechaHasta"];
        $proveedor_id = $request["proveedor"];
        $familia_id = $request["familia"];
        $filename = "indiceDeRotacion";
        $niveles = $request["niveles"];
        $calculo = $request["calculo"];

        //INFORME
        $precabecera = array(
            array(date("F j, Y, g:i a")),
            array(date("H:i:s")),
            array("Indice De Rotacion"),
            array(""),
            array("*PERIODO", $fechaDesde, "a", $fechaHasta),
            array("*ALMACEN", $almacen),
            array("*PROVEEDOR", $proveedor_id),
            array("*LISTAS ARTICULOS ENVASES", "?"),
            array("*EN FUENTE DE COLOR ROJO VIENEN LOS ARTICULOS EN BAJA. LOS ARTICULOS MERCHANDISING NO DEBEN SALIR."),
            array("*ACTUALIZACION DE STOCK MEDIO:", " 01/06/2013" . "al", date("d:m:y")),
            array(" ")
        );
        //BBDD
        //usar otra bbdd
        $db = DB::connection('reporting');
        $cabecera = array(
            "ARTICULO",
            "DESCRIPCION",
            "F.ALTA",
            "F.BAJA",
            "TIPO ART.",
            "TIPO ROT.",
            "PROVEEDOR",
            "RAZON SOCIAL",
            "REFERENCIA",
            "COMPRADOR",
            "MARCA",
            "CAT.FERROKEY?",
            "PRECIO MEDIO",
            "FAMILIA",
            "DESC COMPLETA",
            "FAM-1-",
            "DESCRIPCION FAM1",
            "FAM-2-",
            "DESCRIPCION FAM2",
            "FAM-3-",
            "DESCRIPCION FAM3",
            "DATOS ALMACEN EXTINGUIR",
            "VENTAS (UDS)",
            "VENTAS (PVP)",
            "VENTAS(PMEDIO)",
            "STOCK ACTUAL",
            "MARGEN BRUTO",
            "STOCK MEDIO (UDS)",
            "INDICE ROTACION",
            "MARGEN POR ROTACION",
            "SURTIDO"
        );









        //consultas

        if (!empty($familia_id)) {
            $familia="and a.proveedor_id = '".$familia_id."'";
            } else {
            $familia = "";
            }

        if (!empty($proveedor_id)) {
            $proveedor="and a.proveedor_id = '".$proveedor_id."'";
        } else {
            $proveedor=" ";
        }

        if (!empty($proveedor_id)) {
            $proveedor="and a.proveedor_id = '".$proveedor_id."'";
        } else {
            $proveedor=" ";
        }

        $fecha1="AND v.fecha  BETWEEN '".$fechaDesde."' AND '".$fechaHasta."'";
        $fecha2="AND sm.fecha  BETWEEN '".$fechaDesde."' AND '".$fechaHasta."'";

        if($almacen=='PRINCIPAL'){
            $stock= "mad_stock as stock";
            $stockMedio= "AVG(mad_stock) as stockMedia";
        }else {
            $stock = "ali_stock as stock";
            $stockMedio= "AVG(ali_stock) as stockMedia";
        }


/////////////////////////////////////////////////VENTAS

 $data = $db->select($db->raw("(SELECT 
        a.id,
        a.nombre as NombreArticulo,
        DATE_FORMAT(a.fecha_alta,'%d/%m/%Y')  as FechaAlta,
        DATE_FORMAT(a.fecha_baja ,'%d/%m/%Y') as FechaBaja,
        a.tipo_producto as tipoProcuto,
        a.tipo_rotacion,
        a.proveedor_id,
        pro.nombre as razonSocial,
        a.referencia_proveedor,
        pro.comprador_id,
        a.marca,
        a.es_merch_ferrokey,
        a.coste_medio ,
        a.familia_id,
        fam.ampliada, 
		substring(a.familia_id,1,2) as n1,
		fam1.nombre as nombreFam1,
		substring(a.familia_id,1,4) as n2,
		fam2.nombre as nombreFam2,
	    substring(a.familia_id,1,6) as n6,
		fam3.nombre as nombreFam3,
		if(alm.es_extinguir=1,'SI',' ') as ext,
        ifnull(ven.CANSUM,0) as VENTA, 
        ifnull(ven.CANIMP,0) as IMPORTE,
        a.coste_medio * ven.CANSUM as costeMedio,
        alm.stock_actual,
        (ifnull(ven.CANSUM,0) - ( a.coste_medio * ven.CANSUM )) as margenBruto,
        ROUND(stockMedia) as stockM,
        (ifnull(ven.CANSUM,0)/ROUND(stockMedia))  as indicePorMargeDeRotacion,
        (ifnull(ven.CANSUM,0) - ( a.coste_medio * ven.CANSUM )/(ifnull(ven.CANSUM,0)/ROUND(stockMedia))) as margenPorRotacion,
        if(alm.es_surtido_alicante,'SI',' ') as surtido
                                FROM articulos a
                                LEFT OUTER JOIN (
                                    select articulo_id art,SUM(cantidad) CANSUM ,SUM(importe) CANIMP
                                    from historico_ventas_detalle v LEFT OUTER JOIN articulos a ON v.articulo_id = a.id
                                    WHERE empresa=1 
                                    AND es_directo=0 
                                    AND a.fecha_baja is null
                                    ".$proveedor."
                                    ".$familia."  
                                    ".$fecha1."  
                                    GROUP BY articulo_id
                                ) ven ON a.id = ven.art
                                
                                LEFT OUTER JOIN articulos_almacen alm ON a.id = alm.articulo_id AND alm.almacen = '".$almacen."'
                                LEFT JOIN proveedores pro ON a.proveedor_id = pro.id 
                                LEFT JOIN familias fam ON a.familia_id = fam.id 
                                LEFT JOIN familias fam1 ON substring(a.familia_id,1,2) = fam1.id 
                                LEFT JOIN familias fam2 ON substring(a.familia_id,1,4) = fam2.id 
                                LEFT JOIN familias fam3 ON substring(a.familia_id,1,6) = fam3.id 
                                LEFT JOIN (
                                    SELECT articulo_id ,AVG(mad_stock) as stockMedia
                                    FROM stock_medio sm
                                    LEFT JOIN articulos a ON sm.articulo_id = a.id
                                    WHERE a.fecha_baja is null
                                    ".$proveedor."
                                    ".$fecha2."  
                                  GROUP BY articulo_id
                                ) sm ON a.id = sm.articulo_id
        WHERE a.fecha_baja is null ".$proveedor." ".$familia."   ORDER BY a.id )"));
        $bg = array("808080", "0000ff", "B5BF00");
        // nombre de pestaña
        $title = "INFORME";
        //Parametrizar en funcion de la tabla
        $fin1 = 11;
        $fin2 = $fin1 + 10;
        $fin3 = $fin2 + 10;
        $tramo1 = Coordinate::stringFromColumnIndex(1) . "12:" . Coordinate::stringFromColumnIndex($fin1) . "12";
        $tramo2 = Coordinate::stringFromColumnIndex($fin1 + 1) . "12:" . Coordinate::stringFromColumnIndex($fin2) . "12";
        $tramo3 = Coordinate::stringFromColumnIndex($fin2 + 1) . "12:" . Coordinate::stringFromColumnIndex($fin3) . "12";
        $tramos = array($tramo1, $tramo2, $tramo3);


        //LEYENDA
        $precabeceraL = array();
        $tramo1 = "A2:A" . ($fin1 + 2);
        $tramo2 = "A" . ($fin1 + 3) . ":A" . ($fin1 + $fin2 + 3);
        $tramo3 = "A" . ($fin2 + 2) . ":A" . ($fin2 + $fin3 + 3);
        $tramosLeyenda = array($tramo1, $tramo2, $tramo3);
        $titleL = "LEYENDA";
        //$dataL = $cabecera;
        $dataL =$data;
        $arr = [19, 21, 46];
        $collection = collect($arr);


        $page1 = new Sheet($precabecera, $data, $cabecera, $bg, $title, $tramos);
        //   $page2 = new SheetLeyenda($precabeceraL, $collection, $cabecera, $bg, $titleL, $tramosLeyenda,$titleL);
        $page2 = null;



        // Envio del mail
        if((!is_null($request["email"]))&&($request["enviaMail"]==true)){

            $excelFile = Excel::download(new SheetsExports($page1, $page2), $filename . '.xls');
            if(is_null($request["asunto"])){
                $messageBody ="Informe de Indice de rotacion";
            }else{
                $messageBody=$request["asunto"];
            }
            $email=$request["email"];
            $message="Este mail contiene el informe de rotacion";
            Mail::raw($messageBody,function ($message) use ($email,$page1) {
                    $message->from('rvalle@comafe.es', 'Informe de Indice de rotación');
                $message->to($email);
                $message->subject('indice de rotacion');
                $message->attach(
                    Excel::download(
                        new SheetsExports($page1, null),
                        'report.xlsx'
                    )->getFile(), ['as' => 'report.xls']

                );
            });
            return view('/reporting/index', ['option' =>'IndiceDeRotacion']);
        }

        if($request["compresion"]==true){
            // PENDIENTE DE TESTEAR
            // https://github.com/cblink/laravel-excel-zip
                //$excelZip ="";
                //$excelZip = $excelZip->setExport($export);
                //$excelZip->Excel::download(new SheetsExports($page1, $page2), $filename . '.xls');
                //return $excelZip->zip();
                //return $excelZip->download(Member::all(), $export)
        }


        if ($request["type"] == "xls") {

           return(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls'));
        }
        if ($request["type"] == "csv") {
            return(Excel::download(new SheetsExports($page1, $page2), $filename . '.csv'));
        }

    }

    public function actionObsoletos(Request $request)
    {
        return view('/reporting/index', ['option' => $request['option']]);
    }


}