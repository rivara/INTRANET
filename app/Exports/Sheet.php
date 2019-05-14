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
class Sheet implements FromCollection, WithHeadings, WithEvents, WithTitle , WithProgressBar
{

    protected $precabecera;
    protected $cabecera;
    protected $data;
    protected $background;
    protected $pagename;
    protected $tramos;
    protected $columna_roja;

    public function __construct($precabecera, $data, $cabecera, $background, $title, $tramos)
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

        return $this->data;
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

                $i = 0;
                foreach ($this->tramos as $tramo) {
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setSize(12);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->setBold(true);
                    $event->sheet->getDelegate()->getStyle($tramo)->getFont()->getColor()->setRGB('ffffff');
                    $event->sheet->getDelegate()->getStyle($tramo)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($background[$i]);
                    $i++;
                }

                $event->sheet->getDelegate()->getStyle("A1:W1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->
                getStartColor()->setRGB($background[2]);

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


    /**RVR PENDIENTE BARRA DE PROGRESO*/


    /**
     * @return OutputStyle
     */
    public function getConsoleOutput(): OutputStyle
    {
        // TODO: Implement getConsoleOutput() method.
        // $this->output->createProgressBar(2);
    }

}