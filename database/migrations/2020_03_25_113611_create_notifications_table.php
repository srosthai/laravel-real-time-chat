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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('owner_id');
            $table->string('owner_type');
            $table->text('notification');
            $table->unsignedBigInteger('to_id');
            $table->boolean('is_read')->default(0);
            $table->dateTime('read_at')->nullable();
            $table->tinyInteger('message_type')->default(0);
            $table->text('file_name')->nullable();
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
        Schema::dropIfExists('notifications');
    }
};
