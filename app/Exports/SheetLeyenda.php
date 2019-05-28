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

    public function __construct($precabecera, $data, $cabecera, $background, $title,$tramos)
    {
        $this->precabecera = $precabecera;
        $this->cabecera = $cabecera;
        $this->data = $data;
        $this->background = $background;
        $this->title = $title;
        $this->tramos = $tramos;
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
        $background = $this->background;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($background) {
                $event->sheet->getDelegate()->getStyle("A1")->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle("A1")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A1")->getFont()->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle("A1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('000000');

                $event->sheet->getDelegate()->getStyle("B1")->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle("B1")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("B1")->getFont()->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle("B1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('000000');

                $i = 0;
                foreach ($this->tramos as $tramo) {
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setSize(12);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setBold(true);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->getColor()->setRGB('ffffff');
                    $event->sheet->getDelegate()->getStyle($tramo)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($background[$i]);
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
        return $this->title;
    }







}