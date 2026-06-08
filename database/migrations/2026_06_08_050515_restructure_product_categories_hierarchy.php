<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add parent_id column
        Schema::table('product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('product_categories')->nullOnDelete();
        });

        // 2. Insert 2 new system categories
        $forkliftId = DB::table('product_categories')->insertGetId([
            'name'         => 'Forklift',
            'slug'         => 'forklift',
            'description'  => null,
            'image'        => null,
            'sort_order'   => 0,
            'is_active'    => true,
            'is_system'    => true,
            'show_in_nav'  => false,
            'show_inquiry' => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $sewaId = DB::table('product_categories')->insertGetId([
            'name'         => 'Sewa Forklift',
            'slug'         => 'sewa-forklift',
            'description'  => null,
            'image'        => null,
            'sort_order'   => 1,
            'is_active'    => true,
            'is_system'    => true,
            'show_in_nav'  => false,
            'show_inquiry' => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // 3. Assign existing sub-categories to correct parent
        DB::table('product_categories')
            ->where('is_system', false)
            ->where('slug', '!=', 'kebutuhan-sewa-forklift')
            ->whereNull('parent_id')
            ->update(['parent_id' => $forkliftId]);

        DB::table('product_categories')
            ->where('slug', 'kebutuhan-sewa-forklift')
            ->update(['parent_id' => $sewaId]);

        // 4. Remove old single "semua-produk" system category
        DB::table('product_categories')->where('slug', 'semua-produk')->delete();
    }

    public function down(): void
    {
        // Remove parent_id and new system categories
        DB::table('product_categories')->whereIn('slug', ['forklift', 'sewa-forklift'])->delete();

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
