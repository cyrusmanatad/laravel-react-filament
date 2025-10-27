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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('contactEmail')->nullable();
            $table->string('contactPhone')->nullable();
            $table->timestamps();
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->dropColumn('company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->json('company');
        });

        Schema::dropIfExists('companies');
    }
};
