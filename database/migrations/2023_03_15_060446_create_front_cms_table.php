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
        Schema::create('front_cms', function (Blueprint $table) {
            $table->id();
            $table->text('key');
            $table->text('value');
            $table->timestamps();
        });
        Artisan::call('db:seed', ['--class' => 'FrontCmsSeeder', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('front_cms');
    }
};
