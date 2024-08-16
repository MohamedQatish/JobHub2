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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('freelancers');
            $table->foreignId('worker_id')->constrained('freelancers')->nullable()->default(null);
            $table->foreignId('category_id')->constrained('categories');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('qualifications')->nullable();
            $table->enum('experience_lvl',['entry level','intermediate level','senior level'])->nullable();
            $table->enum('scope',['small','medium','large'])->nullable();
            $table->enum('duration',['less than 1 month','1 to 3 months','3 to 6 months','more than 6 months'])->nullable();
            $table->decimal('fixed_price')->nullable();
            $table->decimal('hourly_payment')->nullable();
            $table->enum('status',['undone','done'])->default('undone')->nullable();
            $table->integer('applicants_count')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
