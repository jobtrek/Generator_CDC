<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class)->orderBy('order_index');
    }

    public function cdc(): HasOne
    {
        return $this->hasOne(Cdc::class);
    }

    public function scopeDraft(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId)
            ->whereHas('cdc', fn (Builder $q) => $q->where('status', Cdc::STATUS_BROUILLON));
    }
}
