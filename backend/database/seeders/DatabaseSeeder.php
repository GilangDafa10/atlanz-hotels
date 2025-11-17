<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('roles')->insert([
            [ 'id_role' => 1, 'nama_role' => 'admin' ],
            [ 'id_role' => 2, 'nama_role' => 'user' ],
        ]);

        // =========================
        // USERS
        // =========================
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@hotel.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567890',
                'id_role' => 1,
            ],
            [
                'id' => 2,
                'name' => 'User Demo',
                'email' => 'user@hotel.com',
                'password' => Hash::make('password'),
                'no_hp' => '089876543210',
                'id_role' => 2,
            ],
        ]);

        // =========================
        // JENIS KAMAR
        // =========================
        DB::table('jenis_kamar')->insert([
            [
                'id_jenis_kamar' => 1,
                'jenis_kasur' => 'Queen Bed',
                'deskripsi' => 'Kamar dengan 1 Queen Bed',
                'harga_permalam' => 350000,
                'url_gambar' => 'https://example.com/kamar1.jpg'
            ],
            [
                'id_jenis_kamar' => 2,
                'jenis_kasur' => 'Twin Bed',
                'deskripsi' => 'Kamar dengan 2 kasur single',
                'harga_permalam' => 300000,
                'url_gambar' => 'https://example.com/kamar2.jpg'
            ],
        ]);

        // =========================
        // KAMAR
        // =========================
        DB::table('kamar')->insert([
            [ 'id_kamar' => 1, 'nomor_kamar' => '101', 'id_jenis_kamar' => 1 ],
            [ 'id_kamar' => 2, 'nomor_kamar' => '102', 'id_jenis_kamar' => 1 ],
            [ 'id_kamar' => 3, 'nomor_kamar' => '103', 'id_jenis_kamar' => 2 ],
        ]);

        // =========================
        // FASILITAS
        // =========================
        DB::table('fasilitas')->insert([
            [
                'id_fasilitas' => 1,
                'nama_fasilitas' => 'Wifi',
                'icon_fasilitas' => 'fa-solid fa-wifi'
            ],
            [
                'id_fasilitas' => 2,
                'nama_fasilitas' => 'AC',
                'icon_fasilitas' => 'fa-solid fa-snowflake'
            ],
            [
                'id_fasilitas' => 3,
                'nama_fasilitas' => 'TV',
                'icon_fasilitas' => 'fa-solid fa-tv'
            ],
        ]);


        // =========================
        // FASILITAS - JENIS KAMAR
        // =========================
        DB::table('fasilitas_jenis_kamar')->insert([
            [ 'id_jenis_kamar' => 1, 'id_fasilitas' => 1 ],
            [ 'id_jenis_kamar' => 1, 'id_fasilitas' => 2 ],
            [ 'id_jenis_kamar' => 1, 'id_fasilitas' => 3 ],

            [ 'id_jenis_kamar' => 2, 'id_fasilitas' => 1 ],
            [ 'id_jenis_kamar' => 2, 'id_fasilitas' => 2 ],
        ]);

        // =========================
        // ADDITIONAL SERVICES
        // =========================
        DB::table('additional_services')->insert([
            [
                'id_service' => 1,
                'nama_service' => 'Sarapan',
                'deskripsi_service' => 'Sarapan prasmanan',
                'harga_service' => 50000,
                'url_gambar' => 'https://example.com/sarapan.jpg'
            ],
            [
                'id_service' => 2,
                'nama_service' => 'Laundry',
                'deskripsi_service' => 'Cuci dan setrika',
                'harga_service' => 30000,
                'url_gambar' => 'https://example.com/laundry.jpg'
            ],
        ]);
    }
}
