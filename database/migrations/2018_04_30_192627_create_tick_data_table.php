<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tick_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pair', 10);
            $table->float('last', 18, 9);
            $table->float('lowest_ask', 18, 9);
            $table->float('highest_bid', 18, 9);
            $table->float('percent_change', 18, 9);
            $table->float('base_volume', 18, 9);
            $table->float('quote_volume', 18, 9);
            $table->float('day_high', 18, 9);
            $table->float('day_low', 18, 9);
            $table->float('weighted_average', 18, 9)->nullable();
            $table->boolean('is_frozen');
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
        Schema::dropIfExists('tick_data');
    }
}
