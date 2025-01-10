<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update any galleries with type 'print' to 'image'
        DB::table('galleries')
            ->where('type', 'print')
            ->update(['type' => 'image']);
    }

    public function down()
    {
        // No need for down migration as we're just updating data
    }
};
