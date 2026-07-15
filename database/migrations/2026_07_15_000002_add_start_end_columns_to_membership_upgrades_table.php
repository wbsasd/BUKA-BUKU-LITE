<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_upgrades', function (Blueprint $table) {
            // (intentionally left blank: start_date/end_date already created in 2026_07_14_000001)

        });
    }

    public function down(): void
    {
        Schema::table('membership_upgrades', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};

