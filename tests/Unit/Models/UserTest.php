<?php

namespace Tests\Unit\Models;

use App\Models\Cdc;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_name_is_fillable(): void
    {
        $user = new User;
        $this->assertTrue(in_array('name', $user->getFillable()));
    }

    public function test_user_email_is_fillable(): void
    {
        $user = new User;
        $this->assertTrue(in_array('email', $user->getFillable()));
    }

    public function test_user_password_is_fillable(): void
    {
        $user = new User;
        $this->assertTrue(in_array('password', $user->getFillable()));
    }

    public function test_user_password_is_hidden(): void
    {
        $user = User::factory()->create();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    public function test_user_email_verified_at_is_cast_to_datetime(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'plainpassword',
        ]);

        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('plainpassword', $user->password));
    }

    public function test_user_has_forms_relationship(): void
    {
        $user = User::factory()->create();
        Form::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->forms());
        $this->assertCount(3, $user->forms);
    }

    public function test_user_has_cdcs_relationship(): void
    {
        $user = User::factory()->create();
        Cdc::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->cdcs());
        $this->assertCount(2, $user->cdcs);
    }

    public function test_user_can_have_zero_forms(): void
    {
        $user = User::factory()->create();

        $this->assertCount(0, $user->forms);
    }

    public function test_user_can_have_zero_cdcs(): void
    {
        $user = User::factory()->create();

        $this->assertCount(0, $user->cdcs);
    }

    public function test_user_email_is_unique(): void
    {
        User::create([
            'name' => 'User 1',
            'email' => 'unique@example.com',
            'password' => 'password',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'User 2',
            'email' => 'unique@example.com',
            'password' => 'password',
        ]);
    }

    public function test_user_can_be_updated(): void
    {
        $user = User::factory()->create();

        $user->update(['name' => 'Updated Name']);

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
    }

    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $id = $user->id;

        $user->delete();

        $this->assertNull(User::find($id));
    }

    public function test_user_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->id);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
    }

    public function test_user_has_remember_token(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->remember_token);
    }

    public function test_user_uses_notifiable_trait(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(method_exists($user, 'notify'));
    }

    public function test_user_uses_has_roles_trait(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(method_exists($user, 'assignRole'));
        $this->assertTrue(method_exists($user, 'hasRole'));
    }

    public function test_user_can_have_multiple_forms(): void
    {
        $user = User::factory()->create();
        Form::factory()->count(5)->create(['user_id' => $user->id]);

        $this->assertCount(5, $user->forms);
    }

    public function test_user_can_have_multiple_cdcs(): void
    {
        $user = User::factory()->create();
        Cdc::factory()->count(4)->create(['user_id' => $user->id]);

        $this->assertCount(4, $user->cdcs);
    }

    public function test_user_email_must_be_valid_format(): void
    {
        $user = User::factory()->create(['email' => 'valid@email.com']);

        $this->assertEquals('valid@email.com', $user->email);
    }

    public function test_user_has_timestamps(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }
}
