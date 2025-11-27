<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lesson_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->integer('part_number');
            $table->text('text_content');
            $table->string('audio_file');
            $table->integer('audio_duration')->nullable();
            $table->text('hints')->nullable();
            $table->text('explanation')->nullable();
            $table->timestamps();
            
            $table->unique(['lesson_id', 'part_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_parts');
    }
};
