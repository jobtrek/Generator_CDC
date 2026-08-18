<?php

namespace App\Models;

use App\Models\Traits\CleansCdcFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Form extends Model
{
    use HasFactory, CleansCdcFiles;

    protected $fillable = ['name'];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Form $form) {
            if ($form->cdc) {
                self::deleteCdcFilesFor($form->cdc->id);
            }
        });
    }

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
}
