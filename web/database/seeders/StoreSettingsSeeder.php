<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'store_name',
                'value' => 'Digital Store',
                'type' => 'text',
            ],
            [
                'key' => 'about_text',
                'value' => 'Welcome to Digital Store — your one-stop marketplace for premium digital products. We offer a curated selection of high-quality courses, eBooks, templates, software, and tools designed to help you learn, create, and grow. Every product is carefully reviewed to ensure it meets our standards of excellence. Whether you are a student, professional, entrepreneur, or creative, you will find something valuable in our collection. Start exploring today and unlock your potential with the best digital resources available online.',
                'type' => 'textarea',
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@digitalstore.com',
                'type' => 'text',
            ],
            [
                'key' => 'contact_phone',
                'value' => '01XXXXXXXXX',
                'type' => 'text',
            ],
            [
                'key' => 'copyright_text',
                'value' => '© 2026 Digital Store. All rights reserved.',
                'type' => 'text',
            ],
            [
                'key' => 'footer_text',
                'value' => 'Premium digital products for your success.',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            StoreSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
