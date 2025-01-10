<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('bio')->nullable();
            $table->string('website')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_twitter')->nullable();
            $table->integer('avg_response_minutes')->default(1440); // Default 24 hours
            $table->timestamp('last_response_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->boolean('is_seller')->default(false);
            $table->json('shipping_countries')->nullable();
            $table->json('seller_settings')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'state',
                'city',
                'bio',
                'website',
                'social_instagram',
                'social_twitter',
                'avg_response_minutes',
                'last_response_at',
                'last_active_at',
                'is_seller',
                'shipping_countries',
                'seller_settings'
            ]);
        });
    }
};
