<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Form;
use App\Models\FieldType;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFactory extends Factory
{
    protected $model = Field::class;

    public function definition(): array
    {
        return [
            'name' => fake()->slug(2),
            'label' => fake()->words(3, true),
            'placeholder' => fake()->sentence(),
            'is_required' => fake()->boolean(70),
            'order_index' => fake()->numberBetween(1, 10),
            'options' => null,
            'form_id' => Form::factory(),
            'field_type_id' => FieldType::factory(),
        ];
    }

    public function withSelectOptions(): static
    {
        return $this->state(fn () => [
            'options' => [
                'Option 1',
                'Option 2',
                'Option 3'
            ]
        ]);
    }
}
