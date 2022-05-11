<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubActivityActuals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subactivityactuals', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('week');
            $table->integer('project_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->float('task_hour');
            $table->foreignId('sub_id')->constrained('SubActivityTypes');
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
        Schema::dropIfExists('SubActivityActuals');
    }
}
