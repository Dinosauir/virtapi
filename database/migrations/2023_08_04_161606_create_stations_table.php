<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('address');
            $table->point('location');
            $table->spatialIndex('location');
            $table->decimal('latitude', 10, 8)->index('latitude_index')->index('latitude_index');
            $table->decimal('longitude', 11, 8)->index('longitude_index')->index('longitude_index');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
