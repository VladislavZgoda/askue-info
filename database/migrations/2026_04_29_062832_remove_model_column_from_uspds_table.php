<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('uspds', function (Blueprint $table) {
            $table->dropColumn('model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('uspds')->truncate();

        Schema::table('uspds', function (Blueprint $table) {
            $table->enum('model', [
                'RTR8A.LRsGE-2-1-RUFG',
                'RTR8A.LGE-2-2-RUF',
                'RTR58A.LG-1-1',
                'RTR58A.LG-2-1',
            ]);
        });
    }
};
