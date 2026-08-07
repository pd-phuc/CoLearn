---
name: laravel-migration-workflow
description: >-
  Database migration best practices for Laravel. Covers creating migrations, safe schema changes,
  rollback strategies, seeding, and factory patterns. Use when working with database schema,
  creating models, or modifying table structures.
---

# Laravel Migration Workflow

## Creating New Tables

```bash
# Always use -mfc flag when creating a new model
php artisan make:model Product -mfc
# Creates: Model + Migration + Factory + Controller
```

### Migration Template
```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');  // Store in cents/VND
            $table->string('status')->default('draft'); // draft, active, archived
            $table->timestamps();
            $table->softDeletes(); // If needed

            // Indexes for frequently queried columns
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

## Modifying Existing Tables

> **RULE: NEVER modify a migration that has been run. Always create a new migration.**

```bash
php artisan make:migration add_phone_to_users_table
```

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable()->after('email');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('phone');
    });
}
```

## Naming Conventions

| Action | Migration Name |
|--------|---------------|
| Create table | `create_products_table` |
| Add column | `add_phone_to_users_table` |
| Remove column | `remove_legacy_field_from_users_table` |
| Add index | `add_index_to_products_status` |
| Create pivot | `create_product_tag_table` (alphabetical) |
| Rename column | `rename_name_to_title_in_posts_table` |

## Foreign Keys

```php
// Standard foreign key (cascading delete)
$table->foreignId('user_id')->constrained()->cascadeOnDelete();

// Nullable foreign key (set null on delete)
$table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

// Custom table name
$table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
```

## Factory Patterns

```php
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10000, 1000000),
            'status' => 'active',
        ];
    }

    // State methods for variations
    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function archived(): static
    {
        return $this->state(fn () => ['status' => 'archived']);
    }
}
```

## Seeder Patterns

```php
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create test data
        User::factory(10)
            ->has(Product::factory(5))
            ->create();
    }
}
```

## Dangerous Operations Checklist

Before running any of these, **always confirm with the user**:

| Operation | Risk | Mitigation |
|-----------|------|-----------|
| `dropColumn` | Data loss | Backup first, verify column is unused |
| `dropTable` | Data loss | Ensure no foreign keys reference it |
| `renameColumn` | Breaks queries | Search codebase for old name first |
| `change()` column type | Data truncation | Check existing data fits new type |
| `migrate:fresh` | **Drops ALL tables** | Only use in development |

## Commands Reference

```bash
php artisan migrate                # Run pending migrations
php artisan migrate:status         # Show migration status
php artisan migrate:rollback       # Rollback last batch
php artisan migrate:fresh --seed   # Drop all + re-migrate + seed (DEV ONLY)
php artisan db:seed                # Run seeders
php artisan db:seed --class=UserSeeder  # Run specific seeder
```
