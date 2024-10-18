<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50);
            $table->string('email', 250)->unique();
            $table->string('hp', 250);
            $table->string('password', 250);
            $table->string('position', 250);
            $table->string('access_level', 250)->nullable();
            $table->enum('user_status', ['active', 'inactive']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });                 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
