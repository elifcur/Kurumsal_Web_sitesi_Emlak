<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('listing_type', 20)->default('sale')->index();
            $table->string('property_type', 40)->default('apartment')->index();
            $table->decimal('price', 14, 2)->nullable();
            $table->string('currency', 3)->default('TRY');
            $table->string('city', 80)->nullable()->index();
            $table->string('district', 80)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('rooms', 20)->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->unsignedInteger('area_gross')->nullable();
            $table->unsignedInteger('area_net')->nullable();
            $table->string('floor', 30)->nullable();
            $table->unsignedSmallInteger('building_age')->nullable();
            $table->string('heating', 60)->nullable();
            $table->boolean('furnished')->default(false);
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            $table->string('map_embed', 1000)->nullable();
            $table->foreignId('agent_id')->nullable()->constrained('team_members')->nullOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
