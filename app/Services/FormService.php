<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Cdc;
use Illuminate\Support\Facades\DB;

class FormService
{
    public function __construct(
        private CdcDataBuilder $cdcDataBuilder,
        private FieldsManager $fieldsManager,
    ) {}

    public function createFormWithCdc(array $validated, int $userId): Form
    {
        return DB::transaction(function () use ($validated, $userId) {
            $form = Form::create([
                'name' => $validated['titre_projet'],
                'user_id' => $userId,
            ]);

            $cdcData = $this->cdcDataBuilder->build($validated);
            $cdcData = $this->fieldsManager->createCustomFields($form, $validated['fields'] ?? [], $cdcData);

            Cdc::create([
                'title' => $validated['titre_projet'],
                'data' => $cdcData,
                'form_id' => $form->id,
                'user_id' => $userId,
            ]);

            return $form;
        });
    }

    public function updateFormWithCdc(Form $form, array $validated, int $userId): void
    {
        DB::transaction(function () use ($form, $validated, $userId) {
            $form->update(['name' => $validated['titre_projet']]);

            $cdcData = $this->cdcDataBuilder->build($validated);
            $cdcData = $this->fieldsManager->updateCustomFields($form, $validated['fields'] ?? [], $cdcData);
            $cdcData = $this->fieldsManager->createCustomFields($form, $validated['new_fields'] ?? [], $cdcData);

            if (!empty($validated['deleted_fields'])) {
                $this->fieldsManager->deleteFields($form, $validated['deleted_fields']);
            }

            $form->cdc()->updateOrCreate(
                ['form_id' => $form->id],
                [
                    'title' => $validated['titre_projet'],
                    'data' => $cdcData,
                    'user_id' => $userId,
                ]
            );
        });
    }

    public function getPrefillDataForEdit(Form $form): array
    {
    $cdc = $form->cdc;
        $cdcData = $cdc?->data ?? [];

        return [
            'date_debut' => $cdcData['date_debut'] ?? null,
            'date_fin' => $cdcData['date_fin'] ?? null,
            'heure_matin_debut' => $cdcData['heure_matin_debut'] ?? null,
            'heure_matin_fin' => $cdcData['heure_matin_fin'] ?? null,
            'heure_aprem_debut' => $cdcData['heure_aprem_debut'] ?? null,
            'heure_aprem_fin' => $cdcData['heure_aprem_fin'] ?? null,
            'nombre_heures' => $cdcData['nombre_heures'] ?? null,
            'planning_analyse' => $cdcData['planning_analyse'] ?? '',
            'planning_implementation' => $cdcData['planning_implementation'] ?? '',
            'planning_tests' => $cdcData['planning_tests'] ?? '',
            'planning_documentation' => $cdcData['planning_documentation'] ?? '',
        ];
    }
}
