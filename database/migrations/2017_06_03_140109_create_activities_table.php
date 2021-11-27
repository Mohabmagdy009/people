<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->integer('month');
            $table->integer('project_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->float('task_hour');
            $table->boolean('from_otl')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
        DB::statement('ALTER TABLE `activities` ADD UNIQUE( `year`, `month`, `project_id`, `user_id`, `from_otl`);');
    }

    public function down()
    {
        Schema::drop('activities');
    }
}
