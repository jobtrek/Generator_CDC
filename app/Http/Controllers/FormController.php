<?php

namespace App\Http\Controllers;

use App\Models\FieldType;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FormController extends Controller
{
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
        $sessionFields = session('form_fields', []);

        return view('forms.create', compact('fieldTypes', 'sessionFields'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.placeholder' => 'nullable|string|max:255',
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
            $form->fields()->create([
                'name' => $fieldData['name'],
                'label' => $fieldData['label'],
                'placeholder' => $fieldData['placeholder'] ?? null,
                'is_required' => $fieldData['is_required'] ?? false,
                'order_index' => $index,
                'field_type_id' => $fieldData['field_type_id'],
                'options' => $this->parseOptions($fieldData['options'] ?? null),
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
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.id' => 'nullable|exists:fields,id',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.placeholder' => 'nullable|string|max:255',
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

        $form->fields()->whereNotIn('id', $existingFieldIds)->delete();

        foreach ($validated['fields'] as $index => $fieldData) {
            $form->fields()->updateOrCreate(
                ['id' => $fieldData['id'] ?? null],
                [
                    'name' => $fieldData['name'],
                    'label' => $fieldData['label'],
                    'placeholder' => $fieldData['placeholder'] ?? null,
                    'is_required' => $fieldData['is_required'] ?? false,
                    'order_index' => $index,
                    'field_type_id' => $fieldData['field_type_id'],
                    'options' => $this->parseOptions($fieldData['options'] ?? null),
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

        $formName = $form->name;
        $form->delete();

        return redirect()->route('forms.index')
            ->with('success', "Le formulaire \"{$formName}\" a été supprimé avec succès !");
    }

    private function parseOptions($options)
    {
        if (is_null($options)) {
            return null;
        }

        if (is_string($options)) {
            return json_decode($options, true);
        }

        return $options;
    }
}
