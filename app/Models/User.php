<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Exceptions\InsufficientBalanceException;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'avatar', 'phone', 'headline', 'bio', 'github_url', 'linkedin_url', 'facebook_url', 'provider', 'provider_id', 'balance', 'total_deposit', 'banned_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'total_deposit' => 'decimal:2',
            'banned_at' => 'datetime',
        ];
    }

    public function hasEnoughBalance(float $amount): bool
    {
        return (float) $this->balance >= $amount;
    }

    /**
     * Deposit money into wallet with Transaction log.
     * MUST be called inside DB::transaction() with lockForUpdate().
     *
     * @throws \InvalidArgumentException if $amount <= 0
     */
    public function deposit(float $amount, string $action = 'deposit_bank', ?string $description = null, ?string $orderId = null, ?string $referenceId = null): Transaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Deposit amount must be greater than zero.');
        }

        $balanceBefore = (float) $this->balance;
        $balanceAfter = $balanceBefore + $amount;

        $this->balance = $balanceAfter;
        $this->total_deposit = (float) $this->total_deposit + $amount;
        $this->save();

        return Transaction::create([
            'user_id' => $this->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'type' => 'in',
            'action' => $action,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_id' => $referenceId,
        ]);
    }

    /**
     * Deduct money from wallet with Transaction log.
     * MUST be called inside DB::transaction() with lockForUpdate().
     *
     * @throws \InvalidArgumentException if $amount <= 0
     * @throws InsufficientBalanceException if balance < $amount
     */
    public function deduct(float $amount, string $action = 'buy_course', ?string $description = null, ?string $orderId = null, ?string $referenceId = null): Transaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Deduct amount must be greater than zero.');
        }

        if (! $this->hasEnoughBalance($amount)) {
            throw new InsufficientBalanceException;
        }

        $balanceBefore = (float) $this->balance;
        $balanceAfter = $balanceBefore - $amount;

        $this->balance = $balanceAfter;
        $this->save();

        return Transaction::create([
            'user_id' => $this->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'type' => 'out',
            'action' => $action,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_id' => $referenceId,
        ]);
    }

    /**
     * Role checking helpers.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lessonCompletions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Send password reset notification with custom localized email template.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
