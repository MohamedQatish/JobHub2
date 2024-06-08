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
        Schema::create('company_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('vacancies')->nullable();
            $table->enum('scope',['small','medium','large'])->nullable();
            $table->enum('work_schedule', ['Full-time', 'Part-time'])->nullable();
            $table->enum('price_type',['hourly', 'fixed']);
            $table->decimal('hourly_rate_min', 10, 2)->nullable();
            $table->decimal('hourly_rate_max', 10, 2)->nullable();
            $table->decimal('fixed_rate', 10, 2)->nullable();

            $table->index('price_type');
            $table->index('scope');
            $table->index(['hourly_rate_min', 'hourly_rate_max']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_jobs');
    }
};
