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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('ign');
            $table->integer('power')->nullable();
            $table->integer('level')->nullable();
            $table->string('class')->nullable();
            $table->string('guild')->nullable()->default('Paragon丶');
            $table->string('status')->nullable()->default('unverified');
            
            $table->string('power_screenshot_path')->nullable();
            $table->boolean('power_is_processed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
