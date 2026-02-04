<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Field;
use App\Models\FieldType;
use App\Models\Cdc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class FormController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Form::with(['user', 'fields'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
            });
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'candidat_nom' => 'required|string|max:255',
            'candidat_prenom' => 'required|string|max:255',
            'lieu_travail' => 'required|string|max:255',
            'orientation' => 'nullable|string',

            'chef_projet_nom' => 'required|string|max:255',
            'chef_projet_prenom' => 'required|string|max:255',
            'chef_projet_email' => 'required|email:rfc,dns',
            'chef_projet_telephone' => 'required|string',

            'expert1_nom' => 'required|string|max:255',
            'expert1_prenom' => 'required|string|max:255',
            'expert1_email' => 'required|email:rfc,dns',
            'expert1_telephone' => 'required|string',

            'expert2_nom' => 'required|string|max:255',
            'expert2_prenom' => 'required|string|max:255',
            'expert2_email' => 'required|email:rfc,dns',
            'expert2_telephone' => 'required|string',

            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'heure_matin_debut' => 'required|date_format:H:i',
            'heure_matin_fin' => 'required|date_format:H:i',
            'heure_aprem_debut' => 'required|date_format:H:i',
            'heure_aprem_fin' => 'required|date_format:H:i',
            'nombre_heures' => 'required|integer|min:1|max:90',

            'planning_analyse' => 'nullable|string',
            'planning_implementation' => 'nullable|string',
            'planning_tests' => 'nullable|string',
            'planning_documentation' => 'nullable|string',

            'procedure' => 'nullable|string|max:5000',

            'titre_projet' => 'required|string',
            'materiel_logiciel' => 'nullable|string',
            'prerequis' => 'nullable|string',
            'descriptif_projet' => 'required|string',
            'livrables' => 'nullable|string',

            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string|max:255',
            'fields.*.label' => 'required_with:fields|string|max:255',
            'fields.*.field_type_id' => 'required_with:fields|exists:field_types,id',
            'fields.*.value' => 'nullable|string',
        ]);

        $dateDebut = Carbon::parse($validated['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateFin = Carbon::parse($validated['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY');
        $periodeRealisation = "Du {$dateDebut} au {$dateFin}";
        $horaireTravail = $validated['heure_matin_debut'] . ' – ' . $validated['heure_matin_fin'] .
            ', ' . $validated['heure_aprem_debut'] . ' – ' . $validated['heure_aprem_fin'];

        DB::beginTransaction();

        try {
            $form = Form::create([
                'name' => $validated['titre_projet'],
                'user_id' => Auth::id(),
            ]);

            $essentialFields = [
                ['name' => 'candidat_nom', 'label' => 'Nom du candidat', 'section' => 'section_1', 'field_type_id' => 1],
                ['name' => 'candidat_prenom', 'label' => 'Prénom du candidat', 'section' => 'section_1', 'field_type_id' => 1],
                ['name' => 'titre_projet', 'label' => 'Titre du projet', 'section' => 'section_3', 'field_type_id' => 1],
            ];

            $orderIndex = 0;
            foreach ($essentialFields as $fieldStructure) {
                $form->fields()->create([
                    'name' => $fieldStructure['name'],
                    'label' => $fieldStructure['label'],
                    'field_type_id' => $fieldStructure['field_type_id'],
                    'section' => $fieldStructure['section'],
                    'placeholder' => null,
                    'is_required' => true,
                    'options' => null,
                    'order_index' => $orderIndex++,
                ]);
            }
            $cdcData = [
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

            if (isset($validated['fields']) && count($validated['fields']) > 0) {
                foreach ($validated['fields'] as $fieldData) {
                    $field = $form->fields()->create([
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

            $cdc = Cdc::create([
                'title' => $validated['titre_projet'],
                'data' => $cdcData,
                'form_id' => $form->id,
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            session()->forget('duplicate_form');

            return redirect()->route('cdcs.download', $cdc)
                ->with('success', 'Formulaire créé et CDC généré avec succès ! Téléchargement en cours...');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur création formulaire/CDC', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show(Form $form)
    {
        $this->authorize('view', $form);
        $form->load(['fields.fieldType', 'user']);
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

    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $validated = $request->validate([
            'candidat_nom' => 'required|string|max:255',
            'candidat_prenom' => 'required|string|max:255',
            'lieu_travail' => 'required|string|max:255',
            'orientation' => 'nullable|string',

            'chef_projet_nom' => 'required|string|max:255',
            'chef_projet_prenom' => 'required|string|max:255',
            'chef_projet_email' => 'required|email:rfc,dns',
            'chef_projet_telephone' => 'required|string',

            'expert1_nom' => 'required|string|max:255',
            'expert1_prenom' => 'required|string|max:255',
            'expert1_email' => 'required|email:rfc,dns',
            'expert1_telephone' => 'required|string',

            'expert2_nom' => 'required|string|max:255',
            'expert2_prenom' => 'required|string|max:255',
            'expert2_email' => 'required|email:rfc,dns',
            'expert2_telephone' => 'required|string',

            'procedure' => 'nullable|string|max:5000',

            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'heure_matin_debut' => 'required|date_format:H:i',
            'heure_matin_fin' => 'required|date_format:H:i',
            'heure_aprem_debut' => 'required|date_format:H:i',
            'heure_aprem_fin' => 'required|date_format:H:i',

            'nombre_heures' => 'required|integer|min:1|max:90',

            'planning_analyse' => 'nullable|string',
            'planning_implementation' => 'nullable|string',
            'planning_tests' => 'nullable|string',
            'planning_documentation' => 'nullable|string',

            'titre_projet' => 'required|string',
            'materiel_logiciel' => 'nullable|string',
            'prerequis' => 'nullable|string',
            'descriptif_projet' => 'required|string',
            'livrables' => 'nullable|string',

            'fields' => 'nullable|array',
            'fields.*.id' => 'nullable|exists:fields,id',
            'fields.*.name' => 'required_with:fields|string|max:255',
            'fields.*.label' => 'required_with:fields|string|max:255',
            'fields.*.value' => 'nullable|string',

            'new_fields' => 'nullable|array',
            'new_fields.*.name' => 'required_with:new_fields|string|max:255',
            'new_fields.*.label' => 'required_with:new_fields|string|max:255',
            'new_fields.*.value' => 'nullable|string',
            'new_fields.*.field_type_id' => 'required_with:new_fields|exists:field_types,id',

            'deleted_fields' => 'nullable|array',
            'deleted_fields.*' => 'exists:fields,id',
        ]);

        $dateDebut = Carbon::parse($validated['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateFin = Carbon::parse($validated['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY');
        $periodeRealisation = "Du {$dateDebut} au {$dateFin}";

        $horaireTravail = $validated['heure_matin_debut'] . ' – ' . $validated['heure_matin_fin'] .
            ', ' . $validated['heure_aprem_debut'] . ' – ' . $validated['heure_aprem_fin'];

        DB::beginTransaction();

        try {
            $form->update([
                'name' => $validated['titre_projet'],
            ]);

            $cdcData = [
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

                'planning_analyse' => $validated['planning_analyse'] ?? '',
                'planning_implementation' => $validated['planning_implementation'] ?? '',
                'planning_tests' => $validated['planning_tests'] ?? '',
                'planning_documentation' => $validated['planning_documentation'] ?? '',

                'procedure' => $request->procedure ?? '',
                'titre_projet' => $validated['titre_projet'],
                'materiel_logiciel' => $validated['materiel_logiciel'] ?? '',
                'prerequis' => $validated['prerequis'] ?? '',
                'descriptif_projet' => $validated['descriptif_projet'],
                'livrables' => $validated['livrables'] ?? '',
            ];

            if (isset($validated['fields'])) {
                foreach ($validated['fields'] as $fieldData) {
                    if (isset($fieldData['id'])) {
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
                }
            }

            if (isset($validated['new_fields']) && count($validated['new_fields']) > 0) {
                $maxOrderIndex = $form->fields()->max('order_index') ?? 0;

                foreach ($validated['new_fields'] as $newFieldData) {
                    $field = $form->fields()->create([
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

            \Log::error('Erreur mise à jour formulaire', [
                'form_id' => $form->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
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
            \Log::error('Erreur suppression formulaire', [
                'form_id' => $form->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
