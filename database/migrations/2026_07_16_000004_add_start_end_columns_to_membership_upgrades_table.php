<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_upgrades', function (Blueprint $table) {
            // Re-add columns as source of truth for membership active period.
            // If columns already exist, Laravel may throw; this migration is intended
            // to be executed after the drop migration.
            if (!Schema::hasColumn('membership_upgrades', 'start_date')) {
                $table->date('start_date')->nullable()->after('requested_at');
            }
            if (!Schema::hasColumn('membership_upgrades', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('membership_upgrades', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};

