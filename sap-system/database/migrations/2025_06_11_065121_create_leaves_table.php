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
      Schema::create('leaves', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('company_id');
    $table->unsignedBigInteger('employee_id');
    $table->string('leave_type');
    $table->date('start_date');
    $table->date('end_date');
    $table->integer('days');
    $table->text('reason');
    $table->string('status')->default('pending'); // pending, approved, rejected
    $table->text('admin_notes')->nullable();
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
        Schema::dropIfExists('leaves');
    }
};
