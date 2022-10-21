<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_visits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('project_id');
            // $table->string('project_name');
            // $table->integer('subcat_id')->nullable();
            $table->string('subcat_name')->nullable();
            // $table->integer('workDetail_id')->nullable();
            $table->string('workDetail_name')->nullable();
            $table->string('workComment')->nullable();
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
        Schema::dropIfExists('field_visits');
    }
}
