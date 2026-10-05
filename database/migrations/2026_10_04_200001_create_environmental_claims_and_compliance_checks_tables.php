<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('environmental_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('claim_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_document')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedTinyInteger('confidence_score')->nullable();
            $table->timestamps();
        });

        Schema::create('compliance_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('environmental_claim_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('check_type')->default('document_review');
            $table->string('status')->default('pending');
            $table->text('result_summary')->nullable();
            $table->text('notes')->nullable();
            $table->date('checked_at')->nullable();
            $table->timestamps();
        });

        Schema::table('alerts', function (Blueprint $table) {
            $table->foreignId('compliance_check_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('compliance_check_id');
        });

        Schema::dropIfExists('compliance_checks');
        Schema::dropIfExists('environmental_claims');
    }
};
