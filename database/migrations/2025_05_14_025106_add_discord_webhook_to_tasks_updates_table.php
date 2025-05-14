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
        Schema::table('task_updates', function (Blueprint $table) {
            $table->boolean('discord_webhook_enabled')->default(true)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_updates', function (Blueprint $table) {
            $table->dropColumn('discord_webhook_enabled');
        });
    }
};
