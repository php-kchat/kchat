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
        Schema::table('participants', function(Blueprint $table){
            $table->bigInteger('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('conversation_id')->unsigned()->change();
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participants', function(Blueprint $table){
            $table->dropForeign('participants_conversation_id_foreign');
            $table->dropIndex('participants_conversation_id_foreign');
            $table->dropForeign('participants_user_id_foreign');
            $table->dropIndex('participants_user_id_foreign');
            //$table->dropUnique('user_id_conversation_id_unique_key');
        });
    }
};
