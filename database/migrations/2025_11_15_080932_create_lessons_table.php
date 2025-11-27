<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('difficulty_level', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])->default('A1');
            $table->integer('total_parts')->default(0);
            $table->integer('order_index')->default(0);
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
            
            $table->index('difficulty_level');
            $table->index('is_premium');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
