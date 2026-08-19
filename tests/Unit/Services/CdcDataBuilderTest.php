<?php

namespace Tests\Unit\Services;

use App\Services\CdcDataBuilder;
use App\Services\DateTimeFormatter;
use Tests\TestCase;

class CdcDataBuilderTest extends TestCase
{
    private CdcDataBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new CdcDataBuilder(new DateTimeFormatter());
    }

    public function test_build_returns_all_required_keys(): void
    {
        $data = $this->builder->build($this->validInput());

        $this->assertArrayHasKey('candidat_nom', $data);
        $this->assertArrayHasKey('chef_projet_nom', $data);
        $this->assertArrayHasKey('expert1_nom', $data);
        $this->assertArrayHasKey('expert2_nom', $data);
        $this->assertArrayHasKey('periode_realisation', $data);
        $this->assertArrayHasKey('horaire_travail', $data);
        $this->assertArrayHasKey('titre_projet', $data);
        $this->assertArrayHasKey('descriptif_projet', $data);
    }

    public function test_build_preserves_candidat_data(): void
    {
        $input = $this->validInput();
        $data = $this->builder->build($input);

        $this->assertSame($input['candidat_nom'], $data['candidat_nom']);
        $this->assertSame($input['candidat_prenom'], $data['candidat_prenom']);
        $this->assertSame($input['candidat_email'], $data['candidat_email']);
    }

    public function test_build_formats_periode_realisation(): void
    {
        $data = $this->builder->build($this->validInput());

        $this->assertStringContainsString('2026', $data['periode_realisation']);
        $this->assertStringStartsWith('Du', $data['periode_realisation']);
    }

    public function test_build_formats_horaire_travail(): void
    {
        $data = $this->builder->build($this->validInput());

        $this->assertStringContainsString('08:00', $data['horaire_travail']);
        $this->assertStringContainsString('12:00', $data['horaire_travail']);
        $this->assertStringContainsString('13:00', $data['horaire_travail']);
        $this->assertStringContainsString('17:00', $data['horaire_travail']);
    }

    public function test_build_default_pauses(): void
    {
        $input = $this->validInput();
        unset($input['pause_matin_debut'], $input['pause_matin_fin']);
        unset($input['pause_aprem_debut'], $input['pause_aprem_fin']);

        $data = $this->builder->build($input);

        $this->assertSame('10:30', $data['pause_matin_debut']);
        $this->assertSame('10:45', $data['pause_matin_fin']);
        $this->assertSame('15:00', $data['pause_aprem_debut']);
        $this->assertSame('15:15', $data['pause_aprem_fin']);
    }

    public function test_build_parses_jours_feries(): void
    {
        $input = $this->validInput();
        $input['jours_feries'] = '["2026-12-25","2026-01-01"]';

        $data = $this->builder->build($input);

        $this->assertCount(2, $data['jours_feries']);
        $this->assertContains('2026-12-25', $data['jours_feries']);
    }

    public function test_build_handles_invalid_jours_feries(): void
    {
        $input = $this->validInput();
        $input['jours_feries'] = 'not json';

        $data = $this->builder->build($input);

        $this->assertSame([], $data['jours_feries']);
    }

    public function test_build_nullable_fields_default_to_empty(): void
    {
        $input = $this->validInput();
        unset($input['materiel_logiciel'], $input['prerequis'], $input['livrables'], $input['procedure']);

        $data = $this->builder->build($input);

        $this->assertSame('', $data['materiel_logiciel']);
        $this->assertSame('', $data['prerequis']);
        $this->assertSame('', $data['livrables']);
        $this->assertSame('', $data['procedure']);
    }

    public function test_build_casts_jours_cours_recuperer_to_int(): void
    {
        $input = $this->validInput();
        $input['jours_cours_recuperer'] = '5';

        $data = $this->builder->build($input);

        $this->assertIsInt($data['jours_cours_recuperer']);
        $this->assertSame(5, $data['jours_cours_recuperer']);
    }

    private function validInput(): array
    {
        return [
            'candidat_nom' => 'Dupont',
            'candidat_prenom' => 'Jean',
            'candidat_email' => 'jean@test.com',
            'candidat_telephone' => '+41 77 123 45 67',
            'lieu_travail' => 'Lausanne',
            'orientation' => '88601',
            'chef_projet_nom' => 'Martin',
            'chef_projet_prenom' => 'Paul',
            'chef_projet_email' => 'paul@test.com',
            'chef_projet_telephone' => '+41 77 234 56 78',
            'expert1_nom' => 'Durand',
            'expert1_prenom' => 'Luc',
            'expert1_email' => 'luc@test.com',
            'expert1_telephone' => '+41 77 345 67 89',
            'expert2_nom' => 'Bernard',
            'expert2_prenom' => 'Marc',
            'expert2_email' => 'marc@test.com',
            'expert2_telephone' => '+41 77 456 78 90',
            'date_debut' => '2026-09-01',
            'date_fin' => '2026-12-31',
            'heure_matin_debut' => '08:00',
            'heure_matin_fin' => '12:00',
            'heure_aprem_debut' => '13:00',
            'heure_aprem_fin' => '17:00',
            'titre_projet' => 'Application web',
            'descriptif_projet' => 'Développer une app',
            'jours_feries' => '[]',
        ];
    }
}
