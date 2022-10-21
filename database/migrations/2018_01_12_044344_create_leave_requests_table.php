<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('leave_applicable_id');
            $table->integer('leave_type_id');
            $table->date('request_date');
            $table->integer('no_of_days');
            $table->date('from_date');
            $table->date('to_date');
            $table->text('remarks');
            $table->boolean('paid_unpaid_status')->default(0);
            $table->string('aprove_by')->nullable();
            $table->string('ap_remarks')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('leave_details')->nullable();
            $table->text('leave_time')->nullable();
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
        Schema::dropIfExists('leave_requests');
    }
}
