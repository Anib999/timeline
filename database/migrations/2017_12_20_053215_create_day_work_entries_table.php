<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayWorkEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_work_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('project_id');
            //$table->string('project_name');
            $table->integer('subcat_id');
            //$table->string('subcat_name');
            $table->integer('workDetail_id');
            //$table->string('workDetail_name');
            $table->string('workComment');
            $table->float('workHour');
            $table->date('workEntryDate');
            $table->integer('dayWorkEntryUser_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_work_entries');
    }
}
