<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCdcRequest;
use App\Http\Requests\UpdateCdcRequest;
use App\Models\FieldType;
use App\Models\Form;
use App\Services\FormService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FormController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private FormService $formService) {}

    public function index(Request $request)
    {
        // La vue index n'affiche que le CDC associé (statut + données) :
        // inutile de charger 'user' et tous les 'fields' de chaque formulaire.
        $query = Form::with('cdc')
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $this->applyNameSearch($query, $request->search);
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

    /**
     * Recherche par nom de formulaire.
     * PostgreSQL : utilise l'index GIN fulltext (to_tsvector('english', name)) via to_tsquery
     * avec l'opérateur préfixe « :* » pour garder le matching partiel (« dev » → « développement »).
     * Autres drivers (SQLite en test) : repli sur un LIKE insensible à la casse.
     */
    private function applyNameSearch($query, string $search): void
    {
        $search = trim($search);

        if ($search === '') {
            return;
        }

        if (DB::connection()->getDriverName() === 'pgsql') {
            $terms = array_filter(array_map(
                fn ($t) => preg_replace('/[^\p{L}\p{N}]/u', '', $t),
                preg_split('/\s+/', $search)
            ));

            if (! empty($terms)) {
                $tsquery = implode(' & ', array_map(fn ($t) => $t.':*', $terms));
                $query->whereRaw("to_tsvector('english', name) @@ to_tsquery('english', ?)", [$tsquery]);

                return;
            }
        }

        $query->whereRaw('LOWER(name) LIKE ?', ['%'.Str::lower($search).'%']);
    }

    public function create()
    {
        $fieldTypes      = FieldType::all();
        $duplicateData   = session('duplicate_form', []);
        $prefilledFields = $duplicateData['fields'] ?? [];
        $prefillData     = [];

        $draftFormId = empty($duplicateData)
            ? Form::draft(Auth::id())->latest()->value('id')
            : null;

        return view('forms.create', compact('fieldTypes', 'duplicateData', 'prefilledFields', 'prefillData', 'draftFormId'));
    }

    public function autosave(Request $request): JsonResponse
    {
        $formId = (int) $request->input('draft_form_id') ?: null;
        $data   = $request->except(['_token', '_method', 'draft_form_id']);

        try {
            $form = $this->formService->autosaveFormWithCdc($data, Auth::user(), $formId);

            return response()->json([
                'form_id'  => $form->id,
                'saved_at' => now()->format('H:i'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la sauvegarde'], 500);
        }
    }
    public function store(StoreCdcRequest $request)
    {
        try {
            $draftFormId = $request->input('draft_form_id');

            if ($draftFormId) {
                $form = Form::where('id', $draftFormId)->where('user_id', Auth::id())->firstOrFail();
                $this->authorize('update', $form);
                $this->formService->updateFormWithCdc($form, $request->validated(), Auth::user());
            } else {
                $form = $this->formService->createFormWithCdc(
                    $request->validated(),
                    Auth::user()
                );
            }
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
            $this->formService->updateFormWithCdc($form, $request->validated(), Auth::user());

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
