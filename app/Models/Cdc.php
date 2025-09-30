<?php
// app/Models/Cdc.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cdc extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'data',
        'form_id',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
