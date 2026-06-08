<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Lookup helper: cari sub-kategori berdasarkan slug + parent slug
        $sub = fn (string $systemSlug, string $subSlug) => ProductCategory::whereHas(
            'parent', fn ($q) => $q->where('slug', $systemSlug)
        )->where('slug', $subSlug)->firstOrFail();

        $reachTruck    = $sub('forklift', 'reach-truck');
        $highLift      = $sub('forklift', 'high-lift-stackers');
        $electricTruck = $sub('forklift', 'electric-forklift-trucks');
        $sewaReach     = $sub('sewa-forklift', 'reach-truck');

        $products = [
            // /forklift/reach-truck
            [
                'product_category_id' => $reachTruck->id,
                'name'              => 'Reach Truck 1.4 Ton',
                'slug'              => 'reach-truck-14-ton',
                'short_description' => 'Reach truck listrik kompak untuk lorong sempit, kapasitas 1.4 ton.',
                'image'             => null,
                'specs'             => [
                    ['label' => 'Kapasitas Angkat',  'value' => '1.400 kg'],
                    ['label' => 'Tinggi Angkat Maks', 'value' => '9.500 mm'],
                    ['label' => 'Lebar Lorong Min',   'value' => '2.700 mm'],
                ],
                'is_active' => true,
            ],
            [
                'product_category_id' => $reachTruck->id,
                'name'              => 'Reach Truck 2.0 Ton',
                'slug'              => 'reach-truck-20-ton',
                'short_description' => 'Reach truck bertenaga tinggi untuk beban berat dan rak tinggi.',
                'image'             => null,
                'specs'             => [
                    ['label' => 'Kapasitas Angkat',  'value' => '2.000 kg'],
                    ['label' => 'Tinggi Angkat Maks', 'value' => '11.000 mm'],
                    ['label' => 'Lebar Lorong Min',   'value' => '2.900 mm'],
                ],
                'is_active' => true,
            ],
            // /forklift/high-lift-stackers
            [
                'product_category_id' => $highLift->id,
                'name'              => 'High Lift Stacker 1.0 Ton',
                'slug'              => 'high-lift-stacker-10-ton',
                'short_description' => 'Stacker listrik serbaguna untuk penumpukan barang di gudang.',
                'image'             => null,
                'specs'             => [
                    ['label' => 'Kapasitas Angkat',  'value' => '1.000 kg'],
                    ['label' => 'Tinggi Angkat Maks', 'value' => '3.500 mm'],
                    ['label' => 'Kecepatan Jalan',    'value' => '5 km/h'],
                ],
                'is_active' => true,
            ],
            [
                'product_category_id' => $highLift->id,
                'name'              => 'High Lift Stacker 1.5 Ton',
                'slug'              => 'high-lift-stacker-15-ton',
                'short_description' => 'Stacker kapasitas menengah untuk operasi gudang intensitas tinggi.',
                'image'             => null,
                'specs'             => [
                    ['label' => 'Kapasitas Angkat',  'value' => '1.500 kg'],
                    ['label' => 'Tinggi Angkat Maks', 'value' => '4.500 mm'],
                    ['label' => 'Kecepatan Jalan',    'value' => '6 km/h'],
                ],
                'is_active' => true,
            ],
            // /forklift/electric-forklift-trucks
            [
                'product_category_id' => $electricTruck->id,
                'name'              => 'Electric Forklift 2.5 Ton',
                'slug'              => 'electric-forklift-25-ton',
                'short_description' => 'Forklift listrik ramah lingkungan untuk operasi indoor intensitas tinggi.',
                'image'             => null,
                'specs'             => [
                    ['label' => 'Kapasitas Angkat',  'value' => '2.500 kg'],
                    ['label' => 'Tinggi Angkat Maks', 'value' => '4.700 mm'],
                    ['label' => 'Kecepatan Jalan',    'value' => '19 km/h'],
                ],
                'is_active' => true,
            ],
            [
                'product_category_id' => $electricTruck->id,
                'name'              => 'Electric Forklift 3.5 Ton',
                'slug'              => 'electric-forklift-35-ton',
                'short_description' => 'Forklift listrik berkapasitas besar untuk material handling berat.',
                'image'             => null,
                'specs'             => [
                    ['label' => 'Kapasitas Angkat',  'value' => '3.500 kg'],
                    ['label' => 'Tinggi Angkat Maks', 'value' => '5.000 mm'],
                    ['label' => 'Kecepatan Jalan',    'value' => '18 km/h'],
                ],
                'is_active' => true,
            ],
            // /sewa-forklift/reach-truck
            [
                'product_category_id' => $sewaReach->id,
                'name'              => 'Sewa Reach Truck',
                'slug'              => 'sewa-reach-truck',
                'short_description' => 'Solusi sewa fleksibel untuk kebutuhan jangka pendek tanpa komitmen jangka panjang.',
                'image'             => null,
                'specs'             => [
                    ['label' => 'Durasi Minimum',    'value' => '1 hari'],
                    ['label' => 'Kapasitas Tersedia', 'value' => '1.5 – 5 ton'],
                    ['label' => 'Termasuk Operator',  'value' => 'Opsional'],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
