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
    Schema::create('habits', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->enum('type', ['diaria', 'semanal', 'mensual', 'anual', 'única', 'recurrente']);
        $table->unsignedInteger('frequency')->default(1); 
        $table->json('days_of_week')->nullable();
        $table->date('start_date');
        $table->date('end_date')->nullable()->change();
        $table->time('time')->nullable();
        $table->unsignedBigInteger('category_id');
        $table->unsignedBigInteger('user_id');
        $table->boolean('completed')->default(false); 
        $table->integer('progress')->default(0); 
        $table->integer('target')->nullable(); 
        $table->timestamps();

        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
