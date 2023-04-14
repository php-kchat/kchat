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
		
		/*
		
		Schema::disableForeignKeyConstraints();
		
        Schema::create('messages', function (Blueprint $table) {
			$table->foreignKeys(['user_id', 'conversation_id'])
                  ->references(['id', 'id'])
                  ->on(['users', 'conversations'])
                  ->onDelete('cascade');
        });
		
        Schema::create('participants', function (Blueprint $table) {
			$table->foreignKeys(['user_id', 'conversation_id'])
                  ->references(['id', 'id'])
                  ->on(['users', 'conversations'])
                  ->onDelete('cascade');
        });
		
		Schema::enableForeignKeyConstraints();
		
		*/
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* -- */
    }
};
