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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hackathon_id')->constrained('hackathons')->onDelete('cascade');
    
            $table->enum('status', ['registered', 'confirmed', 'withdrawn'])->default('registered');
            $table->timestamp('registered_at')->useCurrent();
    
            $table->timestamps();
    
            $table->unique(['user_id', 'hackathon_id']); // no duplicate registration
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
