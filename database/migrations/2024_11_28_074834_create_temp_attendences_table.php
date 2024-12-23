<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_attendences', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->string('name');
            $table->string('department');
            $table->string('date');
            $table->string('shift');
            $table->string('check_in');
            $table->string('check_out');
            $table->string('duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_attendences');
    }
};
