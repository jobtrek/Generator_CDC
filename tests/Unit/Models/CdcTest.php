<?php

namespace Tests\Unit\Models;

use App\Models\Cdc;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CdcTest extends TestCase
{
    use RefreshDatabase;

    public function test_cdc_can_be_created(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $cdc = Cdc::create([
            'title' => 'Test CDC',
            'data' => [
                'candidat_nom' => 'Dupont',
                'candidat_prenom' => 'Jean',
                'date_debut' => '2026-01-15',
                'date_fin' => '2026-03-15',
            ],
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('cdcs', [
            'title' => 'Test CDC',
            'user_id' => $user->id,
        ]);
    }

    public function test_cdc_data_is_cast_to_array(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $cdc = Cdc::create([
            'title' => 'Test CDC',
            'data' => ['candidat_nom' => 'Dupont'],
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $this->assertIsArray($cdc->data);
        $this->assertEquals('Dupont', $cdc->data['candidat_nom']);
    }

    public function test_cdc_data_can_be_empty_array(): void
    {
        $user = User::factory()->create();
        $cdc = Cdc::create([
            'title' => 'Test CDC',
            'data' => [],
            'user_id' => $user->id,
            'form_id' => null,
        ]);

        $this->assertIsArray($cdc->data);
        $this->assertEmpty($cdc->data);
    }

    public function test_cdc_form_relationship_returns_belongs_to(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $cdc = Cdc::create([
            'title' => 'Test CDC',
            'data' => [],
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $cdc->form());
        $this->assertEquals($form->id, $cdc->form->id);
    }

    public function test_cdc_user_relationship_returns_belongs_to(): void
    {
        $user = User::factory()->create();

        $cdc = Cdc::create([
            'title' => 'Test CDC',
            'data' => [],
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $cdc->user());
        $this->assertEquals($user->id, $cdc->user->id);
    }

    public function test_is_manual_returns_true_when_form_id_is_null(): void
    {
        $user = User::factory()->create();

        $cdc = Cdc::create([
            'title' => 'Manual CDC',
            'data' => [],
            'user_id' => $user->id,
            'form_id' => null,
        ]);

        $this->assertTrue($cdc->isManual());
    }

    public function test_is_manual_returns_false_when_form_id_is_not_null(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $cdc = Cdc::create([
            'title' => 'Form CDC',
            'data' => [],
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $this->assertFalse($cdc->isManual());
    }

    public function test_cdc_can_be_created_without_form(): void
    {
        $user = User::factory()->create();

        $cdc = Cdc::create([
            'title' => 'Standalone CDC',
            'data' => ['titre' => 'Mon projet'],
            'user_id' => $user->id,
            'form_id' => null,
        ]);

        $this->assertNull($cdc->form);
        $this->assertTrue($cdc->isManual());
    }

    public function test_cdc_title_is_fillable(): void
    {
        $cdc = new Cdc;
        $this->assertTrue(in_array('title', $cdc->getFillable()));
    }

    public function test_cdc_data_is_fillable(): void
    {
        $cdc = new Cdc;
        $this->assertTrue(in_array('data', $cdc->getFillable()));
    }

    public function test_cdc_form_id_is_fillable(): void
    {
        $cdc = new Cdc;
        $this->assertTrue(in_array('form_id', $cdc->getFillable()));
    }

    public function test_cdc_has_many_cdcs_relationship(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        Cdc::factory()->count(3)->create([
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $form->cdcs);
    }

    public function test_cdc_data_can_store_complex_nested_arrays(): void
    {
        $user = User::factory()->create();

        $cdc = Cdc::create([
            'title' => 'Complex CDC',
            'data' => [
                'planning' => [
                    'analyse' => 20,
                    'implementation' => 40,
                    'tests' => 15,
                    'documentation' => 10,
                ],
                'experts' => [
                    ['nom' => 'Expert1', 'email' => 'expert1@test.com'],
                    ['nom' => 'Expert2', 'email' => 'expert2@test.com'],
                ],
            ],
            'user_id' => $user->id,
        ]);

        $this->assertEquals(20, $cdc->data['planning']['analyse']);
        $this->assertCount(2, $cdc->data['experts']);
    }

    public function test_cdc_can_update_data(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $cdc = Cdc::create([
            'title' => 'Test CDC',
            'data' => ['old_key' => 'old_value'],
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $cdc->update(['data' => ['new_key' => 'new_value']]);

        $cdc->refresh();
        $this->assertArrayHasKey('new_key', $cdc->data);
        $this->assertArrayNotHasKey('old_key', $cdc->data);
    }

    public function test_cdc_can_have_null_form(): void
    {
        $user = User::factory()->create();

        $cdc = Cdc::create([
            'title' => 'Manual CDC',
            'data' => [],
            'user_id' => $user->id,
        ]);

        $this->assertNull($cdc->form_id);
    }

    public function test_cdc_title_can_be_updated(): void
    {
        $user = User::factory()->create();

        $cdc = Cdc::create([
            'title' => 'Original Title',
            'data' => [],
            'user_id' => $user->id,
        ]);

        $cdc->update(['title' => 'Updated Title']);

        $cdc->refresh();
        $this->assertEquals('Updated Title', $cdc->title);
    }

    public function test_cdc_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $cdc = Cdc::create([
            'title' => 'To Delete',
            'data' => [],
            'user_id' => $user->id,
        ]);

        $id = $cdc->id;

        $cdc->delete();

        $this->assertNull(Cdc::find($id));
    }
}
