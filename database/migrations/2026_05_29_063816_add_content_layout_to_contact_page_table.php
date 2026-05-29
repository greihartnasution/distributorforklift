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
        Schema::table('contact_page', function (Blueprint $table) {
            $table->string('content_layout', 20)->default('full')->after('content');
            $table->longText('content_left')->nullable()->after('content_layout');
            $table->longText('content_right')->nullable()->after('content_left');
        });
    }

    public function down(): void
    {
        Schema::table('contact_page', function (Blueprint $table) {
            $table->dropColumn(['content_layout', 'content_left', 'content_right']);
        });
    }
};
