<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_orders', function (Blueprint $table) {
            // Add new columns
            $table->string('order_number')->unique()->after('id');
            $table->decimal('shipping_weight', 8, 2)->default(0)->after('price');
            
            // Make shipping fields nullable
            $table->string('shipping_name')->nullable()->change();
            $table->string('shipping_address')->nullable()->change();
            $table->string('shipping_city')->nullable()->change();
            $table->string('shipping_state')->nullable()->change();
            $table->string('shipping_zip')->nullable()->change();
            $table->string('shipping_country')->nullable()->change();
            
            // Make payment intent nullable
            $table->string('stripe_payment_intent_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('print_orders', function (Blueprint $table) {
            $table->dropColumn(['order_number', 'shipping_weight']);
            
            // Make shipping fields required again
            $table->string('shipping_name')->nullable(false)->change();
            $table->string('shipping_address')->nullable(false)->change();
            $table->string('shipping_city')->nullable(false)->change();
            $table->string('shipping_state')->nullable(false)->change();
            $table->string('shipping_zip')->nullable(false)->change();
            $table->string('shipping_country')->nullable(false)->change();
            
            // Make payment intent required again
            $table->string('stripe_payment_intent_id')->nullable(false)->change();
        });
    }
};
