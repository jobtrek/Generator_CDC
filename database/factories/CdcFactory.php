<?php

namespace Database\Factories;

use App\Models\Cdc;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CdcFactory extends Factory
{
    protected $model = Cdc::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'data' => [
                'candidat_nom' => fake()->lastName(),
                'candidat_prenom' => fake()->firstName(),
                'date_debut' => '2026-01-15',
                'date_fin' => '2026-03-15',
                'procedure' => fake()->paragraph(),
                'titre_projet' => fake()->sentence(3),
            ],
            'form_id' => Form::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function manual(): static
    {
        return $this->state(fn () => [
            'form_id' => null,
        ]);
    }

    public function withSpecificData(array $data): static
    {
        return $this->state(fn () => [
            'data' => $data,
        ]);
    }
}
