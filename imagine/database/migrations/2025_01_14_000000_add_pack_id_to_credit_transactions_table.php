<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credit_transactions', function (Blueprint $table) {
            // Add pack_id foreign key
            $table->foreignId('pack_id')->nullable()->constrained()->onDelete('set null');
            
            // Add indexes for efficient balance calculations and lookups
            $table->index(['user_id', 'type']);
            $table->index(['pack_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex(['pack_id', 'type']);
            $table->dropForeign(['pack_id']);
            $table->dropColumn('pack_id');
        });
    }
};
