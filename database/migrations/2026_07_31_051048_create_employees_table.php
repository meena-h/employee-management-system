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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
        $table->string('employee_code', 20)->unique();
        $table->string('first_name', 100);
        $table->string('last_name', 100);
        $table->date('date_of_birth');
        $table->string('gender', 20);
        $table->string('marital_status', 20);
        $table->string('personal_email')->nullable()->unique();
        $table->string('phone_number', 20)->unique();
        $table->string('alternate_phone_number', 20)->nullable();
        $table->text('current_address');
        $table->text('permanent_address');
        $table->string('department', 100)->nullable();
        $table->string('designation', 100);
        $table->date('date_of_joining');
        $table->string('employment_status', 20)->default('active');
        $table->string('blood_group', 5)->nullable();
        $table->string('emergency_contact_name', 100)->nullable();
        $table->string('emergency_contact_number', 20)->nullable();
        $table->string('profile_photo_path')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        $table->softDeletes();
        $table->timestamps();

        $table->index('department');
        $table->index('employment_status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
