<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_upgrades', function (Blueprint $table) {
            // Only drop columns if they exist to avoid SQL error 1091.
            $connection = Schema::getConnection();
            $schemaBuilder = $connection->getSchemaBuilder();

            if ($schemaBuilder->hasColumn('membership_upgrades', 'start_date')) {
                try {
                    $table->dropColumn('start_date');
                } catch (\Throwable $e) {
                    // no-op
                }
            }

            if ($schemaBuilder->hasColumn('membership_upgrades', 'end_date')) {
                try {
                    $table->dropColumn('end_date');
                } catch (\Throwable $e) {
                    // no-op
                }
            }
        });
    }


    public function down(): void
    {
        Schema::table('membership_upgrades', function (Blueprint $table) {
            try {
                $table->date('start_date')->nullable();
            } catch (\Throwable $e) {
                // no-op
            }

            try {
                $table->date('end_date')->nullable();
            } catch (\Throwable $e) {
                // no-op
            }
        });
    }
};

