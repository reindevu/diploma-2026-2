<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = [
        'new' => 'Новый',
        'processing' => 'В обработке',
        'completed' => 'Завершен',
        'cancelled' => 'Отменен',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'subtotal',
        'bonus_spent',
        'bonus_earned',
        'total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'bonus_spent' => 'integer',
            'bonus_earned' => 'integer',
            'total' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
