<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'input_type',
        'validation_rules'
    ];

    protected $casts = [
        'validation_rules' => 'array'
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}
