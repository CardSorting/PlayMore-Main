<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_orders', function (Blueprint $table) {
            $table->string('material')->default('premium_lustre')->after('size');
            $table->text('notes')->nullable()->after('stripe_payment_intent_id');
            $table->timestamp('paid_at')->nullable()->after('notes');
            $table->timestamp('shipped_at')->nullable()->after('paid_at');
            $table->timestamp('completed_at')->nullable()->after('shipped_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->string('tracking_number')->nullable()->after('cancelled_at');
            $table->string('shipping_carrier')->nullable()->after('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('print_orders', function (Blueprint $table) {
            $table->dropColumn([
                'material',
                'notes',
                'paid_at',
                'shipped_at',
                'completed_at',
                'cancelled_at',
                'tracking_number',
                'shipping_carrier'
            ]);
        });
    }
};
