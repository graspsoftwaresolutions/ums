<?php

use Illuminate\Database\Seeder;
use App\Model\Relation;

class RelationshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relation = new Relation();
	    $relation->relation_name = 'Father';
	    $relation->status = 1;
	    $relation->save();
		
		$relation = new Relation();
	    $relation->relation_name = 'Mother';
	    $relation->status = 1;
	    $relation->save();
    }
}
