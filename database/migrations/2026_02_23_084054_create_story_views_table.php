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
        Schema::create('story_views', function (Blueprint $table) {
            $table->unsignedBigInteger('story_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            #Unique constraint: one view record per user per story
            $table->unique(['story_id', 'user_id']);

            #Foreign keys: story being viewed and the user viewing it
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_views');
    }
};
