<?php

namespace App\Services;

class CdcDataBuilder
{
    public function __construct(private DateTimeFormatter $dateTimeFormatter) {}

    public function build(array $validated): array
    {
        $periodeRealisation = $this->dateTimeFormatter->buildPeriodeRealisation(
            $validated['date_debut'],
            $validated['date_fin']
        );

        $horaireTravail = $this->dateTimeFormatter->buildHoraireTravail(
            $validated['heure_matin_debut'],
            $validated['heure_matin_fin'],
            $validated['heure_aprem_debut'],
            $validated['heure_aprem_fin']
        );

        return [
            'candidat_nom' => $validated['candidat_nom'],
            'candidat_prenom' => $validated['candidat_prenom'],
            'lieu_travail' => $validated['lieu_travail'],
            'orientation' => $validated['orientation'] ?? null,

            'chef_projet_nom' => $validated['chef_projet_nom'],
            'chef_projet_prenom' => $validated['chef_projet_prenom'],
            'chef_projet_email' => $validated['chef_projet_email'],
            'chef_projet_telephone' => $validated['chef_projet_telephone'],

            'expert1_nom' => $validated['expert1_nom'],
            'expert1_prenom' => $validated['expert1_prenom'],
            'expert1_email' => $validated['expert1_email'],
            'expert1_telephone' => $validated['expert1_telephone'],

            'expert2_nom' => $validated['expert2_nom'],
            'expert2_prenom' => $validated['expert2_prenom'],
            'expert2_email' => $validated['expert2_email'],
            'expert2_telephone' => $validated['expert2_telephone'],

            'periode_realisation' => $periodeRealisation,
            'horaire_travail' => $horaireTravail,
            'nombre_heures' => $validated['nombre_heures'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'heure_matin_debut' => $validated['heure_matin_debut'],
            'heure_matin_fin' => $validated['heure_matin_fin'],
            'heure_aprem_debut' => $validated['heure_aprem_debut'],
            'heure_aprem_fin' => $validated['heure_aprem_fin'],


            'titre_projet' => $validated['titre_projet'],
            'descriptif_projet' => $validated['descriptif_projet'],
            'materiel_logiciel' => $validated['materiel_logiciel'] ?? '',
            'prerequis' => $validated['prerequis'] ?? '',
            'livrables' => $validated['livrables'] ?? '',

            'procedure' => $validated['procedure'] ?? '',
            'planning_analyse' => $validated['planning_analyse'] ?? '',
            'planning_implementation' => $validated['planning_implementation'] ?? '',
            'planning_tests' => $validated['planning_tests'] ?? '',
            'planning_documentation' => $validated['planning_documentation'] ?? '',
        ];
    }
}
