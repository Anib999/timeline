<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveApplicablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applicables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('leave_id');
            $table->integer('year_id');
            $table->integer('remaining_days')->nullable();
            // $table->decimal('remaining_days', 10, 1)->nullable();
			$table->boolean('isActive')->default(1);
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
        Schema::dropIfExists('leave_applicables');
    }
}
