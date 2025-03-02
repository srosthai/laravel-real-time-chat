<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->tinyInteger('is_online')->default(0)->nullable();
            $table->tinyInteger('is_active')->default(0)->nullable();
            $table->text('about')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('activation_code')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->tinyInteger('is_system')->default(0)->nullable();
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
};
