<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class Cdc extends Model
{
    use HasFactory;

    const STATUS_BROUILLON = 'brouillon';
    const STATUS_TERMINE   = 'terminé';

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
            $cdc->deleteGeneratedFiles();
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

    private function deleteGeneratedFiles(): void
    {
        $cdcDir = storage_path('app/public/cdc');

        if (! File::isDirectory($cdcDir)) {
            return;
        }

        $files = File::glob($cdcDir.'/cdc-'.$this->id.'-*.docx');

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}
