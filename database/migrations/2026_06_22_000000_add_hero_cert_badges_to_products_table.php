<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('hero_cert_badge_1', 500)->nullable()->after('image_url');
            $table->string('hero_cert_badge_2', 500)->nullable()->after('hero_cert_badge_1');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['hero_cert_badge_1', 'hero_cert_badge_2']);
        });
    }
};
