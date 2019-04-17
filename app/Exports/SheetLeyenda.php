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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class SheetLeyenda implements FromCollection, WithHeadings, WithEvents, WithTitle
{

    protected $precabecera;
    protected $cabecera;
    protected $data;
    protected $background;
    protected $pagename;
    protected $tramos;

    public function __construct($precabecera, $data, $cabecera, $background, $pagename, $tramos)
    {
        $this->precabecera = $precabecera;
        $this->cabecera = $cabecera;
        $this->data = $data;
        $this->background = $background;
        $this->pagename = $pagename;
        $this->tramos = $tramos;
    }


    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->data;
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                //TRAMOS
                $event->sheet->getDelegate()->getStyle("A1:C1")->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle("A1:C1")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A1:C1")->getFont()->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle("A1:C1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB("373737");


                $i = 0;
                foreach ($this->tramos as $tramo) {
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setSize(12);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setBold(true);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->getColor()->setRGB('ffffff');
                    $event->sheet->getDelegate()->getStyle($tramo)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($this->background[$i]);
                    $i++;
                }
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
        return $this->pagename;
    }

}