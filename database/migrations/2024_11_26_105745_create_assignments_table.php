<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('lead')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Make user_id nullable
            $table->enum('agent', ['Agent1', 'Agent2', 'Agent3', 'Agent4'])->nullable();
            $table->timestamps();
        });
        

    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
