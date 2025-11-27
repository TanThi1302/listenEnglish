<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lesson_vocabulary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('vocabulary_id')->constrained('vocabulary')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['lesson_id', 'vocabulary_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_vocabulary');
    }
};
