<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing foreign key constraints
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['category_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
        });

        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['lesson_id']);
        });

        // Spatie permission table drop
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');

        if (DB::getDriverName() === 'pgsql') {
            // Alter users table to use UUID
            DB::statement('TRUNCATE TABLE users CASCADE;');
            DB::statement('ALTER TABLE users ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE users ALTER COLUMN id TYPE uuid USING gen_random_uuid();');
            DB::statement('ALTER TABLE users ALTER COLUMN id SET DEFAULT gen_random_uuid();');

            // Alter sessions table user_id column to use UUID
            DB::statement('TRUNCATE TABLE sessions CASCADE;');
            DB::statement('ALTER TABLE sessions ALTER COLUMN user_id TYPE uuid USING user_id::text::uuid;');

            // Alter categories table to use ULID (string 26)
            DB::statement('TRUNCATE TABLE categories CASCADE;');
            DB::statement('ALTER TABLE categories ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE categories ALTER COLUMN id TYPE varchar(26) USING id::text;');

            // Alter courses table to use ULID for id, uuid for teacher_id, ulid for category_id
            DB::statement('TRUNCATE TABLE courses CASCADE;');
            DB::statement('ALTER TABLE courses ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE courses ALTER COLUMN id TYPE varchar(26) USING id::text;');
            DB::statement('ALTER TABLE courses ALTER COLUMN teacher_id TYPE uuid USING teacher_id::text::uuid;');
            DB::statement('ALTER TABLE courses ALTER COLUMN category_id TYPE varchar(26) USING category_id::text;');

            // Alter sections table
            DB::statement('TRUNCATE TABLE sections CASCADE;');
            DB::statement('ALTER TABLE sections ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE sections ALTER COLUMN id TYPE varchar(26) USING id::text;');
            DB::statement('ALTER TABLE sections ALTER COLUMN course_id TYPE varchar(26) USING course_id::text;');

            // Alter lessons table
            DB::statement('TRUNCATE TABLE lessons CASCADE;');
            DB::statement('ALTER TABLE lessons ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE lessons ALTER COLUMN id TYPE varchar(26) USING id::text;');
            DB::statement('ALTER TABLE lessons ALTER COLUMN section_id TYPE varchar(26) USING section_id::text;');

            // Alter enrollments table
            DB::statement('TRUNCATE TABLE enrollments CASCADE;');
            DB::statement('ALTER TABLE enrollments ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE enrollments ALTER COLUMN id TYPE varchar(26) USING id::text;');
            DB::statement('ALTER TABLE enrollments ALTER COLUMN user_id TYPE uuid USING user_id::text::uuid;');
            DB::statement('ALTER TABLE enrollments ALTER COLUMN course_id TYPE varchar(26) USING course_id::text;');

            // Alter lesson_completions table
            DB::statement('TRUNCATE TABLE lesson_completions CASCADE;');
            DB::statement('ALTER TABLE lesson_completions ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE lesson_completions ALTER COLUMN id TYPE varchar(26) USING id::text;');
            DB::statement('ALTER TABLE lesson_completions ALTER COLUMN user_id TYPE uuid USING user_id::text::uuid;');
            DB::statement('ALTER TABLE lesson_completions ALTER COLUMN lesson_id TYPE varchar(26) USING lesson_id::text;');
        }

        // Recreate Spatie Permission pivot tables with UUID model_id
        $tableNames = config('permission.table_names');
        $pivotRole = 'role_id';
        $pivotPermission = 'permission_id';

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotPermission) {
            $table->unsignedBigInteger($pivotPermission);
            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->cascadeOnDelete();
            $table->primary([$pivotPermission, 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $pivotRole) {
            $table->unsignedBigInteger($pivotRole);
            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->cascadeOnDelete();
            $table->primary([$pivotRole, 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
        });

        // Re-add Foreign Key constraints
        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });

        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnDelete();
        });
    }

    public function down(): void {}
};
