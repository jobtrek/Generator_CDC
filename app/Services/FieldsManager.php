<?php

namespace App\Services;

use App\Models\Field;
use App\Models\Form;

class FieldsManager
{
    public function __construct(
        private FormFieldsService $formFieldsService,
    ) {}

    public function createCustomFields(Form $form, array $fieldsData, array $cdcData): array
    {
        if (empty($fieldsData)) {
            return $cdcData;
        }

        $orderIndex = $form->fields()->max('order_index') ?? 0;

        foreach ($fieldsData as $fieldData) {
            if ($this->isCustomField($fieldData['name'])) {
                $this->createField($form, $fieldData, ++$orderIndex);

                if (isset($fieldData['value']) && ! empty($fieldData['value'])) {
                    $cdcData[$fieldData['name']] = $fieldData['value'];
                }
            }
        }

        return $cdcData;
    }

    public function updateCustomFields(Form $form, array $fieldsData, array $cdcData): array
    {
        if (empty($fieldsData)) {
            return $cdcData;
        }

        // Précharge tous les champs concernés en une seule requête (évite le N+1 du find() en boucle)
        $ids = array_filter(array_column($fieldsData, 'id'));
        $existingFields = $form->fields()->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($fieldsData as $fieldData) {
            if (! isset($fieldData['id']) || ! $this->isCustomField($fieldData['name'])) {
                continue;
            }

            $field = $existingFields->get($fieldData['id']);

            if ($field) {
                $field->update([
                    'name' => $fieldData['name'],
                    'label' => $fieldData['label'],
                ]);

                if (isset($fieldData['value']) && ! empty($fieldData['value'])) {
                    $cdcData[$fieldData['name']] = $fieldData['value'];
                }
            }
        }

        return $cdcData;
    }

    public function deleteFields(Form $form, array $fieldIds): void
    {
        $form->fields()->whereIn('id', $fieldIds)->delete();
    }

    private function createField(Form $form, array $fieldData, int $orderIndex): Field
    {
        return $form->fields()->create([
            'name' => $fieldData['name'],
            'label' => $fieldData['label'],
            'field_type_id' => $fieldData['field_type_id'],
            'placeholder' => null,
            'is_required' => false,
            'options' => null,
            'section' => 'custom',
            'order_index' => $orderIndex,
        ]);
    }

    private function isCustomField(string $fieldName): bool
    {
        return ! FormFieldsService::isStandardField($fieldName);
    }
}
