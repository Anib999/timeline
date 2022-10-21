<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('isAdminMessage')->default(0);
            $table->integer('messageType');
            $table->string('message');
            $table->integer('createdBy');
            $table->dateTime('deliveredOn')->nullable();
            $table->dateTime('readOn')->nullable();
            $table->integer('readBy')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('viewStatus')->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
