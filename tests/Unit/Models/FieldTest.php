<?php

namespace Tests\Unit\Models;

use App\Models\Field;
use App\Models\FieldType;
use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldTest extends TestCase
{
    use RefreshDatabase;

    public function test_field_can_be_created(): void
    {
        $field = Field::factory()->create();

        $this->assertDatabaseHas('fields', [
            'name' => $field->name,
        ]);
    }

    public function test_field_name_is_fillable(): void
    {
        $field = new Field;
        $this->assertTrue(in_array('name', $field->getFillable()));
    }

    public function test_field_label_is_fillable(): void
    {
        $field = new Field;
        $this->assertTrue(in_array('label', $field->getFillable()));
    }

    public function test_field_placeholder_is_fillable(): void
    {
        $field = new Field;
        $this->assertTrue(in_array('placeholder', $field->getFillable()));
    }

    public function test_field_is_required_is_fillable(): void
    {
        $field = new Field;
        $this->assertTrue(in_array('is_required', $field->getFillable()));
    }

    public function test_field_order_index_is_fillable(): void
    {
        $field = new Field;
        $this->assertTrue(in_array('order_index', $field->getFillable()));
    }

    public function test_field_is_required_is_cast_to_boolean(): void
    {
        $field = Field::factory()->create(['is_required' => true]);

        $this->assertIsBool($field->is_required);
        $this->assertTrue($field->is_required);
    }

    public function test_field_is_required_can_be_false(): void
    {
        $field = Field::factory()->create(['is_required' => false]);

        $this->assertFalse($field->is_required);
    }

    public function test_field_form_relationship_returns_belongs_to(): void
    {
        $field = Field::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $field->form());
        $this->assertNotNull($field->form->id);
    }

    public function test_field_field_type_relationship_returns_belongs_to(): void
    {
        $field = Field::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $field->fieldType());
        $this->assertNotNull($field->fieldType->id);
    }

    public function test_field_can_have_options(): void
    {
        $field = Field::factory()->create([
            'options' => ['Option A', 'Option B', 'Option C'],
        ]);

        $this->assertIsArray($field->options);
        $this->assertCount(3, $field->options);
    }

    public function test_field_options_can_be_null(): void
    {
        $field = Field::factory()->create(['options' => null]);

        $this->assertNull($field->options);
    }

    public function test_field_can_be_created_with_factory(): void
    {
        $field = Field::factory()->create();

        $this->assertNotNull($field->id);
        $this->assertNotNull($field->name);
    }

    public function test_field_can_be_updated(): void
    {
        $field = Field::factory()->create();

        $field->update(['label' => 'Updated Label']);

        $field->refresh();
        $this->assertEquals('Updated Label', $field->label);
    }

    public function test_field_can_be_deleted(): void
    {
        $field = Field::factory()->create();
        $id = $field->id;

        $field->delete();

        $this->assertNull(Field::find($id));
    }

    public function test_field_belongs_to_form(): void
    {
        $field = Field::factory()->create();

        $this->assertNotNull($field->form);
    }

    public function test_field_has_timestamps(): void
    {
        $field = Field::factory()->create();

        $this->assertNotNull($field->created_at);
        $this->assertNotNull($field->updated_at);
    }

    public function test_field_options_is_cast_to_array(): void
    {
        $field = Field::factory()->create([
            'options' => ['key' => 'value'],
        ]);

        $this->assertIsArray($field->options);
    }

    public function test_field_order_index_is_required(): void
    {
        $field = Field::factory()->create();

        $this->assertNotNull($field->order_index);
    }

    public function test_field_order_index_can_be_custom(): void
    {
        $field = Field::factory()->create(['order_index' => 99]);

        $this->assertEquals(99, $field->order_index);
    }

    public function test_field_can_be_created_with_custom_field_type(): void
    {
        $fieldType = FieldType::factory()->create();
        $field = Field::factory()->create(['field_type_id' => $fieldType->id]);

        $this->assertEquals($fieldType->id, $field->field_type_id);
    }

    public function test_field_can_belong_to_form(): void
    {
        $form = Form::factory()->create();
        $field = Field::factory()->create(['form_id' => $form->id]);

        $this->assertEquals($form->id, $field->form_id);
    }
}
