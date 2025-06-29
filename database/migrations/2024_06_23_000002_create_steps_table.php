<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    public function up(): void
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->unsignedInteger('step_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
}
