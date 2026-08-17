<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\File;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Form $form) {
            $form->deleteCdcFiles();
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

    private function deleteCdcFiles(): void
    {
        $cdcDir = storage_path('app/public/cdc');

        if (! File::isDirectory($cdcDir) || ! $this->cdc) {
            return;
        }

        $files = File::glob($cdcDir.'/cdc-'.$this->cdc->id.'-*.docx');

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}
