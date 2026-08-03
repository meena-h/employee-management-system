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
        Schema::create('employee_family_members', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
    $table->string('name', 150);
    $table->string('relationship', 30); // spouse, father, mother, child, sibling, other
    $table->date('date_of_birth')->nullable();
    $table->string('occupation', 150)->nullable();
    $table->string('contact_number', 20)->nullable();
    $table->softDeletes();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_family_members');
    }
};
