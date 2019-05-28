<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 15/04/2019
 * Time: 9:35
 */

namespace App\Exports;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithProgressBar;


/**
 * @property  output
 */
class SheetLeyenda implements FromCollection, WithHeadings, WithEvents, WithTitle
{

    protected $precabecera;
    protected $cabecera;
    protected $data;
    protected $background;
    protected $pagename;
    protected $tramos;
    protected $columna_roja;

    public function __construct($precabecera, $data, $cabecera, $background, $title)
    {
        $this->precabecera = $precabecera;
        $this->cabecera = $cabecera;
        $this->data = $data;
        $this->background = $background;
        $this->title = $title;

    }


    /**
     * @return Collection
     */
    public function collection()
    {

        $a= collect($this->data);

        return $a;
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                //TRAMOS
                //GRIS
                $event->sheet->getDelegate()->getStyle("A2")->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle("A2")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A2")->getFont()->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle("A2")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($this->background[0]);
                //AZUL
                $event->sheet->getDelegate()->getStyle("A3")->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle("A3")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A3")->getFont()->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle("A3")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($this->background[1]);
                //VERDE
                $event->sheet->getDelegate()->getStyle("A4")->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle("A4")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A4")->getFont()->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle("A4")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($this->background[2]);


            }
        ];
    }



    /**
     * @return array
     */
    public function headings(): array
    {
        return [$this->cabecera];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }







}