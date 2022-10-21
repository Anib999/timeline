<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminToEmployeeRequestForCheckInOutsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('admin_to_employee_request_for_check_in_outs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('day');
            $table->text('em_comment');
            $table->string('request_type');
            $table->string('check_in_request_time')->nullable();
            $table->string('check_out_request_time')->nullable();
            $table->text('ap_comment')->nullable();
            $table->string('aprove_by')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('admin_to_employee_request_for_check_in_outs');
    }

}
