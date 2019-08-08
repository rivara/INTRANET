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

        return array(

            BeforeSheet::class => function (BeforeSheet $event) {

                $event->sheet->append($this->precabecera);
                //$event->sheet->fromArray($this->data, null, 'A1', true);
                $event->sheet->getDelegate()->getStyle("A1")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A3")->getFont()->setBold(true);

            },
            AfterSheet::class => function (AfterSheet $event) use ($background) {






                   for($i=0 ; $i<4 ; $i++){
                       for($x=0 ; $x< count($this->format["tramo"][$i]) ; $x++) {

                             $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFont()->setSize(12);
                             $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFont()->setBold(true);
                             $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFont()->getColor()->setRGB('ffffff');
                             $event->sheet->getDelegate()->getStyle($this->format["tramo"][$i][$x])->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($this->format["bgs"][$i][$x]);
                       }
                   }






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
        );

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