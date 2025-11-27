<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vocabulary', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->string('pronunciation')->nullable();
            $table->text('meaning');
            $table->text('example_sentence')->nullable();
            $table->enum('difficulty_level', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])->default('A1');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vocabulary');
    }
};
