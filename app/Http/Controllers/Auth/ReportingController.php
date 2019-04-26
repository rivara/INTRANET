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
            "FAMILIA	DES COMPLETA",
            "FAM-1-DESCRIPCION",
            "FAM-2-DESCRIPCION",
            "FAM-3-DESCRIPCION",
            "EXTINGUIR",
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


        //$a=$db->table('articulos')->select('familia_id')->
        //here($where[0][0], $where[0][1], $where[0][2])->where($where[1][0], $where[1][1], $where[1][2])->get();


        //Recojer varias llamadas
        //  REVISAR QUERY
        $data = $db->table('articulos')
            ->join('familias', 'articulos.familia_id', '=', 'familias.id')

            ->select('articulos.id as idArticulos', 'articulos.nombre', 'articulos.fecha_alta', 'articulos.fecha_baja',
                'articulos.tipo_producto', 'articulos.tipo_rotacion',
                'articulos.proveedor_id', 'articulos.marca', 'articulos.descripcion as Falta',
                'articulos.familia_id', 'familias.nombre as nombreFamilias'
            )
            ->selectRaw( "substring(articulos.familia_id,0,1) as fam1,
                                    substring(articulos.nombre,1,".explode('-','articulos.nombre')[0].") as desc1,
                                    substring(articulos.familia_id,2,3) as fam2,
                                    substring(articulos.nombre,".explode('-','articulos.nombre')[0].",20) as desc2,
                                    substring(articulos.familia_id,4,5) as fam3,
                                    substring(articulos.nombre,".explode('-','articulos.nombre')[0].",20) as desc3"
            )
            ->where($where[0][0], $where[0][1], $where[0][2])
            ->where($where[1][0], $where[1][1], $where[1][2])
            ->get();




        //color cabecera
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
        $dataL = Collection::make([1, 2, 3]);


        if ($request["type"] == "xls") {
            $page1 = new Sheet($precabecera, $data, $cabecera, $bg, $title, $tramos);
            $page2 = new SheetLeyenda($precabeceraL, $dataL, $cabecera, $bg, $titleL, $tramosLeyenda,$titleL);
            //$page2 = null;
            return Excel::download(new SheetsExports($page1, $page2), $filename . '.xls');
        }
        if ($request["type"] == "csv") {
            $page1 = new Sheet($precabecera, $data, $cabecera, $bg, $title, $tramos);
            $page2 = new SheetLeyenda($precabeceraL, $dataL, $cabecera, $bg, $titleL, $tramosLeyenda,$titleL);
            //$page2 = null;
            return Excel::download(new SheetsExports($page1, $page2), $filename . '.csv');
        }

    }

    public function actionObsoletos(Request $request)
    {
        return view('/reporting/index', ['option' => $request['option']]);
    }


}