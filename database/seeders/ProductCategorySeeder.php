<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        ProductCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // --- Sistem categories (root level) ---
        $forklift = ProductCategory::create([
            'parent_id'    => null,
            'name'         => 'Forklift',
            'slug'         => 'forklift',
            'description'  => 'Solusi forklift baru untuk kebutuhan material handling industri Anda.',
            'image'        => 'product-categories/1iygR6QqAbLq2uilMJbp6LpOWyL8v0nH3idSXorw.webp',
            'sort_order'   => 0,
            'is_active'    => true,
            'is_system'    => true,
            'show_in_nav'  => false,
            'show_inquiry' => true,
            'nav_group'    => null,
            'nav_sub'      => null,
            'nav_label'    => 'Katalog Forklift',
        ]);

        $sewa = ProductCategory::create([
            'parent_id'    => null,
            'name'         => 'Sewa Forklift',
            'slug'         => 'sewa-forklift',
            'description'  => 'Solusi sewa forklift fleksibel untuk kebutuhan industri jangka pendek maupun panjang.',
            'image'        => 'product-categories/V7ZqDTZRf54dyIybW7BlmJEWTMGTHokSSvUdIaAw.webp',
            'sort_order'   => 1,
            'is_active'    => true,
            'is_system'    => true,
            'show_in_nav'  => false,
            'show_inquiry' => true,
            'nav_group'    => null,
            'nav_sub'      => null,
            'nav_label'    => 'Katalog Sewa',
        ]);

        // --- Sub-categories under /forklift ---
        ProductCategory::create([
            'parent_id'    => $forklift->id,
            'name'         => 'Reach Truck',
            'slug'         => 'reach-truck',
            'description'  => 'Forklift untuk area dengan lorong sempit dan rak bertingkat tinggi.',
            'image'        => 'product-categories/W0siJL0yaKl2Gbjg8IzBur4mrzgwQpxsZX1Y5qS6.webp',
            'sort_order'   => 1,
            'is_active'    => true,
            'is_system'    => false,
            'show_in_nav'  => true,
            'show_inquiry' => false,
            'nav_group'    => 'Forklift',
            'nav_sub'      => 'Forklift Baru',
            'nav_label'    => 'Reach Truck',
        ]);

        ProductCategory::create([
            'parent_id'    => $forklift->id,
            'name'         => 'High Lift Stackers',
            'slug'         => 'high-lift-stackers',
            'description'  => 'Forklift standar serbaguna untuk berbagai kebutuhan industri.',
            'image'        => 'product-categories/h6EjMEnCoO2eJRYecsHAT0nyNIiS7YRaPabeFdaS.webp',
            'sort_order'   => 2,
            'is_active'    => true,
            'is_system'    => false,
            'show_in_nav'  => true,
            'show_inquiry' => false,
            'nav_group'    => 'Forklift',
            'nav_sub'      => 'Forklift Baru',
            'nav_label'    => null,
        ]);

        ProductCategory::create([
            'parent_id'    => $forklift->id,
            'name'         => 'Electric Forklift Trucks',
            'slug'         => 'electric-forklift-trucks',
            'description'  => 'Pallet truck listrik efisien untuk distribusi dan gudang.',
            'image'        => 'product-categories/9BKhxH7OU6IiydlZrRLMJf9k4dWZWP68nu2Yj2tI.webp',
            'sort_order'   => 3,
            'is_active'    => true,
            'is_system'    => false,
            'show_in_nav'  => true,
            'show_inquiry' => false,
            'nav_group'    => 'Forklift',
            'nav_sub'      => 'Forklift Baru',
            'nav_label'    => null,
        ]);

        // --- Sub-categories under /sewa-forklift ---
        ProductCategory::create([
            'parent_id'    => $sewa->id,
            'name'         => 'Reach Truck',
            'slug'         => 'reach-truck',
            'description'  => 'Solusi sewa forklift fleksibel untuk kebutuhan industri jangka pendek maupun panjang.',
            'image'        => 'product-categories/I6OYGy3eNjmbyVwhHDOYtONlczGKIxYN8rpjIciw.webp',
            'sort_order'   => 1,
            'is_active'    => true,
            'is_system'    => false,
            'show_in_nav'  => true,
            'show_inquiry' => false,
            'nav_group'    => 'Forklift',
            'nav_sub'      => 'Sewa Forklift',
            'nav_label'    => null,
        ]);
    }
}
