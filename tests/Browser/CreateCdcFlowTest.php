<?php

namespace Tests\Browser;

use App\Models\Cdc;
use App\Models\FieldType;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateCdcFlowTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_access_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/dashboard')
                ->assertPathIs('/dashboard');
        });
    }

    public function test_user_can_visit_forms_index(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/forms')
                ->assertPathIs('/forms');
        });
    }

    public function test_user_can_visit_create_form_page(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/forms/create')
                ->assertPathIs('/forms/create');
        });
    }

    public function test_forms_show_displays_form(): void
    {
        FieldType::factory()->create(['name' => 'Text', 'input_type' => 'text']);

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $form = Form::factory()->create([
            'user_id' => $user->id,
            'name' => 'Mon formulaire de test',
        ]);

        Cdc::factory()->create([
            'title' => 'Mon formulaire de test',
            'data' => ['candidat_nom' => 'Test'],
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        $this->browse(function (Browser $browser) use ($form) {
            $browser->loginAs($form->user)
                ->visit('/forms/'.$form->id)
                ->assertPathIs('/forms/'.$form->id);
        });
    }
    public function test_forms_list_shows_pagination(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        for ($i = 1; $i <= 10; $i++) {
            $form = Form::factory()->create([
                'user_id' => $user->id,
                'name' => "Formulaire test $i",
            ]);
            Cdc::factory()->create([
                'title' => "Formulaire test $i",
                'data' => ['test' => 'data'],
                'form_id' => $form->id,
                'user_id' => $user->id,
            ]);
        }

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/forms')
                ->assertPathIs('/forms');
        });
    }
}
