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
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign('conversations_to_id_foreign');
            $table->dropIndex('conversations_to_id_foreign');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->string('to_id')->change();
            $table->string('to_type')->default(\App\Models\Conversation::class)->after('to_id')->comment('1 => Message, 2 => Group Message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('to_type');
        });
    }
};
