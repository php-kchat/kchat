<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat_widgets', function (Blueprint $table) {
            $table->string('language', 10)->default('en')->after('position');
        });
    }

    public function down()
    {
        Schema::table('chat_widgets', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};