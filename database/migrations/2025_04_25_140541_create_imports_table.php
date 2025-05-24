<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table): void {
            $table->id();
            $table->string('status')->default('started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('task_pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->unsignedInteger('page_number');
            $table->string('status')->default('pending');    
            $table->timestamps();
            $table->unique(['task_id', 'page_number']);
        });

        Schema::create('task_page_offers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('task_page_id')->constrained('task_pages')->cascadeOnDelete();
            $table->string('reference')->index();
            $table->string('status')->default('pending');
            $table->json('payload')->nullable(); // Pode armazenar os dados da API
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'reference']); // Garante unicidade por importação
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_page_offers');
        Schema::dropIfExists('task_pages');
        Schema::dropIfExists('tasks');
    }
};
