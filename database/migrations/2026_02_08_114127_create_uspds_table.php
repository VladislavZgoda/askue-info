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
        Schema::create('uspds', function (Blueprint $table) {
            $table->id();
            $table->enum('model', ['RTR8A.LRsGE-2-1-RUFG', 'RTR8A.LGE-2-2-RUF', 'RTR58A.LG-1-1', 'RTR58A.LG-2-1']);
            $table->smallInteger('serial_number', false, true)->unique();
            $table->ipAddress('lan_ip')->default('192.168.0.100');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uspds');
    }
};
