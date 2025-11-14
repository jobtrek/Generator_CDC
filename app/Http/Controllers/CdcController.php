<?php

namespace App\Http\Controllers;

use App\Models\Cdc;
use App\Models\Form;
use App\Services\CdcPandocGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CdcController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des CDC de l'utilisateur
     */
    public function index(Request $request)
    {
        $query = Cdc::with(['form', 'user'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('form', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $cdcs = $query->latest()->paginate(10)->withQueryString();

        return view('cdcs.index', compact('cdcs'));
    }

    /**
     * ✅ Prépare la duplication d'un formulaire pour générer un nouveau CDC
     * Redirige vers forms.create avec les données en session
     */
    public function create(Request $request)
    {
        $formId = $request->query('form_id');

        if (!$formId) {
            return redirect()->route('forms.create')
                ->with('info', 'Créez un nouveau formulaire pour générer un CDC.');
        }

        $form = Form::with('fields.fieldType')->findOrFail($formId);
        $this->authorize('view', $form);

        $cdc = $form->cdcs()->first();

        if ($cdc) {
            return redirect()->route('forms.edit', $form)
                ->with('info', 'Modifiez le cahier des charges existant.');
        } else {
            session()->put('duplicate_form', [
                'source_form_id' => $form->id,
                'name' => $form->name . ' (Copie)',
                'description' => $form->description,
                'fields' => $form->fields->where('section', 'custom')->sortBy('order_index')->map(function($field) {
                    return [
                        'name' => $field->name,
                        'label' => $field->label,
                        'field_type_id' => $field->field_type_id,
                        'placeholder' => $field->placeholder,
                        'is_required' => $field->is_required ?? false,
                        'options' => $field->options,
                        'order_index' => $field->order_index,
                        'value' => ''
                    ];
                })->values()->toArray()
            ]);

            return redirect()->route('forms.create')
                ->with('info', 'Remplissez les données pour générer un nouveau CDC basé sur "' . $form->name . '"');
        }
    }

    /**
     * ✅ Store n'est plus utilisé directement
     * La création du CDC se fait via FormController::store()
     */
    public function store(Request $request)
    {
        return redirect()->route('forms.create')
            ->with('error', 'Veuillez utiliser le formulaire de création pour générer un CDC.');
    }

    /**
     * Affiche un CDC spécifique
     */
    public function show(Cdc $cdc)
    {
        $this->authorize('view', $cdc);
        $cdc->load(['form.fields.fieldType', 'user']);

        return view('cdcs.show', compact('cdc'));
    }

    /**
     * Met à jour un CDC existant
     */
    public function update(Request $request, Cdc $cdc)
    {
        $this->authorize('update', $cdc);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|array',
        ]);

        try {
            $cdc->update([
                'title' => $validated['title'],
                'data' => $validated['data'],
            ]);

            return redirect()->route('cdcs.show', $cdc)
                ->with('success', 'CDC mis à jour avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour CDC', [
                'cdc_id' => $cdc->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * ✅ Télécharge le CDC au format Word (.docx)
     */
    public function download(Cdc $cdc, CdcPandocGenerator $generator)
    {
        $this->authorize('view', $cdc);

        try {
            $path = $generator->generate($cdc);

            $fullPath = storage_path('app/public/' . $path);

            if (!file_exists($fullPath)) {
                throw new \Exception('Le fichier généré est introuvable.');
            }

            return response()->download(
                $fullPath,
                $this->generateFileName($cdc)
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Erreur génération CDC', [
                'cdc_id' => $cdc->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la génération du document. Veuillez réessayer.');
        }
    }

    /**
     * Supprime un CDC
     */
    public function destroy(Cdc $cdc)
    {
        $this->authorize('delete', $cdc);

        try {
            $cdcTitle = $cdc->title;
            $cdc->delete();

            return redirect()->route('cdcs.index')
                ->with('success', "Le CDC \"{$cdcTitle}\" a été supprimé avec succès !");

        } catch (\Exception $e) {
            Log::error('Erreur suppression CDC', [
                'cdc_id' => $cdc->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * ✅ Génère un nom de fichier sécurisé pour le téléchargement
     */
    private function generateFileName(Cdc $cdc): string
    {
        $slug = \Illuminate\Support\Str::slug($cdc->title);
        $timestamp = now()->format('Y-m-d');

        return "{$slug}_{$timestamp}.docx";
    }
}
