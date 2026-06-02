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
        // messages: queries filter by conversation_id + ORDER BY id DESC, also filter by user_id + created_at
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['conversation_id', 'id'], 'messages_conversation_id_id_index');
            $table->index(['user_id', 'created_at'], 'messages_user_id_created_at_index');
        });

        // participants: queried by (conversation_id, user_id) constantly, and joined on conversation_id
        Schema::table('participants', function (Blueprint $table) {
            $table->unique(['user_id', 'conversation_id'], 'participants_user_conversation_unique');
        });

        // conversations: message_id is used in JOIN with messages
        Schema::table('conversations', function (Blueprint $table) {
            $table->index('message_id', 'conversations_message_id_index');
        });

        // settings: queried by key
        Schema::table('settings', function (Blueprint $table) {
            $table->index('key', 'settings_key_index');
        });

        // activities: queried by uid
        Schema::table('activities', function (Blueprint $table) {
            $table->index('uid', 'activities_uid_index');
        });

        // status: queried by uid
        Schema::table('status', function (Blueprint $table) {
            $table->index('uid', 'status_uid_index');
        });

        // users: queried by email (search) and status
        Schema::table('users', function (Blueprint $table) {
            $table->index('email', 'users_email_index');
            $table->index('status', 'users_status_index');
        });

        // files: queried by conversation_id
        Schema::table('files', function (Blueprint $table) {
            $table->index('conversation_id', 'files_conversation_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_conversation_id_id_index');
            $table->dropIndex('messages_user_id_created_at_index');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropUnique('participants_user_conversation_unique');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex('conversations_message_id_index');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex('settings_key_index');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex('activities_uid_index');
        });

        Schema::table('status', function (Blueprint $table) {
            $table->dropIndex('status_uid_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_email_index');
            $table->dropIndex('users_status_index');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->dropIndex('files_conversation_id_index');
        });
    }
};
