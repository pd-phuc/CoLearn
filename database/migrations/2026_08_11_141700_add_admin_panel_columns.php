<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('banned_at')->nullable()->after('remember_token');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('is_active');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('rejection_reason');
            $table->string('reviewed_by', 26)->nullable()->after('reviewed_at');
            $table->boolean('is_featured')->default(false)->after('reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('banned_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'reviewed_at', 'reviewed_by', 'is_featured']);
        });
    }
};
