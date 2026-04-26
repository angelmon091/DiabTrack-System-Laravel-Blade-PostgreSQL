<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('linked_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('role'); // caregiver / doctor
            $table->string('invite_code', 6)->unique();
            $table->enum('status', ['pending', 'active', 'revoked'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_links');
    }
};
