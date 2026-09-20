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
        Schema::create('catering_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('catering_package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->unsignedInteger('guest_count');
            $table->date('event_date');
            $table->string('venue');
            $table->text('dietary_notes')->nullable();
            $table->string('status')->default('submitted');
            $table->unsignedBigInteger('quoted_total_minor')->nullable();
            $table->unsignedBigInteger('deposit_minor')->nullable();
            $table->char('currency', 3)->default('NGN');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_requests');
    }
};
