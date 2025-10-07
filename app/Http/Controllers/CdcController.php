<?php
// app/Http/Controllers/CdcController.php

namespace App\Http\Controllers;

use App\Models\Cdc;
use App\Models\Form;
use App\Services\CdcGenerator;
use Illuminate\Support\Facades\Auth;

class CdcController extends Controller
{
    public function index()
    {
        $cdcs = Cdc::with('form')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('cdcs.index', compact('cdcs'));
    }

    public function generate(Form $form, CdcGenerator $generator)
    {
        if ($form->user_id !== Auth::id()) {
            abort(403);
        }

        $cdc = Cdc::create([
            'title' => 'CDC - ' . $form->name . ' - ' . now()->format('d/m/Y'),
            'data' => $this->getDefaultData($form),
            'form_id' => $form->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('cdcs.show', $cdc)
            ->with('success', 'CDC créé avec succès ! Vous pouvez maintenant le télécharger ou le modifier.');
    }

    private function getDefaultData(Form $form)
    {
        $data = [];
        foreach ($form->fields as $field) {
            $data[$field->name] = 'À compléter';
        }
        return $data;
    }

    public function show(Cdc $cdc)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        $cdc->load(['form.fields.fieldType', 'user']);

        return view('cdcs.show', compact('cdc'));
    }

    public function download(Cdc $cdc, CdcGenerator $generator)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $generator->generate($cdc);

        return response()->download(storage_path('app/public/' . $path));
    }

    public function destroy(Cdc $cdc)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        $cdc->delete();

        return redirect()->route('cdcs.index')
            ->with('success', 'CDC supprimé avec succès !');
    }

    public function edit(Cdc $cdc)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        $cdc->load(['form.fields.fieldType']);

        return view('cdcs.edit', compact('cdc'));
    }

    public function update(Request $request, Cdc $cdc)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = [];
        foreach ($cdc->form->fields as $field) {
            $fieldKey = 'field_' . $field->id;
            if ($request->has($fieldKey)) {
                $data[$field->name] = $request->input($fieldKey);
            }
        }

        $cdc->update([
            'title' => $validated['title'],
            'data' => $data,
        ]);

        return redirect()->route('cdcs.show', $cdc)
            ->with('success', 'CDC mis à jour avec succès !');
    }
}
