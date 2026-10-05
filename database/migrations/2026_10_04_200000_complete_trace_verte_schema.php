<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name');
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('farming_method')->nullable();
            $table->json('crop_types')->nullable();
            $table->string('production_capacity')->nullable();
            $table->json('certifications')->nullable();
        });

        Schema::table('transformers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name');
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('transformation_type')->nullable();
            $table->text('process_description')->nullable();
            $table->string('production_capacity')->nullable();
            $table->json('certifications')->nullable();
        });

        Schema::table('distributors', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name');
            $table->string('distributor_type')->nullable();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('coverage_area')->nullable();
        });

        Schema::table('consumers', function (Blueprint $table) {
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->json('preferences')->nullable();
            $table->string('sustainability_level')->nullable();
            $table->json('dietary_restrictions')->nullable();
            $table->json('allergies')->nullable();
            $table->string('budget_range')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name');
            $table->string('barcode')->nullable()->unique();
            $table->string('sku')->nullable()->unique();
            $table->string('category')->nullable();
            $table->string('origin')->nullable();
            $table->text('ingredients')->nullable();
            $table->foreignId('producer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('transformer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft');
        });

        Schema::table('environmental_footprints', function (Blueprint $table) {
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('co2_emissions', 10, 2)->nullable();
            $table->decimal('water_usage', 12, 2)->nullable();
            $table->decimal('land_usage', 10, 2)->nullable();
            $table->unsignedTinyInteger('ai_score')->nullable();
        });

        Schema::table('supply_chain_traces', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distributor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('current_stage')->nullable();
            $table->decimal('current_location_lat', 10, 7)->nullable();
            $table->decimal('current_location_lon', 10, 7)->nullable();
            $table->json('path_history')->nullable();
            $table->string('status')->default('in_transit');
            $table->decimal('total_distance_km', 10, 2)->nullable();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_type');
            $table->string('issuer')->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('pending');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('verified_purchase')->default(false);
            $table->string('status')->default('pending');
        });

        Schema::table('alerts', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('alert_type');
            $table->string('severity')->default('medium');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('detected_at')->nullable();
            $table->string('status')->default('open');
            $table->text('resolution')->nullable();
        });

        Schema::rename('a_i_analyses', 'ai_analyses');

        Schema::table('ai_analyses', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('analysis_type')->nullable();
            $table->unsignedTinyInteger('greenwashing_score')->nullable();
            $table->string('credibility_rating')->nullable();
            $table->text('ai_summary')->nullable();
            $table->string('model_used')->nullable();
            $table->boolean('is_demo')->default(true);
        });

        Schema::table('personal_ratings', function (Blueprint $table) {
            $table->foreignId('consumer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('personalized_score')->nullable();
            $table->text('reason')->nullable();
            $table->text('recommendation_reason')->nullable();
            $table->unique(['consumer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::table('personal_ratings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('consumer_id');
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn(['personalized_score', 'reason', 'recommendation_reason']);
        });

        Schema::table('ai_analyses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn([
                'analysis_type', 'greenwashing_score', 'credibility_rating',
                'ai_summary', 'model_used', 'is_demo',
            ]);
        });

        Schema::rename('ai_analyses', 'a_i_analyses');

        Schema::table('alerts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn([
                'alert_type', 'severity', 'title', 'description',
                'detected_at', 'status', 'resolution',
            ]);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['rating', 'title', 'comment', 'verified_purchase', 'status']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn([
                'certificate_type', 'issuer', 'certificate_number',
                'issue_date', 'expiry_date', 'status',
            ]);
        });

        Schema::table('supply_chain_traces', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropConstrainedForeignId('distributor_id');
            $table->dropColumn([
                'current_stage', 'current_location_lat', 'current_location_lon',
                'path_history', 'status', 'total_distance_km',
            ]);
        });

        Schema::table('environmental_footprints', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn(['co2_emissions', 'water_usage', 'land_usage', 'ai_score']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('producer_id');
            $table->dropConstrainedForeignId('transformer_id');
            $table->dropColumn([
                'name', 'barcode', 'sku', 'category', 'origin',
                'ingredients', 'status',
            ]);
        });

        Schema::table('consumers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'preferences', 'sustainability_level', 'dietary_restrictions',
                'allergies', 'budget_range',
            ]);
        });

        Schema::table('distributors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'company_name', 'distributor_type', 'location',
                'latitude', 'longitude', 'coverage_area',
            ]);
        });

        Schema::table('transformers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'company_name', 'location', 'latitude', 'longitude',
                'transformation_type', 'process_description',
                'production_capacity', 'certifications',
            ]);
        });

        Schema::table('producers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'company_name', 'location', 'latitude', 'longitude',
                'farming_method', 'crop_types', 'production_capacity', 'certifications',
            ]);
        });
    }
};
