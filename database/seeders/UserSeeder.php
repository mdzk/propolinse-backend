<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John',
                'nama_akhir' => 'Doe',
                'email' => 'admin@mail.com',
                'password' => bcrypt('admin'),
                'tgl_lhr' => '2003-11-01',
                'gender' => 'L',
                'alamat' => 'Indonesia',
                'role' => 'admin',
            ],
            [
                'name' => 'Alpha',
                'nama_akhir' => 'Doe',
                'email' => 'user@mail.com',
                'password' => bcrypt('user'),
                'tgl_lhr' => '2002-11-19',
                'gender' => 'L',
                'alamat' => 'Indonesia',
                'role' => 'user',
            ]
        ]);
    }
}
