<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, set all galleries to 'image' type by default
        DB::table('galleries')
            ->whereNull('type')
            ->orWhere('type', '')
            ->update(['type' => 'image']);

        // Update galleries to 'card' type if they have card-specific fields
        DB::table('galleries')
            ->whereNotNull('mana_cost')
            ->orWhereNotNull('card_type')
            ->orWhereNotNull('abilities')
            ->orWhereNotNull('power_toughness')
            ->update(['type' => 'card']);

        // Update galleries to 'print' type if they have associated print orders
        DB::table('galleries')
            ->whereIn('id', function($query) {
                $query->select('gallery_id')
                    ->from('print_orders')
                    ->distinct();
            })
            ->where('type', 'image')
            ->update(['type' => 'print']);
    }

    public function down()
    {
        // No need for down migration as we're just updating existing data
    }
};
