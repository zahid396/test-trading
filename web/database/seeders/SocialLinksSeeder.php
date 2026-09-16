<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinksSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            [
                'platform' => 'messenger',
                'label' => 'Messenger',
                'url' => 'https://m.me/yourpage',
                'icon' => 'messenger',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'platform' => 'whatsapp',
                'label' => 'WhatsApp',
                'url' => 'https://wa.me/8801XXXXXXXXX',
                'icon' => 'whatsapp',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'platform' => 'facebook',
                'label' => 'Facebook',
                'url' => 'https://facebook.com/yourpage',
                'icon' => 'facebook',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'platform' => 'telegram',
                'label' => 'Telegram',
                'url' => 'https://t.me/yourusername',
                'icon' => 'telegram',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($links as $link) {
            SocialLink::updateOrCreate(
                ['platform' => $link['platform']],
                $link
            );
        }
    }
}
