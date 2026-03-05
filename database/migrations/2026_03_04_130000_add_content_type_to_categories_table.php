<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('type', 50)->default('course')->after('id');
        });

        // Existing seeds: treat these slugs as past-question categories
        DB::table('categories')
            ->whereIn('slug', ['gce-ordinary-level', 'hnd'])
            ->update(['type' => 'past_question']);

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['type', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['type', 'slug']);
            $table->unique('slug');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
