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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        $fechaDesd=date('d-m-y',strtotime($fechaDesde));
        $fechaHasta = $request["fechaHasta"];
        $fechaHast =date('d-m-y',strtotime($fechaHasta));

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
            "EXTINGUIR",
            "VENTAS (UDS)",
            "VENTAS (PVP)",
            "VENTAS(PMEDIO)",
            "STOCK ACTUAL",
            "MARGEN BRUTO",
            "STOCK MEDIO (UDS)",
            "INDICE ROTACION",
            "MARGEN POR ROTACION",
            "SURTIDO",
            "EJECUCUION"
        );

        //consulta
        $where = array();;
        if (!empty($familia_id)) {
            if ($niveles) {
                $where[] = array('familia_id', 'like', $familia_id . '%');
            } else {
                $where[] = array('familia_id', '=', $familia_id);
            }

        } else {
            $where[] = array('familia_id', 'like', '%');
        }


        if (!empty($proveedor_id)) {
            $where[] = array_push($where, ['proveedor_id', '=', $proveedor_id]);
        } else {
            $where[] = array_push($where, ['proveedor_id', 'like', '%']);
        }


        // FALTA AÑADIR FECHA  RVR
        //STOCK MEDIO (UDS)
        if($almacen=='PRINCIPAL'){
            $query="(select avg(ali_stock) from stock_medio  where articulo_id =articulos.id  GROUP BY articulo_id)as stockMedio";

        }else{
            $query="(select avg(mad_stock) from stock_medio  where articulo_id =articulos.id  GROUP BY articulo_id) as stockMedio";
        }




/*
        $data = $db->table('articulos')
            ->select(
                'articulos.id as idArticulos',
                'articulos.nombre',
                'articulos.fecha_alta',
                'articulos.fecha_baja',
                'articulos.tipo_producto',
                'articulos.tipo_rotacion',
                'articulos.proveedor_id',
                'proveedores.nombre as razon_social',
                'articulos.referencia_proveedor',
                'proveedores.comprador_id',
                'articulos.marca',
                'articulos.es_merch_ferrokey',
                'articulos.coste_medio as costeMedio',
                'familias.id as familiaId',
                'familias.nombre as familiaNombre',
                $db->raw("(select substring(id,1,2) as f from familias where id=" . 'familiaId' . ")  as fam1"),
                $db->raw("(select familias.nombre from familias where id=fam1 ) as desc1"),
                $db->raw("(select substring(id,1,4) as f from familias where id=" . 'familiaId' . ")  as fam2"),
                $db->raw("(select familias.nombre from familias where id=fam2 ) as desc2"),
                $db->raw("(select substring(id,1,6) as f from familias where id=" . 'familiaId' . ")  as fam3"),
                $db->raw("(select familias.nombre from familias where id=fam3 ) as desc3"),
                'articulos_almacen.es_extinguir as es_extinguir',
                $db->raw("now()"))
                ->join('proveedores', 'proveedores.id', '=', 'articulos.proveedor_id')
                ->join('familias', 'familias.id', '=', 'articulos.familia_id')
                ->join('articulos_almacen', 'articulos_almacen.articulo_id', '=', 'articulos.id')
                ->whereBetween('articulos.fecha_actualizacion', array($fechaDesde, $fechaHasta))
                ->where($where[0][0], $where[0][1], $where[0][2])
                ->where($where[1][0], $where[1][1], $where[1][2])
                ->where("articulos_almacen.almacen","like",$almacen)
                ->get();

*/




/////////////////////////////////////////////////VENTAS

        //$data = $db->select($db->raw( "(select articulo_id ID,SUM(cantidad) CANSUM  from historico_ventas_detalle  WHERE empresa=1 AND year(fecha)=2018 AND es_directo=0 GROUP BY articulo_id)"));
        $data = $db->select($db->raw("(SELECT art.id, ifnull(ven.CANSUM,0) as VENTA, alm.almacen
                                                FROM articulos art
                                                LEFT OUTER JOIN (
                                                select articulo_id art,SUM(cantidad) CANSUM
                                                from historico_ventas_detalle v LEFT OUTER JOIN articulos a ON v.articulo_id = a.id
                                                WHERE empresa=1 AND year(fecha)=2018 AND es_directo=0 AND a.fecha_baja is null
                                                GROUP BY articulo_id
                                                ) ven ON art.id = ven.art
                                                LEFT OUTER JOIN articulos_almacen alm ON art.id = alm.articulo_id AND alm.almacen = 'PRINCIPAL'
                                                WHERE art.fecha_baja is null
                                                ORDER BY art.id)"));
        var_dump($data);
        die;


         //select articulo_id ID,SUM(cantidad) CANSUM  from historico_ventas_detalle  WHERE empresa=1 AND year(fecha)=2018 AND es_directo=0 GROUP BY articulo_id

        // QUERY SANTI
        /*$data = $db->select($db->raw("(select SUM(det.cantidad) as cantidad
                 from historico_ventas_detalle det inner join historico_ventas cab ON det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento
                 WHERE cab.almacen ='".$almacen."' AND det.articulo_id = articulos.id group by articulo_id) as sumcantidad"));
            /*
            $db->raw("(select SUM(det.importe)
                 from historico_ventas_detalle det inner join historico_ventas cab ON det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento
                 WHERE cab.almacen = '".$almacen."' AND det.articulo_id = articulos.id group by articulo_id) as sumimporte")
            */



        $bg = array("808080", "0000ff", "B5BF00");
        // nombre de pestaña
        $title = "INFORME";

        //Parametrizar en funcion de la tabla
        $fin1 = 12;
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
                $message->from('rvalle@comafe.es', '---');
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
            // PENDIENTE
            //$files = array('readme.txt', 'test.html', 'image.gif');
             $zipname = 'file.zip';
             $zip = new ZipArchive;
             $zip->open($zipname, ZipArchive::CREATE);
             $zip->addFile(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls'));
             $zip->close();
             echo 'Archive created!';
             header('Content-disposition: attachment; filename=files.zip');
             header('Content-type: application/zip');
        }


        if ($request["type"] == "xls") {

            return Excel::download(new SheetsExports($page1, $page2), $filename . '.xls');
        }
        if ($request["type"] == "csv") {
            return Excel::download(new SheetsExports($page1, $page2), $filename . '.csv');
        }

    }

    public function actionObsoletos(Request $request)
    {
        return view('/reporting/index', ['option' => $request['option']]);
    }


}