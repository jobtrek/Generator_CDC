<?php

namespace App\Http\Controllers;

use App\Models\Cdc;
use App\Models\Form;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CdcController extends Controller
{
    use AuthorizesRequests;
    public function create(Request $request)
    {
        $formId = $request->query('form_id');

        if (! $formId) {
            return redirect()->route('forms.create')
                ->with('info', 'Créez un nouveau formulaire pour générer un CDC.');
        }

        $form = Form::with('fields.fieldType')->findOrFail($formId);
        $this->authorize('view', $form);

        $cdc = $form->cdc;

        if ($cdc) {
            return redirect()->route('forms.edit', $form)
                ->with('info', 'Modifiez le cahier des charges existant.');
        } else {
            session()->put('duplicate_form', [
                'source_form_id' => $form->id,
                'name' => $form->name.' (Copie)',
                'fields' => $form->fields->where('section', 'custom')->sortBy('order_index')->map(function ($field) {
                    return [
                        'name' => $field->name,
                        'label' => $field->label,
                        'field_type_id' => $field->field_type_id,
                        'placeholder' => $field->placeholder,
                        'is_required' => $field->is_required ?? false,
                        'options' => $field->options,
                        'order_index' => $field->order_index,
                        'value' => '',
                    ];
                })->values()->toArray(),
            ]);

            return redirect()->route('forms.create')
                ->with('info', 'Remplissez les données pour générer un nouveau CDC basé sur "'.$form->name.'"');
        }
    }
    public function download(Cdc $cdc)
    {
        $this->authorize('view', $cdc);

        try {
            $generator = new \App\Services\CdcPhpWordGenerator;
            $filePath = $generator->generate($cdc);

            $fullPath = storage_path('app/public/'.$filePath);

            if (! File::exists($fullPath)) {
                return back()->with('error', 'Le fichier n\'a pas pu être généré.');
            }
            return response()->download(
                $fullPath,
                'cdc-'.Str::slug($cdc->title).'.docx',
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Erreur génération/téléchargement CDC', [
                'cdc_id' => $cdc->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Une erreur est survenue lors du téléchargement.');
        }
    }
}
