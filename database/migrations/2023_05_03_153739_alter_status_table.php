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
        Schema::table('status', function(Blueprint $table){
            $table->bigInteger('uid')->unsigned()->change();
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status', function(Blueprint $table){
            $table->dropForeign('status_uid_foreign');
            $table->dropIndex('status_uid_foreign');
        });
    }
};
