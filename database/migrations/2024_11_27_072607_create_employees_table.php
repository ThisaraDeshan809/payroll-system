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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('epf_no')->nullable();
            $table->string('name')->nullable();
            $table->integer('loacation_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->double('basic_salary',2)->nullable();
            $table->double('allowance_1',2)->nullable();
            $table->double('daily_salary')->nullable();
            $table->integer('OT')->default(0);
            $table->string('emp_category')->nullable();
            $table->string('designation')->nullable();
            $table->string('emp_epf')->default('K 1377');
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->integer('before_location_id')->nullable();
            $table->integer('current_location_id')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
