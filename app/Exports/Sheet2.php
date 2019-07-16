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
use Maatwebsite\Excel\Events\BeforeSheet;


/**
 * @property  output
 */
class Sheet2 implements FromCollection, WithHeadings, WithEvents, WithTitle
{

    protected $precabecera;
    protected $cabecera;
    protected $data;
    protected $background;
    protected $pagename;
    protected $tramos;
    protected $columna_roja;

    public function __construct($precabecera, $data, $cabecera, $background, $title, $tramo)
    {
        $this->precabecera = $precabecera;
        $this->cabecera = $cabecera;
        $this->data = $data;
        $this->background = $background;
        $this->title = $title;
        $this->tramo = $tramo;
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
                $event->sheet->getDelegate()->getStyle("A1")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A3")->getFont()->setBold(true);

            },
            AfterSheet::class => function (AfterSheet $event) use ($background) {
                $event->sheet->getDelegate()->getStyle($this->tramo)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($background);

                //VENTAS >> CONSUMO MARCA PROPIA

                if ($this->title == "INFORME DE VENTAS POR CLIENTE") {
                    $event->sheet->appendRows(array(
                        array(
                            null,
                            null,
                            null,
                            null,
                            '=SUM(E1:E' . $event->sheet->getDelegate()->getHighestRow() . ')&"€"',
                            '=SUM(E1:E' . $event->sheet->getDelegate()->getHighestRow() . ')&"€"',
                            null,
                            '=SUM(E1:E' . $event->sheet->getDelegate()->getHighestRow() . ')&"€"',
                            '=SUM(E1:E' . $event->sheet->getDelegate()->getHighestRow() . ')&"€"',
                            null
                        ),
                    ), $event);
                }
                if ($this->title == "INFORME DE VENTAS POR ARTICULOS") {
                    $event->sheet->appendRows(array(
                        array(
                            null,
                            null,
                            '=SUM(E1:E' . $event->sheet->getDelegate()->getHighestRow() . ')',
                            '=SUM(E1:E' . $event->sheet->getDelegate()->getHighestRow() . ')',
                            null,
                            '=SUM(E1:E' . $event->sheet->getDelegate()->getHighestRow() . ')'
                        ),
                    ), $event);
                }

            },
        );

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