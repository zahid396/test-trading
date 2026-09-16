<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Illuminate\Database\Seeder;

class PaymentSettingsSeeder extends Seeder
{
    public function run(): void
    {
        PaymentSetting::updateOrCreate(
            ['method' => 'bkash'],
            [
                'number' => '017XXXXXXXX',
                'account_type' => 'Personal',
                'instructions' => 'এই নম্বরে Send Money করুন। Payment করার পর আপনার Sender Number এবং Transaction ID নিচে দিন।',
                'qr_image' => null,
                'is_active' => true,
            ]
        );

        PaymentSetting::updateOrCreate(
            ['method' => 'nagad'],
            [
                'number' => '018XXXXXXXX',
                'account_type' => 'Personal',
                'instructions' => 'এই নম্বরে Send Money করুন। Payment করার পর আপনার Sender Number এবং Transaction ID নিচে দিন।',
                'qr_image' => null,
                'is_active' => true,
            ]
        );
    }
}
