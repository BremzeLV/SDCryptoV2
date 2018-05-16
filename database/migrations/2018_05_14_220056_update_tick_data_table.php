<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTickDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tick_data', function (Blueprint $table) {
            $table->float('upper_boliband', 18, 9);
            $table->float('lower_boliband', 18, 9);
            $table->float('current_boliband', 18, 9);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['upper_boliband']);
            $table->dropColumn(['lower_boliband']);
            $table->dropColumn(['current_boliband']);
        });
    }
}
