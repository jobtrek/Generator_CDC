<?php

namespace Tests\Unit\Services;

use App\Services\FormFieldsService;
use PHPUnit\Framework\TestCase;

class FormFieldsServiceTest extends TestCase
{
    private array $expectedStandardFields;

    protected function setUp(): void
    {
        parent::setUp();
        $this->expectedStandardFields = [
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
    }

    public function test_get_standard_fields_returns_array(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertIsArray($result);
    }

    public function test_get_standard_fields_returns_all_standard_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertCount(35, $result);
    }

    public function test_get_standard_fields_contains_candidat_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertContains('candidat_nom', $result);
        $this->assertContains('candidat_prenom', $result);
    }

    public function test_get_standard_fields_contains_chef_projet_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertContains('chef_projet_nom', $result);
        $this->assertContains('chef_projet_prenom', $result);
        $this->assertContains('chef_projet_email', $result);
        $this->assertContains('chef_projet_telephone', $result);
    }

    public function test_get_standard_fields_contains_expert_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertContains('expert1_nom', $result);
        $this->assertContains('expert1_prenom', $result);
        $this->assertContains('expert2_nom', $result);
        $this->assertContains('expert2_prenom', $result);
    }

    public function test_get_standard_fields_contains_date_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertContains('date_debut', $result);
        $this->assertContains('date_fin', $result);
    }

    public function test_get_standard_fields_contains_horaire_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertContains('heure_matin_debut', $result);
        $this->assertContains('heure_matin_fin', $result);
        $this->assertContains('heure_aprem_debut', $result);
        $this->assertContains('heure_aprem_fin', $result);
    }

    public function test_get_standard_fields_contains_planning_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertContains('planning_analyse', $result);
        $this->assertContains('planning_implementation', $result);
        $this->assertContains('planning_tests', $result);
        $this->assertContains('planning_documentation', $result);
    }

    public function test_get_standard_fields_contains_projet_fields(): void
    {
        $result = FormFieldsService::getStandardFields();
        $this->assertContains('procedure', $result);
        $this->assertContains('titre_projet', $result);
        $this->assertContains('materiel_logiciel', $result);
        $this->assertContains('prerequis', $result);
        $this->assertContains('descriptif_projet', $result);
        $this->assertContains('livrables', $result);
    }

    public function test_is_standard_field_returns_true_for_candidat_nom(): void
    {
        $this->assertTrue(FormFieldsService::isStandardField('candidat_nom'));
    }

    public function test_is_standard_field_returns_true_for_candidat_prenom(): void
    {
        $this->assertTrue(FormFieldsService::isStandardField('candidat_prenom'));
    }

    public function test_is_standard_field_returns_true_for_date_debut(): void
    {
        $this->assertTrue(FormFieldsService::isStandardField('date_debut'));
    }

    public function test_is_standard_field_returns_true_for_date_fin(): void
    {
        $this->assertTrue(FormFieldsService::isStandardField('date_fin'));
    }

    public function test_is_standard_field_returns_true_for_titre_projet(): void
    {
        $this->assertTrue(FormFieldsService::isStandardField('titre_projet'));
    }

    public function test_is_standard_field_returns_false_for_custom_field(): void
    {
        $this->assertFalse(FormFieldsService::isStandardField('custom_field'));
    }

    public function test_is_standard_field_returns_false_for_empty_string(): void
    {
        $this->assertFalse(FormFieldsService::isStandardField(''));
    }

    public function test_is_standard_field_returns_false_for_random_string(): void
    {
        $this->assertFalse(FormFieldsService::isStandardField('random_field_name'));
    }

    public function test_is_standard_field_returns_false_for_null(): void
    {
        $this->assertFalse(FormFieldsService::isStandardField('champ_invalide'));
    }

    public function test_is_standard_field_is_case_sensitive(): void
    {
        $this->assertFalse(FormFieldsService::isStandardField('CANDIDAT_NOM'));
        $this->assertFalse(FormFieldsService::isStandardField('Candidat_nom'));
    }

    public function test_is_standard_field_with_underscore_variation(): void
    {
        $this->assertFalse(FormFieldsService::isStandardField('candidat-nom'));
    }

    public function test_all_expected_fields_are_standard(): void
    {
        foreach ($this->expectedStandardFields as $field) {
            $this->assertTrue(
                FormFieldsService::isStandardField($field),
                "Le champ {$field} devrait être un champ standard"
            );
        }
    }

    public function test_standard_fields_contains_lieu_travail(): void
    {
        $this->assertContains('lieu_travail', FormFieldsService::getStandardFields());
    }

    public function test_standard_fields_contains_orientation(): void
    {
        $this->assertContains('orientation', FormFieldsService::getStandardFields());
    }

    public function test_standard_fields_contains_periode_realisation(): void
    {
        $this->assertContains('periode_realisation', FormFieldsService::getStandardFields());
    }

    public function test_standard_fields_contains_horaire_travail(): void
    {
        $this->assertContains('horaire_travail', FormFieldsService::getStandardFields());
    }

    public function test_standard_fields_contains_nombre_heures(): void
    {
        $this->assertContains('nombre_heures', FormFieldsService::getStandardFields());
    }

    public function test_multiple_custom_fields_are_not_standard(): void
    {
        $customFields = [
            'custom_field_1',
            'champ_personnalise_1',
            'info_supplementaire',
            'note_interne',
            'reference_externe',
        ];

        foreach ($customFields as $field) {
            $this->assertFalse(
                FormFieldsService::isStandardField($field),
                "Le champ {$field} ne devrait pas être standard"
            );
        }
    }
}
