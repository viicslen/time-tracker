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
            $table->string('description', 1024)->nullable()->change();
            $table->text('context')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_updates', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
            $table->dropColumn('context');
        });
    }
};
