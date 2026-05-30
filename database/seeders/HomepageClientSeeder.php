<?php

namespace Database\Seeders;

use App\Models\HomepageClient;
use Illuminate\Database\Seeder;

class HomepageClientSeeder extends Seeder
{
    public function run(): void
    {
        HomepageClient::truncate();

        $clients = [
            [
                'name'       => 'PT. Logistik Nusantara',
                'logo'       => null,
                'website'    => null,
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'name'       => 'CV. Maju Bersama',
                'logo'       => null,
                'website'    => null,
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'name'       => 'PT. Industri Prima',
                'logo'       => null,
                'website'    => null,
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'name'       => 'PT. Artha Sentosa',
                'logo'       => null,
                'website'    => null,
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'name'       => 'CV. Trijaya Mandiri',
                'logo'       => null,
                'website'    => null,
                'sort_order' => 5,
                'is_active'  => true,
            ],
        ];

        foreach ($clients as $data) {
            HomepageClient::create($data);
        }
    }
}
