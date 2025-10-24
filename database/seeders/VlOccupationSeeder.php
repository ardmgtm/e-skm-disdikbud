<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VlOccupation;

class VlOccupationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'occupation_desc' => 'PNS'],
            ['id' => 2, 'occupation_desc' => 'TNI/Polri'],
            ['id' => 3, 'occupation_desc' => 'Peg. Swasta'],
            ['id' => 4, 'occupation_desc' => 'Wiraswasta'],
            ['id' => 5, 'occupation_desc' => 'Pelajar/Mahasiswa'],
            ['id' => 99, 'occupation_desc' => 'Other'],
        ];
        foreach ($data as $row) {
            VlOccupation::updateOrCreate(['id' => $row['id']], $row);
        }
    }
}
