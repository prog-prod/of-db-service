<?php

namespace App\Exports;

class ArrayToCsvExport implements \Maatwebsite\Excel\Concerns\FromCollection
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }
}
