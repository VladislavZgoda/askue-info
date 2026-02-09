<?php

use App\Models\Meter;
use App\Models\SimCard;
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
        Schema::create('meter_sim_card', function (Blueprint $table) {
            $table->foreignIdFor(Meter::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SimCard::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_sim_card');
    }
};
