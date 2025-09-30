<?php

namespace App\Http\Controllers;

use App\Models\FieldType;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::with('user', 'fields')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        $fieldTypes = FieldType::all();

        $sessionFields = session('form_fields', []);

        return view('forms.create', compact('fieldTypes', 'sessionFields'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.placeholder' => 'nullable|string',
            'fields.*.is_required' => 'boolean',
            'fields.*.field_type_id' => 'required|exists:field_types,id',
        ]);

        $form = Form::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'user_id' => Auth::id(),
        ]);

        foreach ($validated['fields'] as $index => $fieldData) {
            $options = null;
            if (isset($fieldData['options'])) {
                if (is_string($fieldData['options'])) {
                    $options = json_decode($fieldData['options'], true);
                } else if (is_array($fieldData['options'])) {
                    $options = $fieldData['options'];
                }
            }

            $form->fields()->create([
                'name' => $fieldData['name'],
                'label' => $fieldData['label'],
                'placeholder' => $fieldData['placeholder'] ?? null,
                'is_required' => $fieldData['is_required'] ?? false,
                'order_index' => $index,
                'field_type_id' => $fieldData['field_type_id'],
                'options' => $options,
            ]);
        }

        session()->forget('form_fields');

        return redirect()->route('forms.show', $form)
            ->with('success', 'Formulaire créé avec succès !');
    }

    public function show(Form $form)
    {
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $form->load(['fields.fieldType', 'user']);

        return view('forms.show', compact('form'));
    }

    public function edit(Form $form)
    {
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $fieldTypes = FieldType::all();
        $form->load('fields.fieldType');

        return view('forms.edit', compact('form', 'fieldTypes'));
    }

    public function update(Request $request, Form $form)
    {
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.id' => 'nullable|exists:fields,id',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.placeholder' => 'nullable|string',
            'fields.*.is_required' => 'boolean',
            'fields.*.field_type_id' => 'required|exists:field_types,id',
        ]);

        $form->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $existingFieldIds = collect($validated['fields'])
            ->pluck('id')
            ->filter()
            ->toArray();

        $form->fields()
            ->whereNotIn('id', $existingFieldIds)
            ->delete();

        foreach ($validated['fields'] as $index => $fieldData) {
            $options = null;
            if (isset($fieldData['options'])) {
                if (is_string($fieldData['options'])) {
                    $options = json_decode($fieldData['options'], true);
                } else if (is_array($fieldData['options'])) {
                    $options = $fieldData['options'];
                }
            }

            $form->fields()->updateOrCreate(
                ['id' => $fieldData['id'] ?? null],
                [
                    'name' => $fieldData['name'],
                    'label' => $fieldData['label'],
                    'placeholder' => $fieldData['placeholder'] ?? null,
                    'is_required' => $fieldData['is_required'] ?? false,
                    'order_index' => $index,
                    'field_type_id' => $fieldData['field_type_id'],
                    'options' => $options,
                ]
            );
        }

        return redirect()->route('forms.show', $form)
            ->with('success', 'Formulaire mis à jour avec succès !');
    }

    public function destroy(Form $form)
    {
        if ($form->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $form->delete();

        return redirect()->route('forms.index')
            ->with('success', 'Formulaire supprimé avec succès !');
    }
}
