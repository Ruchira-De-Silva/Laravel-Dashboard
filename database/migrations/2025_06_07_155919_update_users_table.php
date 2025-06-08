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
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name');
            $table->string('username')->unique();
            $table->date('date_of_birth');
            $table->enum('role', ['staff', 'manager', 'admin']);
            $table->date('date_hired');
            $table->string('employee_code', 8)->unique();         
            $table->foreignId('department_id')->nullable()->constrained('departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
