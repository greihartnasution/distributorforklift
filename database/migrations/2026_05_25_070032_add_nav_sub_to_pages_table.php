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
        if (Schema::hasColumn('pages', 'nav_sub')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->string('nav_sub')->nullable()->after('nav_group');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('nav_sub');
        });
    }
};
