<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('solutions_title')->nullable()->after('details');
            $table->text('solutions_description')->nullable()->after('solutions_title');
            $table->json('solutions')->nullable()->after('solutions_description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['solutions_title', 'solutions_description', 'solutions']);
        });
    }
};
