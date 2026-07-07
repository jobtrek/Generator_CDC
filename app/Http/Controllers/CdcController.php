<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCdcDocxJob;
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

        // Supprimer l'ancien fichier s'il existe, pour en générer un frais
        if ($cdc->docx_path) {
            File::delete(storage_path('app/public/'.$cdc->docx_path));
            $cdc->update(['docx_path' => null]);
        }

        GenerateCdcDocxJob::dispatch($cdc, Auth::user());

        return back()->with('info', 'Votre document est en cours de génération. Vous recevrez une notification dès qu\'il sera prêt.');
    }

    public function downloadFile(Cdc $cdc)
    {
        $this->authorize('view', $cdc);

        $fullPath = $cdc->docx_path
            ? storage_path('app/public/'.$cdc->docx_path)
            : null;

        if (! $fullPath || ! File::exists($fullPath)) {
            return back()->with('error', 'Le fichier n\'est pas encore prêt ou a expiré. Veuillez relancer la génération.');
        }

        // Marquer la notification correspondante comme lue
        Auth::user()
            ->unreadNotifications()
            ->where('data->cdc_id', $cdc->id)
            ->update(['read_at' => now()]);

        return response()->download(
            $fullPath,
            'cdc-'.Str::slug($cdc->title).'.docx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        )->deleteFileAfterSend(true);
    }
}
