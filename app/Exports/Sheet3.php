<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 15/04/2019
 * Time: 9:35
 */

namespace App\Exports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


/**
 * @property  output
 * @property  columnFormats
 */



class Sheet3 implements FromCollection, WithHeadings, WithEvents, WithTitle
{

    protected $precabecera;
    protected $cabecera;
    protected $data;
    protected $background;
    protected $pagename;
    protected $tramo;
    protected $columnFormats;

    public function __construct($precabecera, $data, $cabecera, $format, $title)
    {
        $this->precabecera = $precabecera;
        $this->cabecera = $cabecera;
        $this->data = $data;
        $this->format = $format;
        $this->title = $title;



    }


    /**
     * @return Collection
     */
    public function collection()
    {

        $a = collect($this->data);

        return $a;
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        $background = $this->background;

        return [

            BeforeSheet::class => function (BeforeSheet $event) {

                $event->sheet->append($this->precabecera);
                //$event->sheet->fromArray($this->data, null, 'A1', true);
                $event->sheet->getDelegate()->getStyle("A1")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A2")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A3")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A4")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A5")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A6")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A7")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A8")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A9")->getFont()->setBold(true);

            },
            AfterSheet::class => function (AfterSheet $event) use ($background) {

                // COMBINAR Y CENTRAR CELDAS
                //primer nivel
                $event->sheet->mergeCells('H11:X11');
                $event->sheet->getDelegate()->getStyle('H11:X11')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('Z11:AP11');
                $event->sheet->getDelegate()->getStyle('Z11:AP11')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AR11:AV11');
                $event->sheet->getDelegate()->getStyle('AR11:AU11')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                //segundo nivel
                $event->sheet->mergeCells('H12:K12');
                $event->sheet->getDelegate()->getStyle('H12:K12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('L12:O12');
                $event->sheet->getDelegate()->getStyle('L12:O12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('P12:S12');
                $event->sheet->getDelegate()->getStyle('P12:S12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('U12:V12');
                $event->sheet->getDelegate()->getStyle('U12:V12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('Z12:AC12');
                $event->sheet->getDelegate()->getStyle('Z12:AC12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AD12:AG12');
                $event->sheet->getDelegate()->getStyle('AD12:AG12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AH12:AK12');
                $event->sheet->getDelegate()->getStyle('AH12:AK12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AM12:AN12');
                $event->sheet->getDelegate()->getStyle('AM12:AN12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AR12:AV12');
                $event->sheet->getDelegate()->getStyle('AR12:AU12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                //tercer nivel
                $event->sheet->mergeCells('H13:I13');
                $event->sheet->getDelegate()->getStyle('H13:I13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('J13:K13');
                $event->sheet->getDelegate()->getStyle('J13:K13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('L13:M13');
                $event->sheet->getDelegate()->getStyle('L13:M13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('N13:O13');
                $event->sheet->getDelegate()->getStyle('N13:O13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('P13:Q13');
                $event->sheet->getDelegate()->getStyle('P13:Q13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('R13:S13');
                $event->sheet->getDelegate()->getStyle('R13:S13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('Z13:AA13');
                $event->sheet->getDelegate()->getStyle('Z13:AA13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AB13:AC13');
                $event->sheet->getDelegate()->getStyle('AB13:AC13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AD13:AE13');
                $event->sheet->getDelegate()->getStyle('AD13:AE13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AF13:AG13');
                $event->sheet->getDelegate()->getStyle('AF13:AG13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AH13:AI13');
                $event->sheet->getDelegate()->getStyle('AH13:AI13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('AJ13:AK13');
                $event->sheet->getDelegate()->getStyle('AJ13:AK13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                //cuarto nivel

                for($i=0 ; $i<4 ; $i++){
                       for($x=0 ; $x< count($this->format["tramo"][$i]) ; $x++) {

                             $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFont()->setSize(11);
                             if($i==0) {
                                 $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFont()->getColor()->setRGB('ffffff');
                             }else{
                                 $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFont()->getColor()->setRGB('000000');
                             }
                             $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($this->format["bgs"][$i][$x]);
                       }
                   }


              // MODIFICO TOTALES Y SEGUIMIENMTO
                //NIVEL 1
                $event->sheet->getDelegate()->getStyle("AR11")->getFont()->getColor()->setRGB('fa3208');
                //NIVEL 2
                $event->sheet->getDelegate()->getStyle("T12")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("W12")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("X12")->getFont()->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle("AL12")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("AO12")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("AP12")->getFont()->getColor()->setRGB('ffffff');
                //NIVEL 3
                $event->sheet->getDelegate()->getStyle("T13")->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle("T13")->getFont()->getColor()->setRGB('fa3208');
                //NIVEL 4
                $event->sheet->getDelegate()->getStyle("AR14:AV14")->getFont()->getColor()->setRGB('fa3208');
                $event->sheet->getDelegate()->getStyle("T14")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("W14")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("AL14")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("AO14")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("AQ14")->getFont()->getColor()->setRGB('fa3208');
                $event->sheet->getDelegate()->getStyle("AR14")->getFont()->getColor()->setRGB('fa3208');
                $event->sheet->getDelegate()->getStyle("AS14")->getFont()->getColor()->setRGB('fa3208');
                $event->sheet->getDelegate()->getStyle("AT14")->getFont()->getColor()->setRGB('fa3208');
                $event->sheet->getDelegate()->getStyle("AU14")->getFont()->getColor()->setRGB('fa3208');





              /*  foreach ($this->tramos as $tramo) {
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setSize(12);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setBold(true);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->getColor()->setRGB('ffffff');
                    $event->sheet->getDelegate()->getStyle($tramo)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($background[$i]);
                    $i++;
                }*/

                /*$len=strlen($tramo);
                $event->sheet->getDelegate()->getStyle("A1:".substr($tramo,$len-4 , 2)."1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->
                getStartColor()->setRGB($background[2]);*/

            },
        ];

    }









    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->cabecera;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }


}