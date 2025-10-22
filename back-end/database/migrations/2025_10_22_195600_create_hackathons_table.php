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
        Schema::create('hackathons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            
            $table->enum('status', ['draft', 'published', 'ongoing', 'ended'])->default('draft');
            $table->boolean('is_paid')->default(false);
            $table->integer('capacity')->nullable();
            
            $table->timestamps();
        });            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hackathons');
    }
};
