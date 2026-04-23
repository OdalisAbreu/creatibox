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
        if (!Schema::hasTable('captures')) {
            return;
        }

        if (Schema::hasColumn('captures', 'passport')) {
            Schema::table('captures', function (Blueprint $table) {
                $table->dropColumn('passport');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('captures')) {
            return;
        }

        if (!Schema::hasColumn('captures', 'passport')) {
            Schema::table('captures', function (Blueprint $table) {
                $table->string('passport')->nullable()->after('card_id');
            });
        }
    }
};
