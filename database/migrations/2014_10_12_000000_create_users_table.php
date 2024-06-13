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
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('other_name', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->integer('step')->nullable();
            $table->string('dob',10)->nullable();
            $table->string('phone_number')->unique();
            $table->integer('gender')->nullable();
            $table->string('profile_image')->nullable();
            // $table->tinyInteger('user_status')->comment('1=Active, 2=Block')->default(1);
            $table->enum('user_status', ['Active', 'Block'])->default('Active');
            $table->enum('same_as_industry', ['Yes', 'No'])->default('No');
            $table->enum('register_from', ['Web', 'Facebook', 'Gmail', 'Apple'])->default('Web');
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('theme_mode', ['Light', 'Dark'])->default('Light');
            $table->double('time_spent')->default(0);
            $table->timestamp('last_seen')->nullable();
            $table->rememberToken();
            $table->softDeletes();
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