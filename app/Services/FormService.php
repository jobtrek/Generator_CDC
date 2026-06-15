<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Cdc;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class FormService
{
    public function __construct(
        private CdcDataBuilder $cdcDataBuilder,
        private FieldsManager $fieldsManager,
    ) {}

    public function createFormWithCdc(array $validated, User $user): Form
    {
        return DB::transaction(function () use ($validated, $user) {
            $form = new Form([
                'name' => $validated['titre_projet'],
            ]);
            $user->forms()->save($form);
            $cdcData = $this->cdcDataBuilder->build($validated);
            $cdcData = $this->fieldsManager->createCustomFields($form, $validated['fields'] ?? [], $cdcData);
            $cdc = new Cdc([
                'title' => $validated['titre_projet'],
                'data' => $cdcData,
            ]);
            $cdc->user()->associate($user);
            $form->cdc()->save($cdc);

            return $form;
        });
    }
    public function updateFormWithCdc(Form $form, array $validated, User $user): void
    {
        DB::transaction(function () use ($form, $validated, $user) {
            $form->name = $validated['titre_projet'];
            $form->save();

            $cdcData = $this->cdcDataBuilder->build($validated);
            $cdcData = $this->fieldsManager->updateCustomFields($form, $validated['fields'] ?? [], $cdcData);
            $cdcData = $this->fieldsManager->createCustomFields($form, $validated['new_fields'] ?? [], $cdcData);
            if (! empty($validated['deleted_fields'])) {
                $this->fieldsManager->deleteFields($form, $validated['deleted_fields']);
            }
            $cdc = $form->cdc ?? new Cdc;
            $cdc->fill([
                'title' => $validated['titre_projet'],
                'data' => $cdcData,
                'status' => 'terminé',
            ]);

            if (! $cdc->exists) {
                $cdc->user_id = $user->id;
            }
            $form->cdc()->save($cdc);
        });
    }

    public function autosaveFormWithCdc(array $data, User $user, ?int $formId = null): Form
    {
        return DB::transaction(function () use ($data, $user, $formId) {
            $title = $data['titre_projet'] ?? 'Brouillon sans titre';

            if ($formId) {
                $form = Form::where('id', $formId)->where('user_id', $user->id)->firstOrFail();
                $form->name = $title;
                $form->save();
                $cdc = $form->cdc ?? new Cdc;
            } else {
                $form = new Form(['name' => $title]);
                $user->forms()->save($form);
                $cdc = new Cdc;
                $cdc->user()->associate($user);
            }

            $cdc->fill([
                'title' => $title,
                'data' => $data,
                'status' => 'brouillon',
            ]);
            $form->cdc()->save($cdc);

            return $form;
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
            'pause_matin_debut' => $cdcData['pause_matin_debut'] ?? '10:30',
            'pause_matin_fin' => $cdcData['pause_matin_fin'] ?? '10:45',
            'pause_aprem_debut' => $cdcData['pause_aprem_debut'] ?? '15:00',
            'pause_aprem_fin' => $cdcData['pause_aprem_fin'] ?? '15:15',
            'nombre_heures' => $cdcData['nombre_heures'] ?? null,
            'planning_analyse' => $cdcData['planning_analyse'] ?? '',
            'planning_implementation' => $cdcData['planning_implementation'] ?? '',
            'planning_tests' => $cdcData['planning_tests'] ?? '',
            'planning_documentation' => $cdcData['planning_documentation'] ?? '',
            'jours_feries' => $cdcData['jours_feries'] ?? [],
            'jours_cours_recuperer' => (int) ($cdcData['jours_cours_recuperer'] ?? 0),
        ];
    }
}
