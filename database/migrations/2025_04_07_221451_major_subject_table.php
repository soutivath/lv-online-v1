<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('major_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('major_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate entries
            $table->unique(['major_id', 'subject_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('major_subject');
    }
};
