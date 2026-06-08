<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiry_settings', function (Blueprint $table) {
            $table->string('consultant_name')->nullable()->after('hours');
            $table->string('consultant_title')->nullable()->after('consultant_name');
            $table->string('consultant_company')->nullable()->after('consultant_title');
            $table->string('consultant_phone')->nullable()->after('consultant_company');
            $table->string('consultant_email')->nullable()->after('consultant_phone');
            $table->string('consultant_photo')->nullable()->after('consultant_email');
            $table->string('links_label')->nullable()->after('consultant_photo');
            $table->string('link_1')->nullable()->after('links_label');
            $table->string('link_2')->nullable()->after('link_1');
            $table->string('link_3')->nullable()->after('link_2');
        });
    }

    public function down(): void
    {
        Schema::table('inquiry_settings', function (Blueprint $table) {
            $table->dropColumn([
                'consultant_name', 'consultant_title', 'consultant_company',
                'consultant_phone', 'consultant_email', 'consultant_photo',
                'links_label', 'link_1', 'link_2', 'link_3',
            ]);
        });
    }
};
