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
        
        Schema::create('crew_next_of_kins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('crew_id')
                ->constrained('crews')
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('relationship')->nullable();
            $table->text('mobile')->nullable();
            $table->text('email')->nullable();
            $table->text('address')->nullable();
            $table->string('priority')->default('alternate');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crew_next_of_kin');
    }
};
