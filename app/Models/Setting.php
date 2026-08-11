<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasUlids;

    protected $fillable = [
        'group',
        'key',
        'value',
        'is_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
        ];
    }

    /**
     * Auto-decrypt value when reading encrypted settings.
     */
    public function getValueAttribute(?string $value): ?string
    {
        if ($value && $this->is_encrypted) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception) {
                return null;
            }
        }

        return $value;
    }
}
