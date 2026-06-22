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
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('admin_primary_color', 7)->default('#0d6efd');
            $table->string('admin_secondary_color', 7)->default('#6c757d');
            $table->string('form_primary_color', 7)->default('#008037');
            $table->string('form_secondary_color', 7)->default('#0065B3');
            $table->string('form_background_color', 7)->default('#f7f9fa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
