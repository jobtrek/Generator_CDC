<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCdcRequest;
use App\Http\Requests\UpdateCdcRequest;
use App\Models\FieldType;
use App\Models\Form;
use App\Services\FormService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FormController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private FormService $formService) {}

    public function index(Request $request)
    {
        $query = Form::with(['user', 'fields', 'cdc'])
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
        try {
            $form = $this->formService->createFormWithCdc(
                $request->validated(),
                Auth::id()
            );

            session()->forget('duplicate_form');

            return redirect()->route('forms.show', $form)
                ->with('success', 'Cahier des charges créé avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur création formulaire/CDC', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création.');
        }
    }

    public function show(Form $form)
    {
        $this->authorize('view', $form);
        $form->load(['fields.fieldType', 'user', 'cdc']);

        return view('forms.show', compact('form'));
    }

    public function edit(Form $form)
    {
        $this->authorize('update', $form);
        $form->load('fields.fieldType');
        $fieldTypes = FieldType::all();
        $prefillData = $this->formService->getPrefillDataForEdit($form);

        return view('forms.edit', compact('form', 'fieldTypes', 'prefillData'));
    }

    public function update(UpdateCdcRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        try {
            $this->formService->updateFormWithCdc($form, $request->validated(), Auth::id());

            return redirect()->route('forms.show', $form)
                ->with('success', 'Formulaire et CDC mis à jour avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour formulaire', [
                'form_id' => $form->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', "Une erreur est survenue lors de la suppression du formulaire \"{$formName}\".");
        }
    }
}
