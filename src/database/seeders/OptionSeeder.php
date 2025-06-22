<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionSeeder extends Seeder
{
    public function run()
    {
        $options = [
            [
                'name' => 'チャイルドシート',
                'price' => 2000,
                'is_quantity' => true,
                'description' => '小さなお子様向けのチャイルドシートです。',
                'image_path' => 'options/childseat.jpg',
            ],
            [
                'name' => 'ジュニアシート',
                'price' => 1500,
                'is_quantity' => true,
                'description' => '体重15〜36kgのお子様向けのジュニアシートです。',
                'image_path' => 'options/juniorseat.jpg',
            ],
            [
                'name' => 'ベビーカー',
                'price' => 3000,
                'is_quantity' => true,
                'description' => '折りたたみ式のベビーカーです。',
                'image_path' => 'options/stroller.jpg',
            ],
            [
                'name' => '車両保険',
                'price' => 3000,
                'is_quantity' => false,
                'description' => '事故時の修理費用を補償します。',
                'image_path' => null,
            ],
            [
                'name' => '免責補償',
                'price' => 2500,
                'is_quantity' => false,
                'description' => '自己負担金（免責額）を補償します。',
                'image_path' => null,
            ],
            [
                'name' => 'NOC補償',
                'price' => 1000,
                'is_quantity' => false,
                'description' => '休業補償（NOC）をカバーします。',
                'image_path' => null,
            ],
        ];

        foreach ($options as $option) {
            Option::updateOrCreate(
                ['name' => $option['name']], // 一意性はnameで判定
                [
                    'price' => $option['price'],
                    'is_quantity' => $option['is_quantity'],
                    'description' => $option['description'] ?? null,
                    'image_path' => $option['image_path'] ?? null,
                ]
            );
        }
    }
}