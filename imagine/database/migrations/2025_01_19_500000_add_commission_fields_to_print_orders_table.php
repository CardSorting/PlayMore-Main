<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('print_orders', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(4.00)->after('total_price');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_rate');
            $table->foreignId('creator_id')->nullable()->after('user_id')->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('print_orders', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropColumn(['commission_rate', 'commission_amount', 'creator_id']);
        });
    }
};
