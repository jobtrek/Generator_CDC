<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Field;
use App\Models\FieldType;
use App\Models\Cdc;
use App\Services\FormFieldsService;
use App\Http\Requests\StoreCdcRequest;
use App\Http\Requests\UpdateCdcRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FormController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Form::with(['user', 'fields', 'cdcs'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = Str::lower($request->search);
            $query->whereFullText('name', $search);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $forms = $query->latest()->paginate(8)->withQueryString();

        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        $fieldTypes = FieldType::all();
        $duplicateData = session('duplicate_form', []);
        $prefilledFields = $duplicateData['fields'] ?? [];
        $prefillData = [];

        return view('forms.create', compact('fieldTypes', 'duplicateData', 'prefilledFields', 'prefillData'));
    }

    public function store(StoreCdcRequest $request)
    {
        $validated = $request->validated();

        $dateDebut = Carbon::parse($validated['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateFin = Carbon::parse($validated['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY');
        $periodeRealisation = "Du {$dateDebut} au {$dateFin}";
        $horaireTravail = $validated['heure_matin_debut'] . ' — ' . $validated['heure_matin_fin'] .
            ', ' . $validated['heure_aprem_debut'] . ' — ' . $validated['heure_aprem_fin'];

        DB::beginTransaction();

        try {
            $form = Form::create([
                'name' => $validated['titre_projet'],
                'user_id' => Auth::id(),
            ]);

            $cdcData = $this->buildCdcData($validated, $periodeRealisation, $horaireTravail);

            $orderIndex = 0;
            if (isset($validated['fields']) && count($validated['fields']) > 0) {
                foreach ($validated['fields'] as $fieldData) {
                    if (!FormFieldsService::isStandardField($fieldData['name'])) {
                        $form->fields()->create([
                            'name' => $fieldData['name'],
                            'label' => $fieldData['label'],
                            'field_type_id' => $fieldData['field_type_id'],
                            'placeholder' => null,
                            'is_required' => false,
                            'options' => null,
                            'section' => 'custom',
                            'order_index' => $orderIndex++,
                        ]);

                        if (isset($fieldData['value']) && !empty($fieldData['value'])) {
                            $cdcData[$fieldData['name']] = $fieldData['value'];
                        }
                    }
                }
            }

            Cdc::create([
                'title' => $validated['titre_projet'],
                'data' => $cdcData,
                'form_id' => $form->id,
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            session()->forget('duplicate_form');

            return redirect()->route('forms.show', $form)
                ->with('success', 'Cahier des charges créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur création formulaire/CDC', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création.');
        }
    }

    public function show(Form $form)
    {
        $this->authorize('view', $form);
        $form->load(['fields.fieldType', 'user', 'cdcs']);
        return view('forms.show', compact('form'));
    }

    public function edit(Form $form)
    {
        $this->authorize('update', $form);
        $form->load('fields.fieldType');
        $fieldTypes = FieldType::all();

        $cdc = $form->cdcs()->first();
        $cdcData = $cdc->data ?? [];

        $prefillData = [
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

        return view('forms.edit', compact('form', 'fieldTypes', 'prefillData'));
    }

    public function update(UpdateCdcRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        $validated = $request->validated();

        $dateDebut = Carbon::parse($validated['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateFin = Carbon::parse($validated['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY');
        $periodeRealisation = "Du {$dateDebut} au {$dateFin}";
        $horaireTravail = $validated['heure_matin_debut'] . ' — ' . $validated['heure_matin_fin'] .
            ', ' . $validated['heure_aprem_debut'] . ' — ' . $validated['heure_aprem_fin'];

        DB::beginTransaction();

        try {
            $form->update([
                'name' => $validated['titre_projet'],
            ]);

            $cdcData = $this->buildCdcData($validated, $periodeRealisation, $horaireTravail);

            if (isset($validated['fields'])) {
                foreach ($validated['fields'] as $fieldData) {
                    if (isset($fieldData['id'])) {
                        $field = Field::find($fieldData['id']);
                        if ($field && $field->form_id === $form->id && !FormFieldsService::isStandardField($fieldData['name'])) {
                            $field->update([
                                'name' => $fieldData['name'],
                                'label' => $fieldData['label'],
                            ]);

                            if (isset($fieldData['value']) && !empty($fieldData['value'])) {
                                $cdcData[$fieldData['name']] = $fieldData['value'];
                            }
                        }
                    }
                }
            }

            if (isset($validated['new_fields']) && count($validated['new_fields']) > 0) {
                $maxOrderIndex = $form->fields()->max('order_index') ?? 0;

                foreach ($validated['new_fields'] as $newFieldData) {
                    if (!FormFieldsService::isStandardField($newFieldData['name'])) {
                        $form->fields()->create([
                            'name' => $newFieldData['name'],
                            'label' => $newFieldData['label'],
                            'field_type_id' => $newFieldData['field_type_id'],
                            'section' => 'custom',
                            'placeholder' => null,
                            'is_required' => false,
                            'options' => null,
                            'order_index' => ++$maxOrderIndex,
                        ]);

                        if (isset($newFieldData['value']) && !empty($newFieldData['value'])) {
                            $cdcData[$newFieldData['name']] = $newFieldData['value'];
                        }
                    }
                }
            }

            if (isset($validated['deleted_fields']) && count($validated['deleted_fields']) > 0) {
                Field::whereIn('id', $validated['deleted_fields'])
                    ->where('form_id', $form->id)
                    ->delete();
            }

            $cdc = $form->cdcs()->first();
            if ($cdc) {
                $cdc->update([
                    'title' => $validated['titre_projet'],
                    'data' => $cdcData,
                ]);
            } else {
                Cdc::create([
                    'title' => $validated['titre_projet'],
                    'data' => $cdcData,
                    'form_id' => $form->id,
                    'user_id' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('forms.show', $form)
                ->with('success', 'Formulaire et CDC mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur mise à jour formulaire', [
                'form_id' => $form->id,
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function destroy(Form $form)
    {
        $this->authorize('delete', $form);

        $formName = $form->name;

        try {
            $form->delete();

            return redirect()->route('forms.index')
                ->with('success', "Le formulaire \"{$formName}\" a été supprimé avec succès !");

        } catch (\Exception $e) {
            Log::error('Erreur suppression formulaire', [
                'form_id' => $form->id,
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', "Une erreur est survenue lors de la suppression du formulaire \"{$formName}\".");
        }
    }
    private function buildCdcData(array $validated, string $periodeRealisation, string $horaireTravail): array
    {
        return [
            'candidat_nom' => $validated['candidat_nom'],
            'candidat_prenom' => $validated['candidat_prenom'],
            'lieu_travail' => $validated['lieu_travail'],
            'orientation' => $validated['orientation'] ?? null,

            'chef_projet_nom' => $validated['chef_projet_nom'],
            'chef_projet_prenom' => $validated['chef_projet_prenom'],
            'chef_projet_email' => $validated['chef_projet_email'],
            'chef_projet_telephone' => $validated['chef_projet_telephone'],

            'expert1_nom' => $validated['expert1_nom'],
            'expert1_prenom' => $validated['expert1_prenom'],
            'expert1_email' => $validated['expert1_email'],
            'expert1_telephone' => $validated['expert1_telephone'],

            'expert2_nom' => $validated['expert2_nom'],
            'expert2_prenom' => $validated['expert2_prenom'],
            'expert2_email' => $validated['expert2_email'],
            'expert2_telephone' => $validated['expert2_telephone'],

            'periode_realisation' => $periodeRealisation,
            'horaire_travail' => $horaireTravail,
            'nombre_heures' => $validated['nombre_heures'],

            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'heure_matin_debut' => $validated['heure_matin_debut'],
            'heure_matin_fin' => $validated['heure_matin_fin'],
            'heure_aprem_debut' => $validated['heure_aprem_debut'],
            'heure_aprem_fin' => $validated['heure_aprem_fin'],

            'procedure' => $validated['procedure'] ?? '',
            'planning_analyse' => $validated['planning_analyse'] ?? '',
            'planning_implementation' => $validated['planning_implementation'] ?? '',
            'planning_tests' => $validated['planning_tests'] ?? '',
            'planning_documentation' => $validated['planning_documentation'] ?? '',

            'titre_projet' => $validated['titre_projet'],
            'materiel_logiciel' => $validated['materiel_logiciel'] ?? '',
            'prerequis' => $validated['prerequis'] ?? '',
            'descriptif_projet' => $validated['descriptif_projet'],
            'livrables' => $validated['livrables'] ?? '',
        ];
    }
}
