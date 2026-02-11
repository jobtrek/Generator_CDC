<?php


namespace App\Services;

class FormFieldsService
{
    /**
     * Champs standards qui ne doivent PAS être stockés dans la table fields
     */
    private static array $standardFields = [
        'candidat_nom', 'candidat_prenom', 'lieu_travail', 'orientation',
        'chef_projet_nom', 'chef_projet_prenom', 'chef_projet_email', 'chef_projet_telephone',
        'expert1_nom', 'expert1_prenom', 'expert1_email', 'expert1_telephone',
        'expert2_nom', 'expert2_prenom', 'expert2_email', 'expert2_telephone',
        'date_debut', 'date_fin', 'heure_matin_debut', 'heure_matin_fin',
        'heure_aprem_debut', 'heure_aprem_fin', 'periode_realisation', 'horaire_travail',
        'nombre_heures', 'planning_analyse', 'planning_implementation',
        'planning_tests', 'planning_documentation', 'procedure', 'titre_projet',
        'materiel_logiciel', 'prerequis', 'descriptif_projet', 'livrables',
    ];

    public static function isStandardField(string $fieldName): bool
    {
        return in_array($fieldName, self::$standardFields);
    }
}
