<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('headline')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('headline');
            $table->string('github_url')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('github_url');
            $table->string('facebook_url')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'headline', 'bio', 'github_url', 'linkedin_url', 'facebook_url']);
        });
    }
};
