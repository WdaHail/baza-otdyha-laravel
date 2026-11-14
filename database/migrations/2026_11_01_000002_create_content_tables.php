<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('Отдых');
            $table->string('short_description', 255);
            $table->text('description');
            $table->unsignedInteger('price_per_hour');
            $table->unsignedInteger('capacity')->default(10);
            $table->string('cover_url')->nullable();
            $table->string('icon', 30)->default('tree');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('extras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->string('icon', 30)->default('plus-circle');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('site_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('extras');
        Schema::dropIfExists('services');
    }
};
