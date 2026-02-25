<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    #Add soft delete support to the stories table (mirrors add_soft_deletes_to_posts)
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
