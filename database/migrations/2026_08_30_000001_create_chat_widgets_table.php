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
        Schema::create('chat_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('title')->default('Chat with us');
            $table->string('icon')->default('fa-comments');
            $table->string('department');
            $table->string('color', 20)->default('#007bff');
            $table->string('position', 10)->default('right');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('chat_widgets');
    }
};
