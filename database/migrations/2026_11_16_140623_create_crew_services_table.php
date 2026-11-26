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
        Schema::create('crew_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('crew_id')
                ->constrained('crews')
                ->cascadeOnDelete();

            $table->string('rank');
            $table->string('vessel_name');
            $table->string('vessel_type');
            $table->string('dwt_bhp')->nullable();
            $table->string('imo_number')->nullable();
            $table->string('vessel_flag')->nullable();
            $table->string('main_engine')->nullable();
            $table->date('sign_on')->nullable();
            $table->date('sign_off')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crew_services');
    }
};
