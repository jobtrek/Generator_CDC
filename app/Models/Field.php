<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'placeholder',
        'is_required',
        'order_index',
        'options',
        'form_id',
        'field_type_id'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array'
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function fieldType(): BelongsTo
    {
        return $this->belongsTo(FieldType::class);
    }
}
