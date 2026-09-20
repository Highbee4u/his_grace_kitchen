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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('daily_limit');
        });

        Schema::table('combos', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('is_active');
        });

        Schema::table('catering_packages', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('is_active');
        });

        Schema::table('special_requests', function (Blueprint $table) {
            $table->string('reference_image_url')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('combos', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('catering_packages', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('special_requests', function (Blueprint $table) {
            $table->dropColumn('reference_image_url');
        });
    }
};
