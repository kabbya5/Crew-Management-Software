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
        
        Schema::create('crews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('crew_id')->unique();

            // Personal Information
            $table->string('photo')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('full_name');
            $table->string('rank_applied_for');
            $table->string('nationality')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('current_location')->nullable();
            $table->date('available_from')->nullable();

            // Physical Measurements
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('blood_group')->nullable();
            $table->string('shoe_size')->nullable();
            $table->string('shoe_size_standard')->nullable();
            $table->string('boiler_suit_size')->nullable();

            // Professional Summary
            $table->string('english_proficiency')->nullable();
            $table->string('chinese_proficiency')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crews');
    }
};
