<?php

namespace App\Otp;

use SadiqSalau\LaravelOtp\Contracts\OtpInterface as Otp;
use App\Models\TemporaryUser;
use App\Models\User;

class UserRegistrationOtp implements Otp
{
    public function __construct(
        public TemporaryUser $tempUser
    ) {}

    /**
     * Dipanggil ketika OTP berhasil diverifikasi.
     * Kembalikan data user final.
     */
    public function process(): User
    {
        // membuat user permanen
        $user = User::create([
            'name'              => $this->tempUser->name,
            'email'             => $this->tempUser->email,
            'password'          => $this->tempUser->password, // pastikan sudah hashed
            'no_hp'             => $this->tempUser->no_hp,
            'id_role'           => 2, // pastikan field ini ada
            'email_verified_at' => now(),
        ]);

        // hapus temporary record
        $this->tempUser->delete();

        return $user;
    }
}
