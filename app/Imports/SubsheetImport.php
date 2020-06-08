<?php

namespace App\Imports;


use App\Form;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class SubsheetImport implements WithMappedCells,ToArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
   

    use Importable;

    public function mapping(): array
    {
        return [
            'name' => 'B3',
        ];
    }

    public function array(array $row)
    {
       // dd($row);
       return $row;
        
    }
	
}
