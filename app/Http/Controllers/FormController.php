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

class FormController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Form::with(['user', 'fields'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $forms = $query->latest()->paginate(10)->withQueryString();

        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        $fieldTypes = FieldType::all();
        $duplicateData = session('duplicate_form', []);
        $prefilledFields = $duplicateData['fields'] ?? [];

        return view('forms.create', compact('fieldTypes', 'duplicateData', 'prefilledFields'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',

            'candidat_nom' => 'required|string|max:255',
            'candidat_prenom' => 'required|string|max:255',
            'lieu_travail' => 'required|string|max:255',
            'orientation' => 'nullable|string',

            'chef_projet_nom' => 'required|string|max:255',
            'chef_projet_prenom' => 'required|string|max:255',
            'chef_projet_email' => 'required|email',
            'chef_projet_telephone' => 'required|string',

            'expert1_nom' => 'required|string|max:255',
            'expert1_prenom' => 'required|string|max:255',
            'expert1_email' => 'required|email',
            'expert1_telephone' => 'required|string',

            'expert2_nom' => 'required|string|max:255',
            'expert2_prenom' => 'required|string|max:255',
            'expert2_email' => 'required|email',
            'expert2_telephone' => 'required|string',

            'periode_realisation' => 'required|string|max:255',
            'horaire_travail' => 'required|string|max:255',
            'nombre_heures' => 'required|string|max:255',

            'planning_analyse' => 'nullable|string',
            'planning_implementation' => 'nullable|string',
            'planning_tests' => 'nullable|string',
            'planning_documentation' => 'nullable|string',

            'titre_projet' => 'required|string',

            'materiel_logiciel' => 'nullable|string',

            'prerequis' => 'nullable|string',

            'descriptif_projet' => 'required|string',

            'livrables' => 'nullable|string',

            'point_technique_1' => 'nullable|string',
            'point_technique_2' => 'nullable|string',
            'point_technique_3' => 'nullable|string',
            'point_technique_4' => 'nullable|string',
            'point_technique_5' => 'nullable|string',
            'point_technique_6' => 'nullable|string',
            'point_technique_7' => 'nullable|string',

            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string|max:255',
            'fields.*.label' => 'required_with:fields|string|max:255',
            'fields.*.field_type_id' => 'required_with:fields|exists:field_types,id',
            'fields.*.value' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $form = Form::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->has('is_active'),
                'user_id' => Auth::id(),
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

                'periode_realisation' => $validated['periode_realisation'],
                'horaire_travail' => $validated['horaire_travail'],
                'nombre_heures' => $validated['nombre_heures'],

                'planning_analyse' => $validated['planning_analyse'] ?? '',
                'planning_implementation' => $validated['planning_implementation'] ?? '',
                'planning_tests' => $validated['planning_tests'] ?? '',
                'planning_documentation' => $validated['planning_documentation'] ?? '',

                'titre_projet' => $validated['titre_projet'],

                'materiel_logiciel' => $validated['materiel_logiciel'] ?? '',

                'prerequis' => $validated['prerequis'] ?? '',

                'descriptif_projet' => $validated['descriptif_projet'],

                'livrables' => $validated['livrables'] ?? '',

                'point_technique_1' => $validated['point_technique_1'] ?? '',
                'point_technique_2' => $validated['point_technique_2'] ?? '',
                'point_technique_3' => $validated['point_technique_3'] ?? '',
                'point_technique_4' => $validated['point_technique_4'] ?? '',
                'point_technique_5' => $validated['point_technique_5'] ?? '',
                'point_technique_6' => $validated['point_technique_6'] ?? '',
                'point_technique_7' => $validated['point_technique_7'] ?? '',

            ];

            if (isset($validated['fields'])) {
                foreach ($validated['fields'] as $index => $fieldData) {
                    $field = $form->fields()->create([
                        'name' => $fieldData['name'],
                        'label' => $fieldData['label'],
                        'field_type_id' => $fieldData['field_type_id'],
                        'placeholder' => null,
                        'is_required' => false,
                        'options' => null,
                        'order_index' => $index,
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

            return redirect()->route('cdcs.show', $cdc)
                ->with('success', 'Formulaire créé et CDC généré avec succès ! Vous pouvez maintenant le télécharger.');

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
        return view('forms.edit', compact('form', 'fieldTypes'));
    }

    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'fields' => 'nullable|array',
            'fields.*.id' => 'nullable|exists:fields,id',
            'fields.*.name' => 'required_with:fields|string|max:255',
            'fields.*.label' => 'required_with:fields|string|max:255',
            'fields.*.field_type_id' => 'required_with:fields|exists:field_types,id',
            'fields.*.placeholder' => 'nullable|string',
            'fields.*.is_required' => 'boolean',
            'fields.*.options' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $form->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->has('is_active'),
            ]);

            if (isset($validated['fields'])) {
                $existingFieldIds = [];

                foreach ($validated['fields'] as $index => $fieldData) {
                    if (isset($fieldData['id']) && $fieldData['id']) {
                        $field = Field::find($fieldData['id']);
                        if ($field && $field->form_id === $form->id) {
                            $field->update([
                                'name' => $fieldData['name'],
                                'label' => $fieldData['label'],
                                'field_type_id' => $fieldData['field_type_id'],
                                'placeholder' => $fieldData['placeholder'] ?? null,
                                'is_required' => $fieldData['is_required'] ?? false,
                                'options' => $fieldData['options'] ?? null,
                                'order_index' => $index,
                            ]);
                            $existingFieldIds[] = $field->id;
                        }
                    } else {
                        $field = $form->fields()->create([
                            'name' => $fieldData['name'],
                            'label' => $fieldData['label'],
                            'field_type_id' => $fieldData['field_type_id'],
                            'placeholder' => $fieldData['placeholder'] ?? null,
                            'is_required' => $fieldData['is_required'] ?? false,
                            'options' => $fieldData['options'] ?? null,
                            'order_index' => $index,
                        ]);
                        $existingFieldIds[] = $field->id;
                    }
                }

                $form->fields()->whereNotIn('id', $existingFieldIds)->delete();
            }

            DB::commit();

            return redirect()->route('forms.show', $form)
                ->with('success', 'Formulaire mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur mise à jour formulaire', [
                'form_id' => $form->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
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
