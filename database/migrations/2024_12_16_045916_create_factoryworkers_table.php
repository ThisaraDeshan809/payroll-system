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
        Schema::create('factoryworkers', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('name')->nullable();
            $table->string('monthly_loan_amount')->nullable();
            $table->integer('loan_premiums')->nullable();
            $table->integer('is_salary_advance')->default(0);
            $table->string('salary_advance_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factoryworkers');
    }
};
