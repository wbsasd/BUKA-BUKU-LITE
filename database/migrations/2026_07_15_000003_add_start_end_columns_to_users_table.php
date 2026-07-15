<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add only if columns do not exist.
            // Laravel doesn't support conditional column add via Blueprint directly,
            // so we rely on try/catch to keep migration safe.
            try {
                $table->date('start_date')->nullable()->after('membership_status');
            } catch (\Throwable $e) {
                // no-op
            }

            try {
                $table->date('end_date')->nullable()->after('start_date');
            } catch (\Throwable $e) {
                // no-op
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropColumn(['start_date', 'end_date']);
            } catch (\Throwable $e) {
                // no-op
            }
        });
    }
};

