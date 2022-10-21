<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('project_id');
            $table->integer('sub_category_id');
            $table->text('name');
            $table->text('detail');
            $table->text('description');
            $table->string('incharge');
            $table->string('assignedto');
            $table->string('fromDate');
            $table->string('hourTime');
            $table->string('toDate');
            $table->string('job_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('jobs');
    }

}
