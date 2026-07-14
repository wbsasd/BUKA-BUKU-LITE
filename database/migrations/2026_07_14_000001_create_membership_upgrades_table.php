<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_upgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Plan
            $table->unsignedSmallInteger('months'); // 3/6/12
            $table->unsignedInteger('amount'); // e.g. 49000

            // Dummy payment
            $table->string('payment_status')->default('unpaid'); // unpaid/paid
            $table->string('payment_method')->nullable();

            // Admin workflow
            $table->string('status')->default('pending'); // pending/approved/rejected
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_upgrades');
    }
};

