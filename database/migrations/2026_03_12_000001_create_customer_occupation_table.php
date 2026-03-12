<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_occupation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('occupation_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['customer_id', 'occupation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_occupation');
    }
};

