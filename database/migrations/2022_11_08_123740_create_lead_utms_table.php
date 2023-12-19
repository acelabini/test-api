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
        Schema::create('lead_utms', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('source', 240)->nullable();
            $table->string('medium', 240)->nullable();
            $table->string('term', 240)->nullable();
            $table->string('content', 240)->nullable();
            $table->uuid('lead_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lead_id')->references('id')->on('leads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_utms');
    }
};
