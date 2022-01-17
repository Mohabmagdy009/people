<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActualsTable extends Migration
{

    public function up()
    {
        Schema::create('activities_actual', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->integer('week');
            $table->integer('project_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->float('actuals');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities_actual');
    }
}
