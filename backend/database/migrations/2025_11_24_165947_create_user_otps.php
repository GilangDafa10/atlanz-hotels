<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->integer('otp_code');
            $table->dateTime('expired_at');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('user_otps');
    }
};