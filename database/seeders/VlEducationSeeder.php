<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VlEducation;

class VlEducationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'education_desc' => 'SD'],
            ['id' => 2, 'education_desc' => 'SMP'],
            ['id' => 3, 'education_desc' => 'SMA'],
            ['id' => 4, 'education_desc' => 'D1-D3'],
            ['id' => 5, 'education_desc' => 'D4/S1'],
            ['id' => 6, 'education_desc' => 'S2-S3'],
        ];
        foreach ($data as $row) {
            VlEducation::updateOrCreate(['id' => $row['id']], $row);
        }
    }
}
