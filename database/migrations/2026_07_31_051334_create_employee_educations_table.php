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
        Schema::create('employee_educations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
    $table->string('institution', 255);
    $table->string('degree', 150);
    $table->string('specialization', 150)->nullable();
    $table->unsignedSmallInteger('year_of_passing');
    $table->string('score_type', 20); // percentage or cgpa
    $table->decimal('score_value', 5, 2);
    $table->softDeletes();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_educations');
    }
};
