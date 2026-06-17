<?php

namespace Database\Seeders;

use App\Models\InquirySetting;
use Illuminate\Database\Seeder;

class InquirySettingSeeder extends Seeder
{
    public function run(): void
    {
        InquirySetting::truncate();

        InquirySetting::create([
            'heading'            => 'Kami siap membantu Anda.',
            'intro'              => 'Apakah Anda memiliki pertanyaan atau membutuhkan saran personal? Tim ahli kami siap membantu Anda menemukan solusi yang tepat — secara individual dan cepat.',
            'hours'              => 'Senin hingga Jumat',
            'consultant_name'    => 'Marchelya Ariny',
            'consultant_title'   => 'Account Manager',
            'consultant_company' => 'PT Eurotrucks Mekanika Prima',
            'consultant_phone'   => '085156373175',
            'consultant_email'   => 'marchelya@eurotrucks-id.com',
            'consultant_photo'   => 'inquiry/A8BKWCq0LfrmvjcPBtz7JUDiQo1xC8Xa72yaEYTG.png',
            'instagram'          => 'https://www.instagram.com/forkliftstill',
            'tiktok'             => 'https://www.tiktok.com/@forkliftstill',
            'youtube'            => null,
            'facebook'           => null,
        ]);
    }
}
