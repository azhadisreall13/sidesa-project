<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin12345',
            'status' => 'approved',
            'role_id' => '1'
        ]);
        User::create([
            'id' => 2,
            'name' => 'Penduduk1',
            'email' => 'penduduk1@gmail.com',
            'password' => 'penduduk12345',
            'status' => 'approved',
            'role_id' => '2'
        ]);
        Resident::create([
            'user_id' => 2,
            'nik' => '1212121212121212',
            'name' => 'Ali',
            'gender' => 'Laki-laki',
            'birth_date' => '2001-03-12',
            'birth_place' => 'Boyolali',
            'address' => 'Jawa Tengah, Indonesia',
            'religion' => 'Islam',
            'marital_status' => 'Menikah',
            'occupation' => 'Karyawan',
            'phone' => '086789463524',
            'status' => 'Hidup'
        ]);
    }
}
