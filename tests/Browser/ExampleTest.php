<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function test_basic_example(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(1920, 1080)
                ->visit('http://127.0.0.1:8000')
                ->pause(3000)
                ->assertTitleContains('Générateur');
        });
    }
}
