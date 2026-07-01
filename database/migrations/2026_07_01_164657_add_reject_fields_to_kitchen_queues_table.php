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
        Schema::table('kitchen_queues', function (Blueprint $table) {
            $table->foreignId('reject_item_id')
                ->nullable()
                ->after('order_item_id')
                ->constrained('reject_items')
                ->nullOnDelete();

            $table->integer('quantity')
                ->default(1)
                ->after('reject_item_id');

            $table->enum('queue_type', ['new_order', 'remake'])
                ->default('new_order')
                ->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitchen_queues', function (Blueprint $table) {
            //
        });
    }
};
