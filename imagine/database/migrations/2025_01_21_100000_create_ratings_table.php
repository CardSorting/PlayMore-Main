<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('rated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('print_order_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Ensure a user can only rate once per order
            $table->unique(['rated_by', 'print_order_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
};
