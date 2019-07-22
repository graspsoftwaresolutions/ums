<?php

use Illuminate\Database\Seeder;
use App\Model\FormType;

class FormTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new_formtype = new FormType();
        $new_formtype->formname = 'Masters';
        $new_formtype->module = 'master';
        $new_formtype->orderno = 1;
        $new_formtype->save();
        $new_formtype->id;

        $new_formtype->roles()->sync([1]);
    }
}
