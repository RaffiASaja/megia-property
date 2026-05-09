<?php

namespace Database\Seeders;

use App\Models\Tanah;
use Illuminate\Database\Seeder;

class TanahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            
        ];

        foreach ($items as $item) {
            Tanah::updateOrCreate(
                ['kode_tanah' => $item['kode_tanah']],
                $item
            );
        }
    }
}
