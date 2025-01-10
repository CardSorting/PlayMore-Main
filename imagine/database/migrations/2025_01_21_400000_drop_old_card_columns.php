<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Drop the old card-specific columns that were moved to metadata
            $table->dropColumn([
                'mana_cost',
                'card_type',
                'abilities',
                'flavor_text',
                'power_toughness',
                'rarity'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Recreate the columns if we need to rollback
            $table->string('mana_cost')->nullable()->after('metadata');
            $table->string('card_type')->nullable()->after('mana_cost');
            $table->text('abilities')->nullable()->after('card_type');
            $table->text('flavor_text')->nullable()->after('abilities');
            $table->string('power_toughness')->nullable()->after('flavor_text');
            $table->string('rarity')->nullable()->after('power_toughness');
        });
    }
};
