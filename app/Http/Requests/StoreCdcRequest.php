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
            'lieu_travail' => 'required|string|max:255',
            'orientation' => 'nullable|string',

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
            'nombre_heures' => 'nullable|integer|min:1|max:90',

            'planning_analyse' => 'nullable|string',
            'planning_implementation' => 'nullable|string',
            'planning_tests' => 'nullable|string',
            'planning_documentation' => 'nullable|string',

            'procedure' => 'nullable|string|max:5000',

            'titre_projet' => 'required|string',
            'materiel_logiciel' => 'nullable|string',
            'prerequis' => 'nullable|string',
            'descriptif_projet' => 'required|string',
            'livrables' => 'nullable|string',

            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string|max:255',
            'fields.*.label' => 'required_with:fields|string|max:255',
            'fields.*.field_type_id' => 'required_with:fields|exists:field_types,id',
            'fields.*.value' => 'nullable|string',
            'jours_ecole' => 'nullable|array',
            'jours_ecole.*' => 'string',
        ];
    }
}
