<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldType;

class FieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fieldTypes = [
            [
                'name' => 'Texte court',
                'input_type' => 'text',
                'validation_rules' => ['string', 'max:255']
            ],
            [
                'name' => 'Texte long',
                'input_type' => 'textarea',
                'validation_rules' => ['string']
            ],
            [
                'name' => 'Email',
                'input_type' => 'email',
                'validation_rules' => ['email']
            ],
            [
                'name' => 'Numéro',
                'input_type' => 'number',
                'validation_rules' => ['numeric']
            ],
            [
                'name' => 'Date',
                'input_type' => 'date',
                'validation_rules' => ['date']
            ],
            [
                'name' => 'Téléphone',
                'input_type' => 'tel',
                'validation_rules' => ['string', 'max:20']
            ],
            [
                'name' => 'Sélection',
                'input_type' => 'select',
                'validation_rules' => ['string']
            ],
            [
                'name' => 'Cases à cocher',
                'input_type' => 'checkbox',
                'validation_rules' => ['array']
            ],
        ];

        foreach ($fieldTypes as $type) {
            FieldType::firstOrCreate(
                ['input_type' => $type['input_type']],
                $type
            );
        }
    }
}
