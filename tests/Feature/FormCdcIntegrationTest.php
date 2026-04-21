<?php

namespace Tests\Feature;

use App\Models\Cdc;
use App\Models\FieldType;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormCdcIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        FieldType::factory()->create(['name' => 'Text', 'input_type' => 'text']);
        FieldType::factory()->create(['name' => 'Textarea', 'input_type' => 'textarea']);
    }

    public function test_create_form_with_cdc_in_transaction(): void
    {
        $formData = $this->getValidFormData();

        $response = $this->actingAs($this->user)->post(route('forms.store'), $formData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('forms', [
            'name' => $formData['titre_projet'],
            'user_id' => $this->user->id,
        ]);

        $form = Form::where('name', $formData['titre_projet'])->first();
        $this->assertNotNull($form);

        $this->assertDatabaseHas('cdcs', [
            'form_id' => $form->id,
            'user_id' => $this->user->id,
            'title' => $formData['titre_projet'],
        ]);

        $cdc = $form->cdcs()->first();
        $this->assertEquals($formData['candidat_nom'], $cdc->data['candidat_nom']);
        $this->assertEquals($formData['candidat_prenom'], $cdc->data['candidat_prenom']);
    }

    public function test_create_form_with_custom_fields_and_cdc(): void
    {
        $fieldType = FieldType::first();

        $formData = $this->getValidFormData();
        $formData['fields'] = [
            [
                'name' => 'custom_champ_1',
                'label' => 'Champ personnalisé 1',
                'field_type_id' => $fieldType->id,
                'value' => 'Valeur personnalisée',
            ],
            [
                'name' => 'custom_champ_2',
                'label' => 'Champ personnalisé 2',
                'field_type_id' => $fieldType->id,
                'value' => 'Autre valeur',
            ],
        ];

        $response = $this->actingAs($this->user)->post(route('forms.store'), $formData);

        $response->assertRedirect();

        $form = Form::where('name', $formData['titre_projet'])->first();

        $this->assertDatabaseHas('fields', [
            'form_id' => $form->id,
            'name' => 'custom_champ_1',
            'label' => 'Champ personnalisé 1',
        ]);

        $this->assertDatabaseHas('fields', [
            'form_id' => $form->id,
            'name' => 'custom_champ_2',
            'label' => 'Champ personnalisé 2',
        ]);

        $cdc = $form->cdcs()->first();
        $this->assertEquals('Valeur personnalisée', $cdc->data['custom_champ_1']);
        $this->assertEquals('Autre valeur', $cdc->data['custom_champ_2']);
    }

    public function test_update_form_updates_cdc_data(): void
    {
        $form = Form::factory()->create(['user_id' => $this->user->id]);

        $cdc = Cdc::create([
            'title' => 'AncienTitre',
            'data' => [
                'candidat_nom' => 'AncienNom',
                'candidat_prenom' => 'AncienPrenom',
                'titre_projet' => 'AncienTitre',
            ],
            'form_id' => $form->id,
            'user_id' => $this->user->id,
        ]);

        $updateData = $this->getValidFormData();
        $updateData['titre_projet'] = 'Nouveau Titre';
        $updateData['candidat_nom'] = 'NouveauNom';

        $response = $this->actingAs($this->user)->put(route('forms.update', $form), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $form->refresh();
        $cdc->refresh();

        $this->assertEquals('Nouveau Titre', $form->name);
        $this->assertEquals('Nouveau Titre', $cdc->title);
        $this->assertEquals('NouveauNom', $cdc->data['candidat_nom']);
    }

    public function test_delete_form_cascade_deletes_cdc(): void
    {
        $form = Form::factory()->create(['user_id' => $this->user->id]);

        $cdc = Cdc::create([
            'title' => 'Test CDC',
            'data' => ['test' => 'data'],
            'form_id' => $form->id,
            'user_id' => $this->user->id,
        ]);

        $formId = $form->id;

        $response = $this->actingAs($this->user)->delete(route('forms.destroy', $form));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertNull(Form::find($formId));
        $this->assertTrue(Cdc::where('form_id', $formId)->doesntExist());
    }

    public function test_authorization_prevents_other_user_access(): void
    {
        $otherUser = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $otherUser->id]);
        Cdc::create([
            'title' => 'Test CDC',
            'data' => [],
            'form_id' => $form->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('forms.show', $form));

        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get(route('forms.edit', $form));

        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->delete(route('forms.destroy', $form));

        $response->assertStatus(403);
    }

    public function test_form_list_with_pagination(): void
    {
        Form::factory()->count(15)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('forms.index'));

        $response->assertStatus(200);
    }

    public function test_form_can_be_created_via_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('forms.create'));

        $response->assertStatus(200);
    }

    private function getValidFormData(): array
    {
        return [
            'candidat_nom' => 'Dupont',
            'candidat_prenom' => 'Jean',
            'lieu_travail' => 'Paris',
            'orientation' => 'Informatique',

            'chef_projet_nom' => 'Martin',
            'chef_projet_prenom' => 'Sophie',
            'chef_projet_email' => 'sophie.martin@exemple.com',
            'chef_projet_telephone' => '+41 12 345 67 89',

            'expert1_nom' => 'Bernard',
            'expert1_prenom' => 'Luc',
            'expert1_email' => 'luc.bernard@exemple.com',
            'expert1_telephone' => '+41 12 345 67 80',

            'expert2_nom' => 'Durand',
            'expert2_prenom' => 'Marie',
            'expert2_email' => 'marie.durand@exemple.com',
            'expert2_telephone' => '+41 12 345 67 81',

            'date_debut' => '2026-01-15',
            'date_fin' => '2026-03-15',
            'heure_matin_debut' => '09:00',
            'heure_matin_fin' => '12:00',
            'heure_aprem_debut' => '13:30',
            'heure_aprem_fin' => '17:30',
            'nombre_heures' => '80',

            'planning_analyse' => '20',
            'planning_implementation' => '40',
            'planning_tests' => '15',
            'planning_documentation' => '5',

            'procedure' => 'Procédure standard',
            'titre_projet' => 'Projet Test Integration',
            'materiel_logiciel' => 'Laravel, PHP, MySQL',
            'prerequis' => 'Serveur web, PHP 8.2+',
            'descriptif_projet' => 'Description du projet de test',
            'livrables' => 'Documentation, Code source',
        ];
    }
}
