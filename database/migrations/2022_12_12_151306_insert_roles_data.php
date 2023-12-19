<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $currentDateTime = Carbon\Carbon::now();

        DB::table('roles')->insert([
            ['id' => Str::uuid(), 'name' => 'agent', 'created_at' => $currentDateTime, 'updated_at' => $currentDateTime],
            ['id' => Str::uuid(), 'name' => 'developer', 'created_at' => $currentDateTime, 'updated_at' => $currentDateTime],
            ['id' => Str::uuid(), 'name' => 'admin', 'created_at' => $currentDateTime, 'updated_at' => $currentDateTime],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->where('name', '=', 'agent')->delete();
        DB::table('roles')->where('name', '=', 'developer')->delete();
        DB::table('roles')->where('name', '=', 'admin')->delete();
    }
};
