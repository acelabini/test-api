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
        Schema::create('project_properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->string('title')->index();
            $table->string('status')->index();
            $table->double('area_total');
            $table->double('area_external');
            $table->double('area_internal');
            $table->integer('bedrooms')->index()->default(0);
            $table->integer('bathrooms')->index()->default(0);
            $table->integer('car_spaces')->index()->default(0);
            $table->integer('levels')->nullable();
            $table->decimal('price', 27, 8)->index();
            $table->decimal('deposit_payment', 27, 8);
            $table->decimal('monthly_payment', 27, 8)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_properties');
    }
};
