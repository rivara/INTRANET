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
    /////////////////////////////////// OBSOLETOS  ///////////////////////////////////////////////////
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




        //INFORME
        $precabecera = array(
            array(date("F j, Y, g:i a")),
            array("Obsoletos"),
            array(""),
            array("*PERIODO ANALIZADO", $fechaDesde, "a", $fechaHasta),
            array("*PERIODO ANALIZADO PARA ARTICULO IMP:", $fechaDesde, "a", $fechaHasta),
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





        $date = strtotime($fechaDesde.'-1 year');
        $fechaDesdeHace2años= date('Y-m-d', $date);


        $fecha1 = "AND v1.fecha  BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "'";
        $fecha2 = "AND c1.fecha  BETWEEN '" . $fechaDesdeHace2años . "' AND '" . $fechaHasta . "'";
        $fecha3 = "AND v2.fecha  BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "'";
        $fecha4 = "AND c2.fecha  BETWEEN '" . $fechaDesdeHace2años . "' AND '" . $fechaHasta . "'";
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
		CASE WHEN CANSUMCOMP1 < 0    and a.tipo_producto='NAC' THEN 'No hay ventas. Det: 100'
             WHEN CANSUMCOMP1 < 0    and a.tipo_producto!='NAC' THEN 'No hay ventas. Det: 100'
         -- caso 2   no hay compras  
            WHEN CANSUMVENT1  < 0       and a.tipo_producto='NAC' THEN '100% obsolescencia'
            WHEN CANSUMVENT2  < 0       and a.tipo_producto!='NAC' THEN '100% obsolescencia'
        -- caso 3 stock = 0
            WHEN CANSUMCOMP1 > 0 and  CANSUMVENT1  > 0  and a.tipo_producto='NAC' THEN  stock/CANSUMVENT1
            WHEN CANSUMCOMP2 > 0 and  CANSUMVENT2  > 0 and a.tipo_producto!='NAC' THEN  stock/CANSUMVENT2
            
       END AS COMENTARIO,
		NULL as AÑOS_COBERTURA, 
		NULL as OBSOLETO, 
		NULL as VALOR_OBSOLESCENCIA, 
		NULL as PFACTOR_CONVERSION 
 FROM articulos a 
		LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMVENT1  from historico_ventas_detalle   v1 LEFT OUTER JOIN articulos a ON v1.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fecha1."".$proveedor."".$articulo." GROUP BY articulo_id ) v1 ON a.id = v1.art 
		LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMCOMP1  from historico_compras_detalle  c1 LEFT OUTER JOIN articulos a ON c1.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fecha2."".$proveedor."".$articulo." GROUP BY articulo_id ) c1 ON a.id = c1.art 
	    LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMVENT2  from historico_ventas_detalle   v2 LEFT OUTER JOIN articulos a ON v2.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fecha3."".$proveedor."".$articulo." GROUP BY articulo_id ) v2 ON a.id = v2.art 
		LEFT OUTER JOIN (select articulo_id art,SUM(cantidad) as CANSUMCOMP2  from historico_compras_detalle  c2 LEFT OUTER JOIN articulos a ON c2.articulo_id = a.id WHERE empresa=1 AND es_directo=0 AND a.fecha_baja is null ".$fecha4."".$proveedor."".$articulo." GROUP BY articulo_id ) c2 ON a.id = c2.art
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



//a

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


}