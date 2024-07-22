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
        Schema::create('company_job_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_job_id')->constrained('companies');
            $table->foreignId('freelancer_id')->constrained('freelancers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_job_applicants');
    }
};
