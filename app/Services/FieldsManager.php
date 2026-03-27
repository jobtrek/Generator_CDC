<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Field;

class FieldsManager
{
    public function createCustomFields(Form $form, array $fieldsData, array $cdcData): array
    {
        if (empty($fieldsData)) {
            return $cdcData;
        }

        $orderIndex = $form->fields()->max('order_index') ?? 0;

        foreach ($fieldsData as $fieldData) {
            if ($this->isCustomField($fieldData['name'])) {
                $this->createField($form, $fieldData, ++$orderIndex);

                if (isset($fieldData['value']) && !empty($fieldData['value'])) {
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

        foreach ($fieldsData as $fieldData) {
            if (!isset($fieldData['id']) || !$this->isCustomField($fieldData['name'])) {
                continue;
            }

            $field = Field::find($fieldData['id']);

            if ($field && $field->form_id === $form->id) {
                $field->update([
                    'name' => $fieldData['name'],
                    'label' => $fieldData['label'],
                ]);

                if (isset($fieldData['value']) && !empty($fieldData['value'])) {
                    $cdcData[$fieldData['name']] = $fieldData['value'];
                }
            }
        }

        return $cdcData;
    }

    public function deleteFields(Form $form, array $fieldIds): void
    {
        Field::whereIn('id', $fieldIds)
            ->where('form_id', $form->id)
            ->delete();
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
        return !FormFieldsService::isStandardField($fieldName);
    }
}
