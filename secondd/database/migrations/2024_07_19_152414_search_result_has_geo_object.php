<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('search_result_has_geo_object', function(Blueprint $table){
            $table->foreignId("search_result_id")
                ->constrained('search_results')
                ->cascadeOnDelete();
            $table->foreignId("geo_object_id")
                ->constrained('geo_objects')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->primary(["geo_object_id","search_result_id"], "primary_key");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_result_has_geo_object');
    }
};
