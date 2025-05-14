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
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('discord_webhook_enabled')->after('description')->default(false);
            $table->string('discord_webhook_url', 2048)->nullable()->after('discord_webhook_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('discord_webhook_enabled');
            $table->dropColumn('discord_webhook_url');
        });
    }
};
