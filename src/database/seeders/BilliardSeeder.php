<?php

namespace Database\Seeders;

use App\Models\BilliardTable;
use App\Models\Floor;
use Illuminate\Database\Seeder;

class BilliardSeeder extends Seeder
{
    public function run(): void
    {
        $floor1 = Floor::updateOrCreate(
            ['floor_number' => 1],
            [
                'name' => 'Lantai 1',
                'description' => 'Area reguler dengan 1 ruangan besar dan 4 meja billiard. Cocok untuk pemain umum dan grup kecil.',
                'table_specification' => 'Meja billiard standar 9 feet, cloth halus, pencahayaan terang, dan jarak antar meja nyaman.',
                'cue_specification' => 'Stick house cue standar 18-21 oz, tersedia bridge stick, chalk, dan triangle rack.',
                'is_vip' => false,
                'is_active' => true,
            ]
        );

        $floor2 = Floor::updateOrCreate(
            ['floor_number' => 2],
            [
                'name' => 'Lantai 2',
                'description' => 'Area reguler lantai dua dengan 1 ruangan besar dan 4 meja billiard.',
                'table_specification' => 'Meja billiard standar 9 feet dengan kondisi meja kompetitif dan lampu gantung di tiap meja.',
                'cue_specification' => 'Stick billiard reguler, chalk tersedia di setiap meja, cocok untuk latihan maupun main santai.',
                'is_vip' => false,
                'is_active' => true,
            ]
        );

        $floor3 = Floor::updateOrCreate(
            ['floor_number' => 3],
            [
                'name' => 'Lantai 3 - VIP',
                'description' => 'Area VIP dengan 4 ruangan terpisah. Setiap ruangan memiliki 1 meja billiard.',
                'table_specification' => 'Meja VIP 9 feet, area privat, ruangan lebih nyaman, dan cocok untuk booking eksklusif.',
                'cue_specification' => 'Stick VIP pilihan dengan grip lebih nyaman, chalk premium, bridge stick, dan perlengkapan lengkap.',
                'is_vip' => true,
                'is_active' => true,
            ]
        );

        foreach ([$floor1, $floor2] as $floor) {
            foreach (range(1, 4) as $number) {
                BilliardTable::updateOrCreate(
                    ['floor_id' => $floor->id, 'name' => 'Meja ' . $number],
                    [
                        'room_name' => null,
                        'position_label' => match ($number) {
                            1 => 'Kiri Depan',
                            2 => 'Kanan Depan',
                            3 => 'Kiri Belakang',
                            default => 'Kanan Belakang',
                        },
                        'description' => 'Meja reguler nomor ' . $number . ' di ' . $floor->name,
                        'is_active' => true,
                    ]
                );
            }
        }

        foreach (range(1, 4) as $number) {
            BilliardTable::updateOrCreate(
                ['floor_id' => $floor3->id, 'name' => 'Meja VIP ' . $number],
                [
                    'room_name' => 'Ruangan VIP ' . $number,
                    'position_label' => 'VIP ' . $number,
                    'description' => 'Ruangan VIP ' . $number . ' dengan 1 meja billiard privat.',
                    'is_active' => true,
                ]
            );
        }
    }
}
