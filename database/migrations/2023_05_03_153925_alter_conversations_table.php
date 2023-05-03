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
        //Schema::table('conversations', function(Blueprint $table){
        //    $table->bigInteger('message_id')->unsigned()->change();
        //    $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        //});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::table('conversations', function(Blueprint $table){
        //    $table->dropForeign('conversations_message_id_foreign');
        //    $table->dropIndex('conversations_message_id_foreign');
        //});
    }
};
