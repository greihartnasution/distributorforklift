<?php

namespace Database\Seeders;

use App\Models\ContactPage;
use Illuminate\Database\Seeder;

class ContactPageSeeder extends Seeder
{
    public function run(): void
    {
        ContactPage::truncate();

        ContactPage::create([
            'heading'     => 'Kami siap membantu Anda menemukan solusi forklift yang tepat.',
            'description' => 'Ada pertanyaan tentang produk, ketersediaan unit, atau kebutuhan servis? Tim kami siap memberikan konsultasi langsung — tanpa syarat, tanpa tekanan.',
            'image'       => 'contact/YM1Nmq6IgpFfvudI6vNHXk9m8btXhQeyGtMxA8PE.webp',
            'content_left'  => <<<'HTML'
<h3 style="margin: 0px 0px 1em; line-height: 1.4; font-size: 1.5rem; color: rgb(48, 46, 44); max-width: 55em; font-family: TypeSans, Verdana, Arial, sans-serif;">Kantor Pusat</h3><p style="margin: 0px 0px 1em; max-width: 45em; color: rgb(84, 84, 84); font-family: TypeSans, Verdana, Arial, sans-serif; font-size: 20px;">PT. Distributor Forklift Indonesia<br>Jl. Raya Industri No. 45<br>Kawasan Industri Pulogadung<br>Jakarta Timur<br>13920</p><p style="margin: 0px 0px 1em; max-width: 45em; color: rgb(84, 84, 84); font-family: TypeSans, Verdana, Arial, sans-serif; font-size: 20px;"><span style="font-weight: 700;">Telepon:</span> +62 857 8007 8367</p><p style="margin: 0px 0px 1em; max-width: 45em; color: rgb(84, 84, 84); font-family: TypeSans, Verdana, Arial, sans-serif; font-size: 20px;"><span style="font-weight: 700;">WhatsApp:</span> +62 857 8007 8367</p><p style="margin: 0px 0px 1em; max-width: 45em; color: rgb(84, 84, 84); font-family: TypeSans, Verdana, Arial, sans-serif; font-size: 20px;"><span style="font-weight: 700;">Jam Operasional:</span> Senin – Jumat, 08.00 – 17.00 WIB</p>
HTML,
            'content_right' => <<<'HTML'
<h3 style="margin: 0px 0px 1em; line-height: 1.4; font-size: 1.5rem; color: rgb(48, 46, 44); max-width: 55em; font-family: TypeSans, Verdana, Arial, sans-serif;">Kontak Email</h3><p style="margin: 0px 0px 1em; max-width: 45em; color: rgb(84, 84, 84); font-family: TypeSans, Verdana, Arial, sans-serif; font-size: 20px;">Umum: <a href="mailto:info@distributorforklift.co.id" style="background-color: transparent; text-decoration: none; color: rgb(249, 105, 21);">info@distributorforklift.co.id</a><br>Penjualan: <a href="mailto:sales@distributorforklift.co.id" style="background-color: transparent; text-decoration: none; color: rgb(249, 105, 21);">sales@distributorforklift.co.id</a><br>Sewa Unit: <a href="mailto:rental@distributorforklift.co.id" style="background-color: transparent; text-decoration: none; color: rgb(249, 105, 21);">rental@distributorforklift.co.id</a><br>Pembelian: <a href="mailto:purchasing@distributorforklift.co.id" style="background-color: transparent; text-decoration: none; color: rgb(249, 105, 21);">purchasing@distributorforklift.co.id</a><br>Servis &amp; Perawatan: <a href="mailto:service@distributorforklift.co.id" style="background-color: transparent; text-decoration: none; color: rgb(249, 105, 21);">service@distributorforklift.co.id</a><br>Suku Cadang: <a href="mailto:parts@distributorforklift.co.id" style="background-color: transparent; text-decoration: none; color: rgb(249, 105, 21);">parts@distributorforklift.co.id</a><br>Unit Bekas: <a href="mailto:used@distributorforklift.co.id" style="background-color: transparent; text-decoration: none; color: rgb(249, 105, 21);">used@distributorforklift.co.id</a></p>
HTML,
            'show_inquiry' => true,
        ]);
    }
}
