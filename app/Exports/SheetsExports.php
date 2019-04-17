<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 16/04/2019
 * Time: 13:40
 */

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class sheetsExports  implements WithMultipleSheets
{

    protected $page1;
    protected $page2;

    public function __construct($page1,$page2) {
        $this->page1 = $page1;
        $this->page2 = $page2;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [$this->page1,$this->page2];
    }
}