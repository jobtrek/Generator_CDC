<?php

namespace Database\Factories;

use App\Models\FieldType;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldTypeFactory extends Factory
{
    protected $model = FieldType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Text', 'Email', 'Date', 'Textarea', 'Select', 'Number', 'Checkbox']),
            'input_type' => fake()->randomElement(['text', 'email', 'date', 'textarea', 'select', 'number', 'checkbox']),
            'validation_rules' => ['required' => fake()->boolean()],
        ];
    }

    public function textType(): static
    {
        return $this->state(fn () => [
            'name' => 'Text',
            'input_type' => 'text',
            'validation_rules' => ['required' => true, 'max' => 255],
        ]);
    }

    public function emailType(): static
    {
        return $this->state(fn () => [
            'name' => 'Email',
            'input_type' => 'email',
            'validation_rules' => ['required' => true, 'email' => true],
        ]);
    }

    public function dateType(): static
    {
        return $this->state(fn () => [
            'name' => 'Date',
            'input_type' => 'date',
            'validation_rules' => ['required' => true, 'date' => true],
        ]);
    }

    public function textareaType(): static
    {
        return $this->state(fn () => [
            'name' => 'Textarea',
            'input_type' => 'textarea',
            'validation_rules' => ['required' => false],
        ]);
    }
}
