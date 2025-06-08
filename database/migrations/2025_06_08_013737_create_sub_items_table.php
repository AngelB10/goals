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
        Schema::create('sub_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('habit_id');
        $table->string('title');
        $table->boolean('done')->default(false);
        $table->timestamps();
        $table->foreign('habit_id')->references('id')->on('habits')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_items');
    }
};
