<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('present');
            $table->date('day');
            $table->integer('check_in');
            $table->string('check_in_by');
            $table->integer('check_out')->nullable();
            $table->time('check_in_time');
            $table->time('check_out_time')->nullable();
            $table->float('total_work_hour')->default(0);
            $table->string('checkin_remarks')->nullable();
            $table->string('checkout_remarks')->nullable();
            $table->string('checkin_location')->nullable();
            $table->string('is_leave_auto')->nullable();
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
        Schema::dropIfExists('attendences');
    }
}
