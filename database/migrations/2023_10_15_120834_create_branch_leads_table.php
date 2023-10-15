<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_leads', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->integer('month');
            $table->integer('year');
            $table->integer('total_leads');
            $table->timestamps();

            $table->unique(['branch_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_leads');
    }
}
