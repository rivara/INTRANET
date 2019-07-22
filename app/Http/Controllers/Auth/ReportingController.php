<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 11/04/2019
 * Time: 10:43
 */

namespace App\Http\Controllers\Auth;

use App\Exports\Sheet;
use App\Exports\Sheet2;
use App\Exports\SheetLeyenda;
use App\Exports\SheetsExports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
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

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////// INDICE DE ROTACION ///////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////

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
        $compresion=$request["compresion"];
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
            $familia = "and a.proveedor_id = '" . $familia_id . "'";
        } else {
            $familia = "";
        }

        if (!empty($proveedor_id)) {
            $proveedor = "and a.proveedor_id = '" . $proveedor_id . "'";
        } else {
            $proveedor = " ";
        }


        $fecha1 = "AND v.fecha  BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "'";
        $fecha2 = "AND sm.fecha  BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "'";

        if ($almacen == 'PRINCIPAL') {
            $stock = "mad_stock as stock";
            $stockMedio = "AVG(mad_stock) as stockMedia";
        } else {
            $stock = "ali_stock as stock";
            $stockMedio = "AVG(ali_stock) as stockMedia";
        }


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
                                    " . $proveedor . "
                                    " . $familia . "  
                                    " . $fecha1 . "  
                                    GROUP BY articulo_id
                                ) ven ON a.id = ven.art
                                
                                LEFT OUTER JOIN articulos_almacen alm ON a.id = alm.articulo_id AND alm.almacen = '" . $almacen . "'
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
                                    " . $proveedor . "
                                    " . $fecha2 . "  
                                  GROUP BY articulo_id
                                ) sm ON a.id = sm.articulo_id
        WHERE a.fecha_baja is null " . $proveedor . " " . $familia . "   ORDER BY a.id )"));

        $bg = array("808080", "0000ff", "B5BF00");
        $title = "INFORME";
        //LEYENDA
        $fin1 = 12;
        $fin2 = $fin1 + 9;
        $fin3 = $fin2 + 11;
        $tramo1 = Coordinate::stringFromColumnIndex(1) . "12:" . Coordinate::stringFromColumnIndex($fin1) . "12";
        $tramo2 = Coordinate::stringFromColumnIndex($fin1 + 1) . "12:" . Coordinate::stringFromColumnIndex($fin2) . "12";
        $tramo3 = Coordinate::stringFromColumnIndex($fin2 + 1) . "12:" . Coordinate::stringFromColumnIndex($fin3) . "12";
        $tramos = array($tramo1, $tramo2, $tramo3);
        $precabeceraL = array("CAMPO INFORME", "DESCRIPCION COMENTARIOS");
        $tramo1 = "A2:A" . ($fin1);
        $tramo2 = "A" . ($fin1) . ":A" . ($fin2);
        $tramo3 = "A" . ($fin2) . ":A" . ($fin3);
        $tramosLeyenda = array($tramo1, $tramo2, $tramo3);
        $titleL = "LEYENDA";
        $comentarios = array(
            "Codigo de articulo",
            "Descripcion del articulo",
            "Fecha de alta",
            "Fecha de baja",
            "Tipo de articulo (NAC,UE o IMP)",
            "Tipo Rotacion (Este campo es un campo de Navision, que no sé si alguien lo actualiza)",
            "Proveedor",
            "Razon social del proveedor",
            "Referencia del articulo",
            "Comprador asociado al proveedor del articulo",
            "Marca asociada al articulo",
            "Esta en el surtido basico de ferrokey",
            "Precio medio de la ficha del articulo en Navision",
            "Familia",
            "Descripcion completa de la familia",
            "Nivel 1 de la familia",
            "Descripcion del nivel 1 de la familia",
            "Nivel 2 de la familia",
            "Descripcion del nivel 2 de la familia",
            "Nivel 3 de la familia",
            "Descripcion del nivel 3 de la familia",
            "¿Esta a extinguir?",
            "Unidades vendidas",
            "Importe de las unidades",
            "Importe de las unidades vendidas (valoradas al coste)",
            "Stock a la fecha que se genera el informe",
            "La suma de los importes de la ventas menos el coste de estas ventas.",
            "Stock medio del producto (En unidades)",
            "Son las unidades vendidas divididas por el stock medio",
            "Es la division del margen bruto, por el indice de rotacion",
            "¿Tiene marcado la casilla surtido de alicante?"
        );


        $i = 0;
        foreach ($cabecera as $cab) {
            $array[$i][1] = $cab;
            $array[$i][2] = $comentarios[$i];
            $i++;
        }

        $cabeceraL = array();
        $page1 = new Sheet($precabecera, $data, $cabecera, $bg, $title, $tramos);
        $page2 = new SheetLeyenda($precabeceraL, $array, $precabeceraL, $bg, $titleL, $tramosLeyenda);

        // Envio del mail
        if ((!is_null($request["email"])) && ($request["enviaMail"] == true)) {
            set_time_limit(20000);
            //generacion del zip
            $zip = new ZipArchive;
            if ($zip->open($filename.'.zip', ZipArchive::CREATE) === true) {
                $zip->addFile(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(),
                    $filename.'.xls');
                $zip->close();
            }

            if (is_null($request["asunto"])) {
                $messageBody = "Informe de Indice de rotacion";
            } else {
                $messageBody = $request["asunto"];
            }
            $email = $request["email"];
            $message = "Este mail contiene el informe de rotacion";
            Mail::raw(/**
             * @param $message
             */
                $messageBody, function ($message) use ($filename, $page2, $compresion, $email, $page1) {
                $message->from('rvalle@comafe.es', 'Informe de Indice de rotación');
                $message->to($email);
                $message->subject('indice de rotacion');

                if ($compresion== true) {
                    set_time_limit(20000);
                    $message->attach(response()->download($filename.".zip")->getFile(), ['as' => 'report.zip']);
                    return redirect()->back();
                }else{
                    set_time_limit(20000);
                    $message->attach(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(), ['as' => 'report.xls']);
                    return redirect()->back();
                }
            });

        }

        if (($compresion == true)&&($request["enviaMail"] == false)) {
            //generacion del zip
            $zip = new ZipArchive;
            if ($zip->open($filename.'.zip', ZipArchive::CREATE) === true) {
                $zip->addFile(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(),
                    $filename.'.xls');
                $zip->close();
            }
            return response()->download($filename.".zip");
        }


        if ($request["type"] == "xls") {
            set_time_limit(20000);
            return (Excel::download(new SheetsExports($page1, $page2), $filename . '.xls'));
        }
        if ($request["type"] == "csv") {
            set_time_limit(20000);
            //return(Excel::download(new SheetsExports($page1, $page2), $filename . '.csv'));
            //parametrizacion
            $cabecera = "ARTICULO;DESCRIPCION;F.ALTA;F.BAJA;TIPO ART.;TIPO ROT.;PROVEEDOR;RAZON SOCIAL;REFERENCIA;COMPRADOR;MARCA;CAT.FERROKEY;PRECIO MEDIO;FAMILIA;DESC COMPLETA;FAM-1-;DESCRIPCION;FAM-2-;DESCRIPCION;FAM-3-;DESCRIPCION;DATOS ALMACEN EXTINGUIR;VENTAS (UDS);VENTAS (PVP);VENTAS(PMEDIO);STOCK ACTUAL;MARGEN BRUTO;STOCK MEDIO (UDS);INDICE ROTACON;MARGEN POR ROTACION;SURTIDO";
            $cabeza = date("d-m-Y h:i:sa") . "\n Indice De Rotacion \n PERIODO, " . $fechaDesde . " a " . $fechaHasta . "  \n ALMACEN " . $almacen . " \n PROVEEDOR " . $proveedor_id . " \nLISTAS ARTICULOS ENVASES";
            $array = $cabeza . "\n\n" . $cabecera . "\n";
            foreach ($data as $list) {
                foreach ($list as $dat) {
                    $array = $array . $dat . ";";
                }
                $array = $array . "\n";
            }
            return response()->attachmentCSV($array, $filename.".csv");
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////// OBSOLETOS  //////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function actionObsoletos(Request $request)
    {
        //VARIABLES
        $fechaDesde = $request["fechaDesde"];
        $fechaHasta = $request["fechaHasta"];
        $proveedor_id = $request["proveedor"];
        $filename = "obsoletos";
        $stockmedio = $request["stockmedio"];
        $articulo = $request["articulo"];
        $calculo = $request["calculo"];
        $compresion=$request["compresion"];
        $date = strtotime($fechaDesde.'-2 year');
        $fechaDesdeHace2años= date('Y-m-d', $date);



        //INFORME
        $precabecera = array(
            array(date("F j, Y, g:i a")),
            array("Obsoletos"),
            array(""),
            array("*PERIODO ANALIZADO", $fechaDesde, "a", $fechaHasta),
            array("*PERIODO ANALIZADO PARA ARTICULO IMP:", $fechaDesdeHace2años, "a", $fechaHasta),
            array(""),
            array("*LOS DATOS CALCULADOS VIENEN VACIOS. PRECIOS DESDE LA FICHA ARTICULOS"),
            array("*STOCK ACTUAL y VENTAS (Movimientos de almacenes PRINCIPAL y ALICANTE, hasta el dia:".$fechaHasta.")"),
            array("*COMPRAS (Movimientos de almacenes PRINCIPAL / ALICANTE / IMPORTAC)"),
            array("*EN LOS ARTICULOS DE IMPORTACION SE COGEN LAS VENTAS Y COMPRAS DE LOS ULTIMOS DOS AÑOS."),
            array("* EN ROJO LOS ARTICULOS CON VARIOS PRECIOS ACTIVOS."),
            array("* CODIGOS DE ARTICULOS MARCADOS COMO ENVASE: 1,2,3,4,57145,57146,57148"),
            array(" ")
        );
        //BBDD
        //usar otra bbdd
        $db = DB::connection('reporting');
        $cabecera = array(
            "ARTICULO",
            "DESCRIPCION",
            "TIPO PROD.",
            "F.BAJA",
            "COMPRADOR",
            "PROVEEDOR",
            "RAZON SOCIAL",
            "MARCA",
            "FAMILIA",
            "FAM-1- DES",
            "FAM-2- DES",
            "FAM-3- DES",
            "PRESENTACION",
            "STOCK ACTUAL",
            "VALORACION",
            "COMPRAS 1 AÑO",
            "COMPRAS 2 AÑOS",
            "CODIGO ANTERIOR",
            "COMPRAS COD.ANT.",
            "VENTAS 1 AÑO",
            "VENTAS 2 AÑOS",
            "VENTAS COD.ANT.",
            "PRECIO COSTE",
            "PRECIO VENTA SOCIO",
            "PRECIO MEDIO",
            "PRECIO MEDIO CALCULADO",
            "COMENTARIO",
            "AÑOS COBERTURA",
            "% OBSOLETO",
            "VALOR OBSOLESCENCIA",
            "PFACTOR CONVERSION (COD.ANT)"
        );

        //consultas



        /* if (!empty($articulo)) {
             $articulo = "AND a.articulo = '" . $articulo . "'";
         } else {
             $articulo = " ";
         }*/

        if (!empty($proveedor_id)) {
            $proveedor = "AND a.proveedor_id = '" . $proveedor_id . "'";
        } else {
            $proveedor = " ";
        }


        if (!empty($articulo)) {
            $articulo = "AND a.id = '" . $articulo . "'";
        } else {
            $articulo = " ";
        }




        $fechaVenta1 = "AND v1.fecha  BETWEEN '" . $fechaDesde . "' AND '" .$fechaHasta."'";
        $fechaCompra1 = "AND c1.fecha  BETWEEN '" .$fechaDesde. "' AND '" .$fechaHasta. "'";
        $fechaVenta2 = "AND v2.fecha  BETWEEN '" .$fechaDesdeHace2años. "' AND '" .$fechaHasta. "'";
        $fechaCompra2 = "AND c2.fecha  BETWEEN '" .$fechaDesdeHace2años. "' AND '" .$fechaHasta. "'";

        $fechaF = "AND sm.fecha  BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "'";



        $data = $db->select($db->raw("(
        SELECT a.id Articulo, 
		a.nombre as Descripcion, 
		a.tipo_producto as tipoProcuto, 
		DATE_FORMAT(a.fecha_baja ,'%d/%m/%Y') as FechaBaja, 
		pro.comprador_id as comprador, 
		a.proveedor_id proveedor, 
		pro.nombre as razonSocial, 
		a.marca as marca, 
		a.familia_id as familia, 
		fam1.nombre as nombreFam1,
		fam2.nombre as nombreFam2, 
		fam3.nombre as nombreFam3, 
		NULL as presentacion,
	    stock as stockActual,
		a.coste_medio * stock as valoracion, 
		ifnull(CANSUMCOMP1,0) as COMPRAS_1_AÑO, 
		ifnull(CANSUMCOMP2,0) as COMPRAS_2_AÑOS, 
		NULL as CODIGO_ANTERIOR, 
		NULL as COMPRAS_COD_ANT, 
		ifnull(CANSUMVENT1,0) as VENTAS_1_AÑO, 
		ifnull(CANSUMVENT2,0) as VENTAS_2_AÑOS, 
		
		NULL as VENTAS_COD_ANT, 
		NULL as PRECIO_COSTE,
		NULL as PRECIO_VENTA_SOCIO, 
		a.coste_medio  as PRECIO_MEDIO, 
		NULL as PRECIO_MEDIO_CALCULADO, 
    -- caso1 hay compras 
     CASE 
        WHEN CANSUMCOMP1 != 0 and a.tipo_producto != 'IMP' THEN 'Hay entradas' 
        WHEN CANSUMCOMP2 != 0 and a.tipo_producto = 'IMP' THEN 'Hay entradas' 
    -- caso 2 no hay ventas 
        WHEN ifnull(CANSUMVENT1,0) = 0 and a.tipo_producto != 'IMP' THEN 'No hay ventas: 100%' 
        WHEN ifnull(CANSUMVENT2,0) = 0 and a.tipo_producto = 'IMP' THEN 'No hay ventas: 100%' 
    -- caso 3 compra = 0 -- IMPORTACION 
	    WHEN a.tipo_producto ='IMP' and ifnull(stock/CANSUMVENT2,0) >= 0 and stock/CANSUMVENT2 < 2 THEN 'DE 0.00 a 2.00 = 0' 
	    WHEN a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 2 and stock/CANSUMVENT2 < 3 THEN 'DE 2.00 a 3.00 = 5' 
	    WHEN a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 3 and stock/CANSUMVENT2 < 4 THEN 'DE 3.00 a 4.00 = 10'
 	    WHEN a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 4 and stock/CANSUMVENT2 < 5 THEN 'DE 4.00 a 5.00 = 15' 
 	    WHEN a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 5 and stock/CANSUMVENT2 < 6 THEN 'DE 5.00 a 6.00 = 20'
  	    WHEN a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 6 and stock/CANSUMVENT2 < 7 THEN 'DE 6.00 a 7.00 = 25'
        WHEN a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 7 and stock/CANSUMVENT2 < 8 THEN 'DE 7.00 a 8.00 = 30' 
	    WHEN a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 8 THEN 'MAYOR DE 8'
	 -- NO IMPORTACION
	    WHEN a.tipo_producto !='IMP' and ifnull(stock/CANSUMVENT1,0) >= 0 and stock/CANSUMVENT1 <2 THEN 'DE 0.00 a 2.00 = 0'
	    WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 2 and stock/CANSUMVENT1 <3 THEN 'DE 2.00 a 3.00 = 5'
	    WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 3 and stock/CANSUMVENT1 <4 THEN 'DE 3.00 a 4.00 = 10'
	    WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 4 and stock/CANSUMVENT1 <5 THEN 'DE 4.00 a 5.00 = 15' 
	    WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 5 and stock/CANSUMVENT1 <6 THEN 'DE 5.00 a 6.00 = 20'
	    WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 6 and stock/CANSUMVENT1 <7 THEN 'DE 6.00 a 7.00 = 25' 
	    WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 7 and stock/CANSUMVENT1 <8 THEN 'DE 7.00 a 8.00 = 30'
	    WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 8 THEN 'MAYOR DE 8' 
	END AS COMENTARIO, 
       
       CASE
            WHEN a.tipo_producto != 'IMP' THEN stock/ifnull(CANSUMVENT1,0)
            WHEN a.tipo_producto = 'IMP' THEN  stock/ifnull(CANSUMVENT2,0)
       END as AÑOS_COBERTURA,  
	   CASE 
		   -- caso1 hay compras    
		            WHEN CANSUMCOMP1 !=0    and a.tipo_producto !='IMP' THEN '0'
                    WHEN CANSUMCOMP2 !=0    and a.tipo_producto  = 'IMP' THEN '0'
           -- caso 2  no hay ventas     
                    WHEN ifnull(CANSUMVENT1,0)  = 0    and a.tipo_producto != 'IMP' THEN '100'
                    WHEN ifnull(CANSUMVENT2,0)   = 0    and a.tipo_producto = 'IMP' THEN '100'
           -- caso 3 compra = 0
		        -- IMPORTACION
		        WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 0 and stock/CANSUMVENT2 < 2 THEN  '0'
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 2 and stock/CANSUMVENT2 < 3 THEN  '5'
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 3 and stock/CANSUMVENT2 < 4 THEN  '10'
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 4 and stock/CANSUMVENT2 < 5 THEN  '15'
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 5 and stock/CANSUMVENT2 < 6 THEN  '20'
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 6 and stock/CANSUMVENT2 < 7 THEN  '25'
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 7 and stock/CANSUMVENT2 < 8 THEN  '30'
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 8  THEN '40'
                
                -- NO IMPORTACION
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 0 and stock/CANSUMVENT1 <2 THEN  '0'
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 2 and stock/CANSUMVENT1 <3 THEN  '5'
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 3 and stock/CANSUMVENT1 <4 THEN  '10'
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 4 and stock/CANSUMVENT1 <5 THEN  '15'
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 5 and stock/CANSUMVENT1 <6 THEN  '20'
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 6 and stock/CANSUMVENT1 <7 THEN  '25'
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 7 and stock/CANSUMVENT1 <8 THEN  '30'
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 8  THEN '40'
            
		END as OBSOLETO, 
		
		
		CASE
		  -- caso1 hay compras    
		            WHEN CANSUMCOMP1 != 0    and a.tipo_producto !='IMP' THEN 0
                    WHEN CANSUMCOMP2 != 0    and a.tipo_producto  = 'IMP' THEN 0
           -- caso 2  no hay ventas     
                    WHEN ifnull(CANSUMVENT1,0)  = 0    and a.tipo_producto != 'IMP' THEN  a.coste_medio * stock 
                    WHEN ifnull(CANSUMVENT2,0)  = 0    and a.tipo_producto = 'IMP' THEN  a.coste_medio * stock 
           -- caso 3 compra = 0
		
		        -- IMPORTACION 
		        --  valoracion * %obsolescencia
		        WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 0 and stock/CANSUMVENT2 < 2 THEN  0
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 2 and stock/CANSUMVENT2 < 3 THEN   a.coste_medio * stock  *  5 / 100
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 3 and stock/CANSUMVENT2 < 4 THEN   a.coste_medio * stock  * 10 / 100 
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 4 and stock/CANSUMVENT2 < 5 THEN   a.coste_medio * stock  * 15 / 100
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 5 and stock/CANSUMVENT2 < 6 THEN   a.coste_medio * stock  * 20 / 100
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 6 and stock/CANSUMVENT2 < 7 THEN   a.coste_medio * stock  * 25 / 100
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 7 and stock/CANSUMVENT2 < 8 THEN   a.coste_medio * stock  * 30 / 100
                WHEN  a.tipo_producto ='IMP' and stock/CANSUMVENT2 >= 8 THEN                             a.coste_medio * stock  * 40 / 100
                
                -- NO IMPORTACION
                --  valoracion * %obsolescencia
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 0 and stock/CANSUMVENT1 <2 THEN   0
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 2 and stock/CANSUMVENT1 <3 THEN   a.coste_medio * stock * 5  / 100
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 3 and stock/CANSUMVENT1 <4 THEN   a.coste_medio * stock * 10 / 100
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 4 and stock/CANSUMVENT1 <5 THEN   a.coste_medio * stock * 15 / 100
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 5 and stock/CANSUMVENT1 <6 THEN   a.coste_medio *  stock * 20 / 100
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 6 and stock/CANSUMVENT1 <7 THEN   a.coste_medio * stock * 25 / 100
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 7 and stock/CANSUMVENT1 <8 THEN   a.coste_medio * stock * 30 / 100
                WHEN a.tipo_producto !='IMP' and stock/CANSUMVENT1 >= 8 THEN                            a.coste_medio * stock * 40 / 100
		END as  VALOR_OBSOLESCENCIA, 
		NULL as PFACTOR_CONVERSION 
        FROM articulos a 
		LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMVENT1  from historico_ventas_detalle   v1 LEFT OUTER JOIN articulos a ON v1.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fechaVenta1."".$proveedor."".$articulo." GROUP BY articulo_id ) v1 ON a.id = v1.art 
		LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMCOMP1  from historico_compras_detalle  c1 LEFT OUTER JOIN articulos a ON c1.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fechaCompra1."".$proveedor."".$articulo." GROUP BY articulo_id ) c1 ON a.id = c1.art 
	    LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMVENT2  from historico_ventas_detalle   v2 LEFT OUTER JOIN articulos a ON v2.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fechaVenta2."".$proveedor."".$articulo." GROUP BY articulo_id ) v2 ON a.id = v2.art 
		LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMCOMP2  from historico_compras_detalle  c2 LEFT OUTER JOIN articulos a ON c2.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fechaCompra2."".$proveedor."".$articulo." GROUP BY articulo_id ) c2 ON a.id = c2.art
		LEFT OUTER JOIN (
				select articulo_id, SUM(stock_actual) as  stock   from  articulos_almacen 		  arta LEFT OUTER JOIN articulos a ON arta.articulo_id=a.id GROUP BY articulo_id	
		) arta ON a.id = arta.articulo_id
		LEFT JOIN proveedores pro ON a.proveedor_id = pro.id 
		LEFT JOIN familias fam ON a.familia_id = fam.id 
		LEFT JOIN familias fam1 ON substring(a.familia_id,1,2) = fam1.id 
		LEFT JOIN familias fam2 ON substring(a.familia_id,1,4) = fam2.id 
		LEFT JOIN familias fam3 ON substring(a.familia_id,1,6) = fam3.id 
		LEFT JOIN (SELECT articulo_id ,AVG(mad_stock) as stockMedia 
		            FROM stock_medio sm  
		            LEFT JOIN articulos a ON sm.articulo_id = a.id 
		            WHERE a.fecha_baja is null 
		            ".$proveedor."
		            ".$fechaF." 
		            ".$articulo."
		            GROUP BY articulo_id ) sm ON a.id = sm.articulo_id
		            WHERE a.fecha_baja is null  ".$proveedor."".$articulo." ORDER BY a.id )"));




        $bg = array("B5BF00","808080","B5BF00","808080","3333ff","B5BF00","ec7063");
        $title = "INFORME";
        //LEYENDA
        $fin1 = 8;
        $fin2 = $fin1 + 4;
        $fin3 = $fin2 + 5;
        $fin4 = $fin3 + 4;
        $fin5 = $fin4 + 3;
        $fin6 = $fin5 + 4;
        $fin7 = $fin6 + 5;

        $tramo1 = Coordinate::stringFromColumnIndex(1) . "14:" . Coordinate::stringFromColumnIndex($fin1) . "14";
        $tramo2 = Coordinate::stringFromColumnIndex($fin1 + 1) . "14:" . Coordinate::stringFromColumnIndex($fin2) . "14";
        $tramo3 = Coordinate::stringFromColumnIndex($fin2 + 1) . "14:" . Coordinate::stringFromColumnIndex($fin3) . "14";
        $tramo4 = Coordinate::stringFromColumnIndex($fin3 + 1) . "14:" . Coordinate::stringFromColumnIndex($fin4) . "14";
        $tramo5 = Coordinate::stringFromColumnIndex($fin4 + 1) . "14:" . Coordinate::stringFromColumnIndex($fin5) . "14";
        $tramo6 = Coordinate::stringFromColumnIndex($fin5 + 1) . "14:" . Coordinate::stringFromColumnIndex($fin6) . "14";
        $tramo7 = Coordinate::stringFromColumnIndex($fin6 + 1) . "14:" . Coordinate::stringFromColumnIndex($fin7) . "14";
        $tramos = array($tramo1, $tramo2, $tramo3,$tramo4,$tramo5,$tramo6,$tramo7);

        $precabeceraL = array("CAMPO INFORME", "DESCRIPCION COMENTARIOS");
        $tramo1 = "A2:A" . ($fin1);
        $tramo2 = "A" . ($fin1) . ":A" . ($fin2);
        $tramo3 = "A" . ($fin2) . ":A" . ($fin3);
        $tramo4 = "A" . ($fin3) . ":A" . ($fin4);
        $tramo5 = "A" . ($fin4) . ":A" . ($fin5);
        $tramo6 = "A" . ($fin5) . ":A" . ($fin6);
        $tramo7 = "A" . ($fin6) . ":A" . ($fin7);
        //$tramosLeyenda = array($tramo1, $tramo2, $tramo3, $tramo4, $tramo5, $tramo6, $tramo7);
        $titleL = "LEYENDA";
        $comentarios = array(
            "Codigo de articulo",
            "Descripcion del articulo",
            "Fecha de alta",
            "Fecha de baja",
            "Tipo de articulo (NAC,UE o IMP)",
            "Tipo Rotacion (Este campo es un campo de Navision, que no sé si alguien lo actualiza)",
            "Proveedor",
            "Razon social del proveedor",
            "Referencia del articulo",
            "Comprador asociado al proveedor del articulo",
            "Marca asociada al articulo",
            "Esta en el surtido basico de ferrokey",
            "Precio medio de la ficha del articulo en Navision",
            "Familia",
            "Descripcion completa de la familia",
            "Nivel 1 de la familia",
            "Descripcion del nivel 1 de la familia",
            "Nivel 2 de la familia",
            "Descripcion del nivel 2 de la familia",
            "Nivel 3 de la familia",
            "Descripcion del nivel 3 de la familia",
            "¿Esta a extinguir?",
            "Unidades vendidas",
            "Importe de las unidades",
            "Importe de las unidades vendidas (valoradas al coste)",
            "Stock a la fecha que se genera el informe",
            "La suma de los importes de la ventas menos el coste de estas ventas.",
            "Stock medio del producto (En unidades)",
            "Son las unidades vendidas divididas por el stock medio",
            "Es la division del margen bruto, por el indice de rotacion",
            "¿Tiene marcado la casilla surtido de alicante?"
        );




        $cabeceraL = array();
        $page1 = new Sheet($precabecera, $data, $cabecera, $bg, $title, $tramos);
        $page2=null;
        //$page2 = new SheetLeyenda($precabeceraL, $array, $precabeceraL, $bg, $titleL, $tramosLeyenda);

        // Envio del mail
        if ((!is_null($request["email"])) && ($request["enviaMail"] == true)) {
            set_time_limit(20000);
            //generacion del zip
            $zip = new ZipArchive;
            if ($zip->open($filename.'.zip', ZipArchive::CREATE) === true) {
                $zip->addFile(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(),
                    $filename.'.xls');
                $zip->close();
            }

            if (is_null($request["asunto"])) {
                $messageBody = "Informe de Indice de rotacion";
            } else {
                $messageBody = $request["asunto"];
            }
            $email = $request["email"];
            $message = "Este mail contiene el informe de rotacion";
            Mail::raw(/**
             * @param $message
             */
                $messageBody, function ($message) use ($filename, $page2, $compresion, $email, $page1) {
                $message->from('rvalle@comafe.es', 'Informe de Indice de rotación');
                $message->to($email);
                $message->subject('indice de rotacion');

                if ($compresion== true) {
                    set_time_limit(20000);
                    $message->attach(response()->download($filename.".zip")->getFile(), ['as' => 'report.zip']);
                    return redirect()->back();
                }else{
                    set_time_limit(20000);
                    $message->attach(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(), ['as' => 'report.xls']);
                    return redirect()->back();
                }
            });

        }

        if (($compresion == true)&&($request["enviaMail"] == false)) {
            //generacion del zip
            $zip = new ZipArchive;
            if ($zip->open($filename.'.zip', ZipArchive::CREATE) === true) {
                $zip->addFile(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(),
                    $filename.'.xls');
                $zip->close();
            }
            return response()->download($filename.".zip");
        }


        if ($request["type"] == "xls") {
            set_time_limit(20000);
            return (Excel::download(new SheetsExports($page1, $page2), $filename . '.xls'));
        }
        if ($request["type"] == "csv") {
            set_time_limit(20000);
            //return(Excel::download(new SheetsExports($page1, $page2), $filename . '.csv'));
            //parametrizacion
            $cabecera = "ARTICULO;DESCRIPCION;F.ALTA;F.BAJA;TIPO ART.;TIPO ROT.;PROVEEDOR;RAZON SOCIAL;REFERENCIA;COMPRADOR;MARCA;CAT.FERROKEY;PRECIO MEDIO;FAMILIA;DESC COMPLETA;FAM-1-;DESCRIPCION;FAM-2-;DESCRIPCION;FAM-3-;DESCRIPCION;DATOS ALMACEN EXTINGUIR;VENTAS (UDS);VENTAS (PVP);VENTAS(PMEDIO);STOCK ACTUAL;MARGEN BRUTO;STOCK MEDIO (UDS);INDICE ROTACON;MARGEN POR ROTACION;SURTIDO";
            $cabeza = date("d-m-Y h:i:sa") . "\n Indice De Rotacion \n PERIODO, " . $fechaDesde . " a " . $fechaHasta .  " \n PROVEEDOR " . $proveedor_id . " \nLISTAS ARTICULOS ENVASES";
            $array = $cabeza . "\n\n" . $cabecera . "\n";
            foreach ($data as $list) {
                foreach ($list as $dat) {
                    $array = $array . $dat . ";";
                }
                $array = $array . "\n";
            }
            return response()->attachmentCSV($array, $filename.".csv");
        }
    }








    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////// MARCA PROPIA ///////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    /*
     * select clientes.nombre
    from historico_ventas_detalle as hist
    INNER JOIN clientes  ON   clientes.cliente= hist.cliente_id
    limit 100;
     * */


    public function actionMarcaPropia(Request $request)
    {


        //VARIABLES
        $tipo = $request["opcion"];

        $tipoCliente = $request["tipoCliente"];
        if($tipoCliente==" "){
            $tipoCliente="Todos";
        }

        $tipoGrupoCliente = $request["tipoGrupoCliente"];
        if($tipoGrupoCliente==" "){
            $tipoGrupoCliente="Todos";
        }

        $codigoCliente = $request["codigoCliente"];
        if(empty($codigoCliente)){
            $codigoCliente="Todos";
        }


        $codigoArticulo = $request["codigoArticulo"];
        if($codigoArticulo==" "){
            $codigoArticulo="Todos";
        }

        $fechaDesde = $request["fechaDesde"];
        $fechaHasta = $request["fechaHasta"];

        $compresion=$request["compresion"];





        $precabecera = array(
            array(date("F j, Y, g:i a")),
            //  array(date("H:i:s")),
            array("Marca propia por cliente"),
            array(""),
            array("*PERIODO", $fechaDesde, "a", $fechaHasta),
            array("*PERIODO ANTERIOR", date('Y-m-d',strtotime($fechaDesde.'-1 year')), "a",  date('Y-m-d',strtotime($fechaHasta.'-1 year'))),
            array("*TIPO CLIENTE", $tipoGrupoCliente),
            array("*CODIGO CLIENTE",$codigoCliente),
            array(" ")
        );

        /******************************
        SI LA OPCION ES CLIENTE
         *****************************/

        if ($tipo=="CLIENTE") {

            $codigoCliente="";
            $codigoClienteInner="";
            if(! is_null($request["codigoCliente"])){
                $codigoClienteInner=" AND cab.cliente_id ='".$request["codigoCliente"]."'";
                $codigoCliente=" AND c.cliente ='".$request["codigoCliente"]."'";
            }



            $db = DB::connection('reporting');
            $cabecera = array(
                "EMPRESA",
                "Nº CLIENTE",
                "SUCURSAL",
                "NOMBRE CLIENTE",
                "VENTAS TOTALES A ".$fechaHasta." (€)",
                "VENTAS TOTALES A ".date('d/m/Y',strtotime($fechaHasta.'-1 year'))." (€)",
                "DIF ".$fechaHasta."/".date('d/m/Y',strtotime($fechaHasta.'-1 year'))." (%)",
                "VENTAS MARCA PROPIA A ".$fechaHasta." (€).",
                "VENTAS MARCA PROPIA A ".date('d/m/Y',strtotime($fechaHasta.'-1 year'))."(€)",
                "DIF 19/18 (%)"
            );


            $tipoGrupoClienteInner=" AND cl.tipo_cliente ='".$request["tipoGrupoCliente"]."'";
            $tipoGrupoCliente=" AND c.tipo_cliente ='".$request["tipoGrupoCliente"]."'";
            $fechaActual    = " AND (cab.fecha BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "')";
            $fechaAnterior  = " AND (cab.fecha BETWEEN '" . date('Y-m-d',strtotime($fechaDesde.'-1 year'))."' AND '".date('Y-m-d',strtotime($fechaHasta.'-1 year'))."')";
            $data = $db->select($db->raw("(
            SELECT c.empresa, c.cliente, c.sucursal, c.nombre
            , IFNULL(ventasact.TOTAL,0) Almacen
            , IFNULL(v_alm_ant.TOTAL,0) AlmacenAnterior
            , ROUND (CASE WHEN iFNULL(v_alm_ant.TOTAL,0) <> 0 THEN ((IFNULL(ventasact.TOTAL,0) -  IFNULL(v_alm_ant.TOTAL,0)) / iFNULL(v_alm_ant.TOTAL,0))*100 ELSE 0 END,2)'Diferencia_almacen (%)'
            , IFNULL(v_mp.TOTAL,0) MarcaPropia
            , IFNULL(v_mp_ant.TOTAL,0) MarcaPropiaAnterior
            , ROUND (CASE WHEN iFNULL(v_mp_ant.TOTAL,0) <> 0 THEN ((IFNULL(v_mp.TOTAL,0) -  IFNULL(v_mp_ant.TOTAL,0)) / iFNULL(v_mp_ant.TOTAL,0))*100 ELSE 0 END,2)'Diferencia_mpropia (%)'
            FROM clientes c
            LEFT OUTER JOIN (
            SELECT
            cab.empresa EMP, cab.cliente_id CLI, cab.sucursal_id SUC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE 0 END) TOTABO
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.importe ELSE 0 END) TOT_FAC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE det.importe END) TOTAL
            FROM historico_ventas_detalle det
            INNER JOIN historico_ventas cab ON (det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento)
            LEFT OUTER JOIN clientes cl ON (cab.empresa = cl.empresa AND cab.cliente_id = cl.cliente AND cab.sucursal_id = cl.sucursal)
            LEFT OUTER JOIN articulos art ON (det.articulo_id = art.id)
            LEFT OUTER JOIN proveedores pro ON (art.proveedor_id = pro.id)
            WHERE (cab.empresa = 1 ".$tipoGrupoClienteInner.$codigoClienteInner.")
            ".$fechaActual."
            GROUP BY cab.empresa, cab.cliente_id, cab.sucursal_id
            ) ventasact ON c.empresa = ventasact.EMP AND c.cliente = ventasact.CLI AND c.sucursal = ventasact.SUC
            LEFT OUTER JOIN (
            SELECT cab.empresa EMP, cab.cliente_id CLI, cab.sucursal_id SUC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE 0 END) TOTABO
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.importe ELSE 0 END) TOT_FAC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE det.importe END) TOTAL
            FROM historico_ventas_detalle det
            INNER JOIN historico_ventas cab ON (det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento)
            LEFT OUTER JOIN clientes cl ON (cab.empresa = cl.empresa AND cab.cliente_id = cl.cliente AND cab.sucursal_id = cl.sucursal)
            LEFT OUTER JOIN articulos art ON (det.articulo_id = art.id)
            LEFT OUTER JOIN proveedores pro ON (art.proveedor_id = pro.id)
              WHERE (cab.empresa = 1 ".$tipoGrupoClienteInner.$codigoClienteInner.")
              ".$fechaActual."
            AND (art.es_marca_propia = 1 OR pro.es_marca_propia=1)
            GROUP BY cab.empresa, cab.cliente_id, cab.sucursal_id
            ) v_mp ON c.empresa = v_mp.EMP AND c.cliente = v_mp.CLI AND c.sucursal = v_mp.SUC
            LEFT OUTER JOIN (
            SELECT cab.empresa EMP, cab.cliente_id CLI, cab.sucursal_id SUC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE 0 END) TOTABO
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.importe ELSE 0 END) TOT_FAC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE det.importe END) TOTAL
            FROM historico_ventas_detalle det
            INNER JOIN historico_ventas cab ON (det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento)
            LEFT OUTER JOIN clientes cl ON (cab.empresa = cl.empresa AND cab.cliente_id = cl.cliente AND cab.sucursal_id = cl.sucursal)
            LEFT OUTER JOIN articulos art ON (det.articulo_id = art.id)
            LEFT OUTER JOIN proveedores pro ON (art.proveedor_id = pro.id)
             WHERE (cab.empresa = 1 ".$tipoGrupoClienteInner.$codigoClienteInner.")
                ".$fechaAnterior."
            GROUP BY cab.empresa, cab.cliente_id, cab.sucursal_id
            ) v_alm_ant ON c.empresa = v_alm_ant.EMP AND c.cliente = v_alm_ant.CLI AND c.sucursal = v_alm_ant.SUC
            LEFT OUTER JOIN (
            SELECT cab.empresa EMP, cab.cliente_id CLI, cab.sucursal_id SUC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE 0 END) TOTABO
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.importe ELSE 0 END) TOT_FAC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.importe*-1 ELSE det.importe END) TOTAL
            FROM historico_ventas_detalle det
            INNER JOIN historico_ventas cab ON (det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento)
            LEFT OUTER JOIN clientes cl ON (cab.empresa = cl.empresa AND cab.cliente_id = cl.cliente AND cab.sucursal_id = cl.sucursal)
            LEFT OUTER JOIN articulos art ON (det.articulo_id = art.id)
            LEFT OUTER JOIN proveedores pro ON (art.proveedor_id = pro.id)
             WHERE (cab.empresa = 1 ".$tipoGrupoClienteInner.$codigoClienteInner.")
            ".$fechaAnterior."
            AND (art.es_marca_propia = 1 OR pro.es_marca_propia=1)
            GROUP BY cab.empresa, cab.cliente_id, cab.sucursal_id
            ) v_mp_ant ON c.empresa = v_mp_ant.EMP AND c.cliente = v_mp_ant.CLI AND c.sucursal = v_mp_ant.SUC
            WHERE (c.empresa = 1 ".$tipoGrupoCliente.$codigoCliente.")
             )"));

            $bg = "b5bf00";
            $title = "INFORME DE VENTAS POR CLIENTE";
            $fin1 = 10;
            $tramo = Coordinate::stringFromColumnIndex(1) . "9:" . Coordinate::stringFromColumnIndex($fin1) . "9";

            $columnFormats = [
                "E" => NumberFormat::FORMAT_CURRENCY_EUR,
                "F" => NumberFormat::FORMAT_CURRENCY_EUR,
                "G" => NumberFormat::FORMAT_PERCENTAGE_00,
                "H" => NumberFormat::FORMAT_CURRENCY_EUR,
                "I" => NumberFormat::FORMAT_CURRENCY_EUR,
                "J" => NumberFormat::FORMAT_PERCENTAGE_00
            ];
            $filename = "marcaPropia_por_Cliente";
        }
        /******************************
        SI LA OPCION ES ARTICULO
         ********************************/


        if ($tipo=="ARTICULOS") {


            $db = DB::connection('reporting');
            $cabecera = array(
                "N ARTICULO",
                "DESCRIPCIÓN ARTÍCULO (SÓLO MARCA PROPIA)",
                "VENTAS TOTALES A ".$fechaHasta."(UDS)",
                "VENTAS TOTALES A ".$fechaHasta."(€)",
                "PRECIO VENTA MEDIO ".$fechaHasta."(€)",
                "VENTAS TOTALES A  ".date('d/m/Y',strtotime($fechaHasta.'-1 year'))."(UDS)",
                "VENTAS TOTALES A  ".date('d/m/Y',strtotime($fechaHasta.'-1 year'))."(€)",
                "DIF (UDS)".$fechaHasta.date('d/m/Y',strtotime($fechaHasta.'-1 year'))."(%)",
                "DIF(€)".$fechaHasta.date('d/m/Y',strtotime($fechaHasta.'-1 year')),
                "ROTACIÓN DIARÍA (UDS VENDIDAS ".$fechaHasta." /  181 DÍAS"

            );
            $codigoArticulo="";
            $codigoArticuloInner="";
            if(! is_null($request["codigoArticulo"])){
                $codigoArticuloInner=" AND det.articulo_id ='".$request["codigoArticulo"]."'";
                $codigoArticulo=" AND a.id ='".$request["codigoArticulo"]."'";
            }
            $fechaActual    = " AND (cab.fecha BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "')";
            $fechaAnterior  = "AND (cab.fecha BETWEEN '" . date('Y-m-d',strtotime($fechaDesde.'-1 year'))."' AND '".date('Y-m-d',strtotime($fechaHasta.'-1 year'))."')";
            $tipoGrupoClienteInner=" AND cl.tipo_cliente ='".$request["tipoGrupoCliente"]."'";
            // $tipoGrupoCliente=" AND c.tipo_cliente ='".$request["tipoGrupoCliente"]."'";

            $data = $db->select($db->raw("(SELECT a.id, a.nombre
            , IFNULL(Almacen.TOTAL_UDS,0) AlmacenUds
            , IFNULL(Almacen.TOTAL_PREC,0) AlmacenPrec
            , IFNULL(IFNULL(Almacen.TOTAL_PREC,0)/IFNULL(Almacen.TOTAL_UDS,0),0) PrecioMedio
            , IFNULL(AlmacenAnterior.TOTAL_UDS,0) AlmacenAnterior
            , IFNULL(AlmacenAnterior.TOTAL_PREC,0) AlmacenAnteriorPrec
            , (IFNULL(Almacen.TOTAL_UDS,0) DIV IFNULL(AlmacenAnterior.TOTAL_UDS,0)) -1  dif_Anual_almacenUDS  
            , (IFNULL(Almacen.TOTAL_PREC,0) DIV IFNULL(AlmacenAnterior.TOTAL_PREC,0)) -1 dif_Anual_almaceNPREC 
            , CASE WHEN DATEDIFF('".$fechaHasta."','".$fechaDesde."') <> 0 THEN IFNULL(Almacen.TOTAL_UDS,0) / (DATEDIFF('".$fechaHasta."','".$fechaDesde."')) ELSE 0 END Rotacion  
            FROM articulos a
            LEFT OUTER JOIN proveedores p ON a.proveedor_id = p.id
            LEFT OUTER JOIN (
            SELECT det.articulo_id ART
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.cantidad *-1 ELSE 0 END) TOTABO_UDS
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.cantidad ELSE 0 END) TOT_FAC_UDS
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.cantidad*-1 ELSE det.cantidad END) TOTAL_UDS
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.precio *-1 ELSE 0 END) TOTABO_PREC
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.precio ELSE 0 END) TOT_FAC_PREC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.precio*-1 ELSE det.precio END) TOTAL_PREC
            FROM historico_ventas_detalle det
            INNER JOIN historico_ventas cab ON (det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento)
            LEFT OUTER JOIN clientes cl ON (cab.empresa = cl.empresa AND cab.cliente_id = cl.cliente AND cab.sucursal_id = cl.sucursal)
            LEFT OUTER JOIN articulos art ON (det.articulo_id = art.id)
            LEFT OUTER JOIN proveedores pro ON (art.proveedor_id = pro.id)
            WHERE (cab.empresa = 1 ".$tipoGrupoClienteInner.")
            ".$fechaActual."
            ".$codigoArticuloInner."
            AND (art.es_marca_propia = 1 OR pro.es_marca_propia=1)
            GROUP BY det.articulo_id
            ) Almacen ON a.id = Almacen.ART
            
            LEFT OUTER JOIN (
            SELECT det.articulo_id ART
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.cantidad *-1 ELSE 0 END) TOTABO_UDS
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.cantidad ELSE 0 END) TOT_FAC_UDS
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.cantidad*-1 ELSE det.cantidad END) TOTAL_UDS
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.precio *-1 ELSE 0 END) TOTABO_PREC
            , SUM(CASE WHEN det.tipo_documento='F' THEN  det.precio ELSE 0 END) TOT_FAC_PREC
            , SUM(CASE WHEN det.tipo_documento='A' THEN  det.precio*-1 ELSE det.precio END) TOTAL_PREC
            FROM historico_ventas_detalle det
            INNER JOIN historico_ventas cab ON (det.empresa = cab.empresa AND det.tipo_documento = cab.tipo_documento AND det.documento = cab.documento)
            LEFT OUTER JOIN clientes cl ON (cab.empresa = cl.empresa AND cab.cliente_id = cl.cliente AND cab.sucursal_id = cl.sucursal)
            LEFT OUTER JOIN articulos art ON (det.articulo_id = art.id)
            LEFT OUTER JOIN proveedores pro ON (art.proveedor_id = pro.id)
           
            WHERE (cab.empresa = 1  ".$tipoGrupoClienteInner.")
            ".$fechaAnterior."
            ".$codigoArticuloInner."
            AND (art.es_marca_propia = 1 OR pro.es_marca_propia=1)
            GROUP BY det.articulo_id
            ) AlmacenAnterior ON a.id = AlmacenAnterior.ART
            
            WHERE (a.es_marca_propia = 1 OR p.es_marca_propia=1)
            ".$codigoArticulo."
            ORDER BY a.proveedor_id, a.nombre)
            "));
            $bg = "b5bf00";
            $title = "INFORME DE VENTAS POR ARTICULOS";
            //LEYENDA
            $fin1 = 10;
            $tramo = Coordinate::stringFromColumnIndex(1)."9:".Coordinate::stringFromColumnIndex($fin1)."9";
            $columnFormats = [
                "C" => NumberFormat::FORMAT_NUMBER,
                "D" => NumberFormat::FORMAT_CURRENCY_EUR,
                "E" => NumberFormat::FORMAT_CURRENCY_EUR,
                "F" => NumberFormat::FORMAT_NUMBER,
                "G" => NumberFormat::FORMAT_CURRENCY_EUR,
                "H" => NumberFormat::FORMAT_PERCENTAGE,
                "I" => NumberFormat::FORMAT_PERCENTAGE,
                "J" => NumberFormat::FORMAT_NUMBER

            ];
            $filename = "marcaPropia_Por_Articulo";
        }



        $i = 0;
        foreach ($cabecera as $cab) {
            $array[$i][1] = $cab;
            $i++;
        }


        $page1 = new Sheet2($precabecera, $data, $cabecera, $bg, $title, $tramo,$columnFormats);
        $page2 = null;

        // Envio del mail
        if ((!is_null($request["email"])) && ($request["enviaMail"] == true)) {
            set_time_limit(20000);
            //generacion del zip
            $zip = new ZipArchive;
            if ($zip->open($filename.'.zip', ZipArchive::CREATE) === true) {
                $zip->addFile(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(),
                    $filename.'.xls');
                $zip->close();
            }

            if (is_null($request["asunto"])) {
                $messageBody = "Informe de Indice de rotacion";
            } else {
                $messageBody = $request["asunto"];
            }
            $email = $request["email"];
            $message = "Este mail contiene el informe de rotacion";
            Mail::raw(/**
             * @param $message
             */
                $messageBody, function ($message) use ($filename, $page2, $compresion, $email, $page1) {
                $message->from('rvalle@comafe.es', 'Informe de Indice de rotación');
                $message->to($email);
                $message->subject('indice de rotacion');

                if ($compresion== true) {
                    set_time_limit(20000);
                    $message->attach(response()->download($filename.".zip")->getFile(), ['as' => 'report.zip']);
                    return redirect()->back();
                }else{
                    set_time_limit(20000);
                    $message->attach(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(), ['as' => 'report.xls']);
                    return redirect()->back();
                }
            });

        }

        if (($compresion == true)&&($request["enviaMail"] == false)) {
            //generacion del zip
            $zip = new ZipArchive;
            if ($zip->open($filename.'.zip', ZipArchive::CREATE) === true) {
                $zip->addFile(Excel::download(new SheetsExports($page1, $page2), $filename . '.xls')->getFile(),
                    $filename.'.xls');
                $zip->close();
            }
            return response()->download($filename.".zip");
        }


        if ($request["type"] == "xls") {
            set_time_limit(20000);
            return (Excel::download(new SheetsExports($page1, $page2), $filename . '.xls'));
        }
        if ($request["type"] == "csv") {
            set_time_limit(20000);

            //parametrizacion

            $cabeza=null;
           if ($tipo=="CLIENTE") {
                $cabecera = "EMPRESA;N CLIENTE;SUCURSAL;NOMBRE CLIENTE;VENTAS TOTALES A".$fechaHasta." €);VENTAS TOTALES A.".$fechaHasta.'-1 year'."(€);.$fechaHasta.'-1 year(%);VENTAS MARCA PROPIA A ".$fechaHasta." (€);VENTAS MARCA PROPIA A ".$fechaHasta.'-1 year'."(€);DIF 19/18 (%)";
            }

            if ($tipo=="ARTICULOS"){
                $cabecera ="N ARTICULO;DESCRIPCIÓN ARTÍCULO (SóLO MARCA PROPIA); VENTAS TOTALES A ".$fechaHasta."(UDS); VENTAS TOTALES A  ".$fechaHasta."-1 year(UDS);".$fechaHasta.$fechaHasta."(%);ROTACIÓN DIARÍA (UDS VENDIDAS A 30.06.19 / 181 DÍAS";
            }


            $array = $cabeza . "\n\n" . $cabecera . "\n";
            foreach ($data as $list) {
                foreach ($list as $dat) {
                    $array=$array.$dat.";";
                }
                $array = $array . "\n";
            }
            return response()->attachmentCSV($array, $filename.".csv");
        }
    }








}