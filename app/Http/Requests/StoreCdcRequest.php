<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCdcRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $phoneRule = ['required', 'string', 'regex:/^\+41\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}$/'];

        return [
            'candidat_nom' => 'required|string|max:255',
            'candidat_prenom' => 'required|string|max:255',
            'candidat_email' => 'required|email',
            'candidat_telephone' => $phoneRule,
            'lieu_travail' => 'required|string|max:255',
            'orientation' => 'nullable|string|max:500',

            'chef_projet_nom' => 'required|string|max:255',
            'chef_projet_prenom' => 'required|string|max:255',
            'chef_projet_email' => 'required|email',
            'chef_projet_telephone' => $phoneRule,

            'expert1_nom' => 'required|string|max:255',
            'expert1_prenom' => 'required|string|max:255',
            'expert1_email' => 'required|email',
            'expert1_telephone' => $phoneRule,

            'expert2_nom' => 'required|string|max:255',
            'expert2_prenom' => 'required|string|max:255',
            'expert2_email' => 'required|email',
            'expert2_telephone' => $phoneRule,

            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'heure_matin_debut' => 'required|date_format:H:i',
            'heure_matin_fin' => 'required|date_format:H:i',
            'heure_aprem_debut' => 'required|date_format:H:i',
            'heure_aprem_fin' => 'required|date_format:H:i',
            'pause_matin_debut' => 'nullable|date_format:H:i',
            'pause_matin_fin' => 'nullable|date_format:H:i',
            'pause_aprem_debut' => 'nullable|date_format:H:i',
            'pause_aprem_fin' => 'nullable|date_format:H:i',
            'nombre_heures' => 'nullable|integer|min:1|max:90',

            'planning_analyse' => 'nullable|string|max:20',
            'planning_implementation' => 'nullable|string|max:20',
            'planning_tests' => 'nullable|string|max:20',
            'planning_documentation' => 'nullable|string|max:20',

            'procedure' => 'nullable|string|max:5000',

            'titre_projet' => 'required|string|max:255',
            'materiel_logiciel' => 'nullable|string|max:5000',
            'prerequis' => 'nullable|string|max:5000',
            'descriptif_projet' => 'required|string|max:10000',
            'livrables' => 'nullable|string|max:5000',

            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string|max:255',
            'fields.*.label' => 'required_with:fields|string|max:255',
            'fields.*.field_type_id' => 'required_with:fields|exists:field_types,id',
            'fields.*.value' => 'nullable|string|max:5000',
            'jours_ecole' => 'nullable|array',
            'jours_ecole.*' => 'string|in:lundi,mardi,mercredi,jeudi,vendredi',
            'jours_feries' => 'nullable|string|max:2000',
            'jours_cours_recuperer' => 'nullable|integer|min:0|max:90',
        ];
    }
}
