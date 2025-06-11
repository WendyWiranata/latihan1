<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('salaries', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('company_id');
    $table->unsignedBigInteger('employee_id');
    $table->decimal('basic_salary', 12, 2);
    $table->decimal('allowance', 12, 2)->default(0);
    $table->decimal('bonus', 12, 2)->default(0);
    $table->decimal('tax', 12, 2)->default(0);
    $table->decimal('total_salary', 12, 2);
    $table->date('payment_date');
    $table->string('payment_method');
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->foreign('company_id')->references('id')->on('companies');
    $table->foreign('employee_id')->references('id')->on('employees');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
