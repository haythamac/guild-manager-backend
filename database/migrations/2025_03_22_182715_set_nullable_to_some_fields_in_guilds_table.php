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
        Schema::table('guilds', function (Blueprint $table) {
            $table->string('server')->nullable()->change();
            $table->string('leader')->nullable()->change();
            $table->integer('game_id')->nullable()->change();
            $table->integer('member_count')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guilds', function (Blueprint $table) {
            $table->string('server')->nullable(false)->change();
            $table->string('leader')->nullable(false)->change();
            $table->integer('game_id')->nullable(false)->change();
            $table->integer('member_count')->nullable(false)->change();
        });
    }
};
