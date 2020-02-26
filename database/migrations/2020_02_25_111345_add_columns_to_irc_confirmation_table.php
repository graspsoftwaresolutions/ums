<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToIrcConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('irc_confirmation', function (Blueprint $table) {
            $table->integer('messengerbox')->default(0)->after('comments');
            $table->integer('jobtakenbox')->default(0)->after('messengerbox');
            $table->string('jobtakenby')->nullable()->after('jobtakenbox');
            $table->integer('posfilledbybox')->default(0)->after('jobtakenby');
            $table->string('posfilledby')->nullable()->after('posfilledbybox');
            $table->integer('replacestaffbox')->default(0)->after('posfilledby');
            $table->string('replacestaff')->nullable()->after('replacestaffbox');
            $table->integer('appcontactbox')->default(0)->after('replacestaff');
            $table->string('appcontact')->nullable()->after('appcontactbox');
            $table->string('appoffice')->nullable()->after('appcontact');  
            $table->string('appmobile')->nullable()->after('appoffice');  
            $table->string('appfax')->nullable()->after('appmobile'); 
            $table->string('appemail')->nullable()->after('appfax'); 
            $table->integer('samebranchbox')->default(0)->after('appemail');
            $table->integer('attachedbox')->default(0)->after('samebranchbox');
            $table->string('attached_desc')->nullable()->after('attachedbox'); 
            $table->integer('demised_onboxtwo')->default(0)->after('attached'); 
            $table->integer('member_nameboxtwo')->default(0)->after('demised_onboxtwo'); 
            $table->integer('relationshipboxtwo')->default(0)->after('member_nameboxtwo');
            $table->string('demised_ontwo')->nullable()->after('relationshipboxtwo'); 
            $table->string('member_nametwo')->nullable()->after('demised_ontwo'); 
            $table->string('relationshiptwo')->nullable()->after('member_nametwo'); 
            $table->integer('applicantboxtwo')->default(0)->after('relationshiptwo');
            $table->integer('promotedboxthree')->default(0)->after('applicantboxtwo');
            $table->integer('transfertoplaceboxthree')->default(0)->after('promotedboxthree');
            $table->string('transfertoplacethree')->nullable()->after('transfertoplaceboxthree');
            $table->integer('resignedonboxfour')->default(0)->after('transfertoplacethree');
            $table->integer('expelledboxfive')->default(0)->after('resignedonboxfour');
            $table->integer('samejobboxfive')->default(0)->after('expelledboxfive');
            $table->integer('memberstoppedboxfive')->default(0)->after('samejobboxfive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('irc_confirmation', function (Blueprint $table) {
            //
        });
    }
}
