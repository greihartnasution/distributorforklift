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
            'links_label'        => 'For further information please visit',
            'link_1'             => 'still.co.uk',
            'link_2'             => 'eurotrucks-id.com',
            'link_3'             => 'tiktok.com/@forkliftstill',
        ]);
    }
}
