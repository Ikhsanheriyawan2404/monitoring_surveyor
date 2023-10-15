<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("surveyor_id");
            $table->string("name")->nullable();
            $table->string("debitur_name");
            $table->timestamp("survey_date");
            $table->string("plat_number");
            $table->string("slik_status")->nullable();
            $table->string("application_status")->nullable();
            $table->boolean("take_over");
            $table->boolean("bpkb_onhand");
            $table->string("leasing_name");
            $table->string("slik_grouping");
            $table->string("information")->nullable();
            $table->string("status"); // reject, golive, progrees(default), done
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('surveyor_id')->references('id')->on('surveyors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
