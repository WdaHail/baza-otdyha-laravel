<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->unsignedInteger('guest_count')->default(1);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->unsignedInteger('total_price');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->index(['service_id', 'start_at', 'end_at', 'status']);
        });
        Schema::create('booking_extra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('extra_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('price_at_booking');
            $table->timestamps();
            $table->unique(['booking_id', 'extra_id']);
        });
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->enum('status', ['pending', 'published', 'rejected'])->default('pending');
            $table->timestamps();
        });
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('message');
            $table->boolean('is_processed')->default(false);
            $table->timestamps();
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('booking_extra');
        Schema::dropIfExists('bookings');
    }
};
