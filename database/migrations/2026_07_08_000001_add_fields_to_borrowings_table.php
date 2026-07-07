<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('borrowings')) {
            Schema::create('borrowings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('book_id')->nullable();
                $table->integer('duration')->default(7);
                $table->integer('price')->default(0);
                $table->string('payment_method')->nullable();
                $table->string('status')->default('pending');
                $table->timestamp('borrowed_at')->nullable();
                $table->timestamp('due_date')->nullable();
                $table->timestamp('returned_at')->nullable();
                // Backwards-compatible fields (existing admin views/controllers expect these)
                $table->timestamp('borrow_date')->nullable();
                $table->timestamp('return_date')->nullable();

                $table->timestamps();

                $table->index('user_id');
                $table->index('book_id');
            });
        } else {
            Schema::table('borrowings', function (Blueprint $table) {
                if (!Schema::hasColumn('borrowings', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('borrowings', 'book_id')) {
                    $table->unsignedBigInteger('book_id')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('borrowings', 'duration')) {
                    $table->integer('duration')->default(7)->after('book_id');
                }
                if (!Schema::hasColumn('borrowings', 'price')) {
                    $table->integer('price')->default(0)->after('duration');
                }
                if (!Schema::hasColumn('borrowings', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('price');
                }
                if (!Schema::hasColumn('borrowings', 'status')) {
                    $table->string('status')->default('pending')->after('payment_method');
                }
                if (!Schema::hasColumn('borrowings', 'borrowed_at')) {
                    $table->timestamp('borrowed_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('borrowings', 'due_date')) {
                    $table->timestamp('due_date')->nullable()->after('borrowed_at');
                }
                if (!Schema::hasColumn('borrowings', 'returned_at')) {
                    $table->timestamp('returned_at')->nullable()->after('due_date');
                }
                if (!Schema::hasColumn('borrowings', 'borrow_date')) {
                    $table->timestamp('borrow_date')->nullable()->after('returned_at');
                }
                if (!Schema::hasColumn('borrowings', 'return_date')) {
                    $table->timestamp('return_date')->nullable()->after('borrow_date');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('borrowings')) {
            Schema::table('borrowings', function (Blueprint $table) {
                foreach ([
                    'user_id','book_id','duration','price','payment_method','status',
                    'borrowed_at','due_date','returned_at','borrow_date','return_date'
                ] as $col) {
                    if (Schema::hasColumn('borrowings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
