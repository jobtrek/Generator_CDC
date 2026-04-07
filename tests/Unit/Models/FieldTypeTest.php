<?php

namespace Tests\Unit\Models;

use App\Models\Field;
use App\Models\FieldType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_field_type_can_be_created(): void
    {
        $fieldType = FieldType::create([
            'name' => 'Text',
            'input_type' => 'text',
            'validation_rules' => ['required' => true],
        ]);

        $this->assertDatabaseHas('field_types', [
            'name' => 'Text',
            'input_type' => 'text',
        ]);
    }

    public function test_field_type_name_is_fillable(): void
    {
        $fieldType = new FieldType;
        $this->assertTrue(in_array('name', $fieldType->getFillable()));
    }

    public function test_field_type_input_type_is_fillable(): void
    {
        $fieldType = new FieldType;
        $this->assertTrue(in_array('input_type', $fieldType->getFillable()));
    }

    public function test_field_type_validation_rules_is_fillable(): void
    {
        $fieldType = new FieldType;
        $this->assertTrue(in_array('validation_rules', $fieldType->getFillable()));
    }

    public function test_field_type_validation_rules_is_cast_to_array(): void
    {
        $fieldType = FieldType::create([
            'name' => 'Email',
            'input_type' => 'email',
            'validation_rules' => ['required' => true, 'email' => true],
        ]);

        $this->assertIsArray($fieldType->validation_rules);
        $this->assertTrue($fieldType->validation_rules['required']);
    }

    public function test_field_type_has_fields_relationship(): void
    {
        $fieldType = FieldType::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $fieldType->fields());
    }

    public function test_field_type_can_have_multiple_fields(): void
    {
        $fieldType = FieldType::factory()->create();
        Field::factory()->count(3)->create(['field_type_id' => $fieldType->id]);

        $this->assertCount(3, $fieldType->fields);
    }

    public function test_field_type_can_be_created_with_factory(): void
    {
        $fieldType = FieldType::factory()->create();

        $this->assertNotNull($fieldType->id);
        $this->assertNotNull($fieldType->name);
    }

    public function test_field_type_can_be_text_type(): void
    {
        $fieldType = FieldType::factory()->textType()->create();

        $this->assertEquals('Text', $fieldType->name);
        $this->assertEquals('text', $fieldType->input_type);
    }

    public function test_field_type_can_be_email_type(): void
    {
        $fieldType = FieldType::factory()->emailType()->create();

        $this->assertEquals('Email', $fieldType->name);
        $this->assertEquals('email', $fieldType->input_type);
    }

    public function test_field_type_can_be_date_type(): void
    {
        $fieldType = FieldType::factory()->dateType()->create();

        $this->assertEquals('Date', $fieldType->name);
        $this->assertEquals('date', $fieldType->input_type);
    }

    public function test_field_type_can_be_textarea_type(): void
    {
        $fieldType = FieldType::factory()->textareaType()->create();

        $this->assertEquals('Textarea', $fieldType->name);
        $this->assertEquals('textarea', $fieldType->input_type);
    }

    public function test_field_type_validation_rules_can_be_empty_array(): void
    {
        $fieldType = FieldType::create([
            'name' => 'Custom',
            'input_type' => 'text',
            'validation_rules' => [],
        ]);

        $this->assertIsArray($fieldType->validation_rules);
        $this->assertEmpty($fieldType->validation_rules);
    }

    public function test_field_type_validation_rules_can_be_null(): void
    {
        $fieldType = FieldType::create([
            'name' => 'Custom',
            'input_type' => 'text',
            'validation_rules' => null,
        ]);

        $this->assertNull($fieldType->validation_rules);
    }

    public function test_field_type_can_be_updated(): void
    {
        $fieldType = FieldType::factory()->create();

        $fieldType->update(['name' => 'Updated Name']);

        $fieldType->refresh();
        $this->assertEquals('Updated Name', $fieldType->name);
    }

    public function test_field_type_can_be_deleted(): void
    {
        $fieldType = FieldType::factory()->create();
        $id = $fieldType->id;

        $fieldType->delete();

        $this->assertNull(FieldType::find($id));
    }

    public function test_field_type_has_timestamps(): void
    {
        $fieldType = FieldType::factory()->create();

        $this->assertNotNull($fieldType->created_at);
        $this->assertNotNull($fieldType->updated_at);
    }

    public function test_field_type_can_have_zero_fields(): void
    {
        $fieldType = FieldType::factory()->create();

        $this->assertCount(0, $fieldType->fields);
    }
}
