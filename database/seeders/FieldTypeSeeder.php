<?php

namespace Database\Seeders;

use App\Models\FieldType;
use Illuminate\Database\Seeder;

class FieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fieldTypes = [
            [
                'name' => 'Texte court',
                'input_type' => 'text',
                'validation_rules' => ['required', 'string', 'max:255']
            ],
            [
                'name' => 'Texte long',
                'input_type' => 'textarea',
                'validation_rules' => ['required', 'string']
            ],
            [
                'name' => 'Email',
                'input_type' => 'email',
                'validation_rules' => ['required', 'email']
            ],
            [
                'name' => 'Nombre',
                'input_type' => 'number',
                'validation_rules' => ['required', 'numeric']
            ],
            [
                'name' => 'Date',
                'input_type' => 'date',
                'validation_rules' => ['required', 'date']
            ],
            [
                'name' => 'Liste déroulante',
                'input_type' => 'select',
                'validation_rules' => ['required', 'string']
            ],
            [
                'name' => 'Cases à cocher',
                'input_type' => 'checkbox',
                'validation_rules' => ['array']
            ]
        ];

        foreach ($fieldTypes as $fieldType) {
            FieldType::create($fieldType);
        }
    }
}
