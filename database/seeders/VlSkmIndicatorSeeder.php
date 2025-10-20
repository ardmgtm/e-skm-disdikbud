<?php

namespace Database\Seeders;

use App\Models\VlSkmIndicator;
use Illuminate\Database\Seeder;

class VlSkmIndicatorSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [ 'id' => 1, 'indicator_name' => 'Persyaratan' ],
            [ 'id' => 2, 'indicator_name' => 'Sistem, Mekanisme, dan Prosedur' ],
            [ 'id' => 3, 'indicator_name' => 'Waktu Penyelesaian' ],
            [ 'id' => 4, 'indicator_name' => 'Biaya/Tarif' ],
            [ 'id' => 5, 'indicator_name' => 'Produk Spesifikasi Jenis Pelayanan' ],
            [ 'id' => 6, 'indicator_name' => 'Kompetensi Pelaksana' ],
            [ 'id' => 7, 'indicator_name' => 'Perilaku Pelaksana' ],
            [ 'id' => 8, 'indicator_name' => 'Penanganan Pengaduan, Saran dan Masukan' ],
            [ 'id' => 9, 'indicator_name' => 'Sarana dan Prasarana' ],
        ];
        foreach ($data as $item) {
            VlSkmIndicator::updateOrInsert(
                ['id' => $item['id']],
                ['indicator_name' => $item['indicator_name']]
            );
        }
    }
}
