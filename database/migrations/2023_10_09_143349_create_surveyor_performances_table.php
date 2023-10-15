<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyorPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveyor_performances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('surveyor_id');
            $table->integer('efficiency');
            $table->integer('productivity');
            $table->integer('quality');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('score', 15, 2);

            $table->unique(['surveyor_id', 'month', 'year']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveyor_performances');
    }
}
