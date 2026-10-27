<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('messages', 'reply_to_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->bigInteger('reply_to_id')->nullable()->after('post_id')->comment('the message this one answers');
                $table->timestamp('pinned_at')->nullable()->after('read_at');
                $table->timestamp('deleted_at')->nullable()->after('pinned_at')->comment('deleted for everyone by the sender');
                $table->timestamp('sender_hidden_at')->nullable()->after('deleted_at')->comment('deleted for me by the sender');
                $table->timestamp('receiver_hidden_at')->nullable()->after('sender_hidden_at')->comment('deleted for me by the receiver');
            });
        }

        if (!Schema::hasTable('message_likes')) {
            Schema::create('message_likes', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('message_id')->index();
                $table->bigInteger('user_id');
                $table->timestamps();
                $table->unique(['message_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_likes');
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['reply_to_id', 'pinned_at', 'deleted_at', 'sender_hidden_at', 'receiver_hidden_at']);
        });
    }
};
