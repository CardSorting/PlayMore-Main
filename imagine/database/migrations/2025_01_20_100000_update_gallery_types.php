<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update galleries to 'card' type if they have card-specific fields filled
        DB::table('galleries')
            ->whereNotNull('mana_cost')
            ->orWhereNotNull('card_type')
            ->orWhereNotNull('abilities')
            ->orWhereNotNull('power_toughness')
            ->update(['type' => 'card']);

        // All other galleries should be prints
        DB::table('galleries')
            ->whereNull('type')
            ->orWhere('type', '')
            ->update(['type' => 'print']);
    }

    public function down()
    {
        // No need for down migration as we're just updating existing data
    }
};
