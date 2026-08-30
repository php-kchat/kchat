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
        Schema::create('widget_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_uid', 64);
            $table->string('visitor_name')->nullable();
            $table->unsignedBigInteger('widget_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();

            $table->foreign('widget_id')->references('id')->on('chat_widgets')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['visitor_uid', 'widget_id']);
            $table->index(['agent_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_sessions');
    }
};
