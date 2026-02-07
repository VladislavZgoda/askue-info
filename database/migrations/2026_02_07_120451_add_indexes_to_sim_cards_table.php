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
        Schema::table('sim_cards', function (Blueprint $table) {
            $table->unique('number');
            $table->unique('ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sim_cards', function (Blueprint $table) {
            $table->dropUnique(['number']);
            $table->dropUnique(['ip']);
        });
    }
};
