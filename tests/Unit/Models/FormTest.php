<?php

namespace Tests\Unit\Models;

use App\Models\Field;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_can_be_created(): void
    {
        $user = User::factory()->create();

        $form = Form::create([
            'name' => 'Test Form',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('forms', [
            'name' => 'Test Form',
            'user_id' => $user->id,
        ]);
    }

    public function test_form_name_is_fillable(): void
    {
        $form = new Form;
        $this->assertTrue(in_array('name', $form->getFillable()));
    }

    public function test_form_user_relationship_returns_belongs_to(): void
    {
        $user = User::factory()->create();

        $form = Form::create([
            'name' => 'Test Form',
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $form->user());
        $this->assertEquals($user->id, $form->user->id);
    }

    public function test_form_fields_relationship_returns_has_many(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $form->fields());
    }

    public function test_form_has_cdcs_relationship(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $form->cdcs());
    }

    public function test_form_can_have_multiple_fields(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        Field::factory()->count(5)->create(['form_id' => $form->id]);

        $this->assertCount(5, $form->fields);
    }

    public function test_form_fields_are_ordered_by_order_index(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        Field::factory()->create([
            'form_id' => $form->id,
            'name' => 'field_1',
            'order_index' => 3,
        ]);
        Field::factory()->create([
            'form_id' => $form->id,
            'name' => 'field_2',
            'order_index' => 1,
        ]);
        Field::factory()->create([
            'form_id' => $form->id,
            'name' => 'field_3',
            'order_index' => 2,
        ]);

        $fields = $form->fields;
        $this->assertEquals('field_2', $fields[0]->name);
        $this->assertEquals('field_3', $fields[1]->name);
        $this->assertEquals('field_1', $fields[2]->name);
    }

    public function test_form_can_be_updated(): void
    {
        $user = User::factory()->create();

        $form = Form::create([
            'name' => 'Original Name',
            'user_id' => $user->id,
        ]);

        $form->update(['name' => 'Updated Name']);

        $form->refresh();
        $this->assertEquals('Updated Name', $form->name);
    }

    public function test_form_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $form = Form::create([
            'name' => 'To Delete',
            'user_id' => $user->id,
        ]);

        $id = $form->id;

        $form->delete();

        $this->assertNull(Form::find($id));
    }

    public function test_form_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $form->user->id);
    }

    public function test_form_can_have_cdc(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $cdc = \App\Models\Cdc::factory()->create([
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals($form->id, $cdc->form->id);
    }

    public function test_form_name_is_required(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        Form::create([
            'name' => null,
            'user_id' => $user->id,
        ]);
    }

    public function test_form_user_id_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Form::create([
            'name' => 'Test Form',
            'user_id' => null,
        ]);
    }

    public function test_form_can_be_created_with_factory(): void
    {
        $form = Form::factory()->create();

        $this->assertNotNull($form->id);
        $this->assertNotNull($form->name);
    }

    public function test_form_has_timestamps(): void
    {
        $user = User::factory()->create();

        $form = Form::create([
            'name' => 'Test Form',
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($form->created_at);
        $this->assertNotNull($form->updated_at);
    }

    public function test_form_can_have_zero_fields(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertCount(0, $form->fields);
    }
}
