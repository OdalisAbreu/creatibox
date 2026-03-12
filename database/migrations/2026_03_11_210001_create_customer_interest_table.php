<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_interest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('interest_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['customer_id', 'interest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_interest');
    }
};
