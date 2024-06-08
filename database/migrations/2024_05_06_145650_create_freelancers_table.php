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
        Schema::create('freelancers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->decimal('hourly_wage')->nullable();
            // $table->string('residence')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->cascadeOnDelete();
            $table->enum('gender',['male','female']);
            $table->integer('total_jobs')->nullable();
            $table->integer('total_hours')->nullable();
            $table->decimal('total_earnings')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->decimal('rating')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancers');
    }
};
