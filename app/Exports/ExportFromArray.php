<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ExportFromArray implements FromArray
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $data = $this->data;
        return $data;
    }

}