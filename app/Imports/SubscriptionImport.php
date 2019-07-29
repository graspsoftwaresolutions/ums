<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


class SubscriptionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {

		print_r($row); die;
		/* if($row['name']!=''){
			return new User([
				'name'  => $row['name'],
				'email' => $row['email'],
				'password' => '22',
			]);
		} */
    }
	/* public function headingRow(): int
    {
        return 2;
    } */
}
