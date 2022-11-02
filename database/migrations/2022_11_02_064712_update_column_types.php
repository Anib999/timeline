<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('leave_applicables', function (Blueprint $table) {
            $table->dropColumn('remaining_days');
            // $table->decimal('remaining_days', 5, 2)->change();	
        });

        Schema::table('leave_applicables', function (Blueprint $table) {
            $table->decimal('remaining_days', 5, 2)->nullable()->after('year_id');
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('no_of_days');
            // $table->decimal('no_of_days', 5, 2)->change();	
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->decimal('no_of_days', 5, 2)->after('request_date');
        });

        // Schema::table('leave_requests', function (Blueprint $table) {
        //     $table->decimal('no_of_days', 5, 2)->change();	
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('leave_applicables', function (Blueprint $table) {
            $table->dropColumn('remaining_days');
            // $table->decimal('remaining_days', 5, 2)->change();	
        });

        Schema::table('leave_applicables', function (Blueprint $table) {
            $table->integer('remaining_days')->nullable()->after('year_id');
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('no_of_days');
            // $table->decimal('no_of_days', 5, 2)->change();	
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->integer('no_of_days')->after('request_date');
        });
    }
}
