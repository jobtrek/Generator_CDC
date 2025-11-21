<?php

namespace App\Http\Controllers;

use App\Models\Cdc;
use App\Services\CdcPandocGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CdcController extends Controller
{
    use AuthorizesRequests;

    /**
     * ✅ Prépare la duplication d'un formulaire pour générer un nouveau CDC
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
     * ✅ Store n'est plus utilisé (CDC créé via FormController)
     */
    public function store(Request $request)
    {
        return redirect()->route('forms.create')
            ->with('error', 'Veuillez utiliser le formulaire de création pour générer un CDC.');
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
                ->with('error', 'Une erreur est survenue lors de la génération du document.');
        }
    }

    /**
     * ✅ Génère un nom de fichier sécurisé
     */
    private function generateFileName(Cdc $cdc): string
    {
        $slug = \Illuminate\Support\Str::slug($cdc->title);
        $timestamp = now()->format('Y-m-d');
        return "{$slug}_{$timestamp}.docx";
    }
}
