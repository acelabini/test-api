<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_prefix', 4)->after('email');
            $table->string('mobile_number', 25)->after('mobile_prefix');

            $table->string('middle_name')->nullable()->change();
            $table->renameColumn('email_verified_at', 'verified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile_prefix', 'mobile_number']);

            $table->string('middle_name')->nullable(false)->change();
            $table->renameColumn('verified_at', 'email_verified_at');
        });
    }
};
