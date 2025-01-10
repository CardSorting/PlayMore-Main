<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_orders', function (Blueprint $table) {
            // Drop existing decimal columns
            $table->dropColumn(['total_price', 'unit_price']);

            // Re-add as integer columns (storing cents)
            $table->unsignedInteger('total_price')->default(0)->after('status');
            $table->unsignedInteger('unit_price')->default(0)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('print_orders', function (Blueprint $table) {
            // Drop integer columns
            $table->dropColumn(['total_price', 'unit_price']);

            // Re-add as decimal columns
            $table->decimal('total_price', 8, 2)->default(0)->after('status');
            $table->decimal('unit_price', 8, 2)->default(0)->after('quantity');
        });
    }
};
