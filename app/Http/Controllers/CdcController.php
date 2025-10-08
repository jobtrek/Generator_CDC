<?php

namespace App\Http\Controllers;

use App\Models\Cdc;
use App\Models\Form;
use App\Services\CdcGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CdcController extends Controller
{
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

        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $cdcs = $query->latest()->paginate(10)->withQueryString();

        $forms = Auth::user()->forms()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('cdcs.index', compact('cdcs', 'forms'));
    }

    public function generate(Form $form)
    {
        if ($form->user_id !== Auth::id()) {
            abort(403);
        }

        $cdc = Cdc::create([
            'title' => $this->generateTitle($form),
            'data' => $this->getDefaultData($form),
            'form_id' => $form->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('cdcs.show', $cdc)
            ->with('success', 'Vous pouvez maintenant le télécharger.');
    }

    public function show(Cdc $cdc)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        $cdc->load(['form.fields.fieldType', 'user']);

        return view('cdcs.show', compact('cdc'));
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
                $value = $request->input($fieldKey);
                $data[$field->name] = is_array($value) ? $value : trim($value);
            }
        }

        $cdc->update([
            'title' => $validated['title'],
            'data' => $data,
        ]);

        return redirect()->route('cdcs.show', $cdc)
            ->with('success', 'CDC mis à jour avec succès !');
    }

    public function download(Cdc $cdc, CdcGenerator $generator)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $path = $generator->generate($cdc);

            return response()->download(
                storage_path('app/public/' . $path),
                $this->generateFileName($cdc)
            );
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du document : ' . $e->getMessage());
        }
    }

    public function destroy(Cdc $cdc)
    {
        if ($cdc->user_id !== Auth::id()) {
            abort(403);
        }

        $cdcTitle = $cdc->title;
        $cdc->delete();

        return redirect()->route('cdcs.index')
            ->with('success', "Le CDC \"{$cdcTitle}\" a été supprimé avec succès !");
    }

    private function generateTitle(Form $form): string
    {
        return sprintf(
            'CDC - %s - %s',
            $form->name,
            now()->format('d/m/Y')
        );
    }

    private function generateFileName(Cdc $cdc): string
    {
        $slug = \Illuminate\Support\Str::slug($cdc->title);
        return "{$slug}.docx";
    }

    private function getDefaultData(Form $form): array
    {
        $data = [];
        foreach ($form->fields as $field) {
            $data[$field->name] = 'À compléter';
        }
        return $data;
    }
}
