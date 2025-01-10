<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('views_count')->default(0);
        });
    }

    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['price', 'is_available', 'views_count']);
        });
    }
};
