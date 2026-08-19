<?php

namespace App\Models;

use App\Models\Traits\CleansCdcFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cdc extends  Model
{
    use HasFactory, CleansCdcFiles;

    const STATUS_DRAFT     = 'draft';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'title',
        'data',
        'form_id',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Cdc $cdc) {
            self::deleteCdcFilesFor($cdc->id);
        });
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isManual(): bool
    {
        return is_null($this->form_id);
    }
}
