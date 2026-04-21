# Tests E2E - Setup

Guide pour configurer et exécuter les tests E2E avec Laravel Dusk.

## Installation de Laravel Dusk

```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

## Commandes

### Exécuter les tests E2E

```bash
php artisan dusk
```

### Exécuter un test spécifique

```bash
php artisan dusk --filter=NomDuTest
```

### Mode non-headless (pour debugging)

```bash
php artisan dusk --headless=false
```

## Créer un test E2E

Créer un fichier dans `tests/Browser/`:

```php
<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MonTestE2E extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_mon_scénario(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/dashboard')
                ->assertPathIs('/dashboard');
        });
    }
}
```

    │#                                                        │Scénario                                                                           │Description                                                                                                      │                           
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤                                
     │1                                                        │Inscription/Connexion                                                              │Tester le流程 complet d'inscription + connexion                                                                  │                             
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤                                              
     │2                                                        │Création de formulaire CDC                                                         │Remplir le formulaire complet et soumettre                                                                       │                                      
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤                         
     │3                                                        │Téléchargement CDC                                                                 │Créer un CDC puis télécharger le document .docx                                                                  │                                              
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤                         
     │4                                                        │Modification d'un formulaire                                                       │Modifier un formulaire existant                                                                                  │       
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤        
     │5                                                        │Suppression d'un formulaire                                                        │Supprimer un formulaire et vérifier                                                                              │      
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤       
     │6                                                        │Accès aux routes protégées                                                         │Vérifier redirection vers /login si non connecté                                                                 │      
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤      
     │7                                                        │Vérification email                                                                 │Tester le流程 de vérification d'email                                                                            │     
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤      
     │8                                                        │Dashboard                                                                          │Vérifier que le dashboard affiche les bonnes infos                                                               │       
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤    
     │9                                                        │Profile - modification                                                             │Modifier le nom/email et vérifier                                                                                │                                              
     ├─────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤                                              
     │10                                                       │Gestion users (admin)                                                              │Tester la création/modification d'un utilisateur par admin  
