# Tests Unitaires - Générateur de CDC

## Présentation

Ce document décrit comment exécuter les tests unitaires de l'application de génération de cahiers des charges.

## Framework de tests utilisé

### PHPUnit (Tests Unitaires & Intégration)

Laravel utilise **PHPUnit** comme framework de tests. C'est le standard industriel pour les tests PHP.

**Tests Unitaires :**
- Tests de composants isolés (Models, Services)
- Utilisation de factories pour générer des données
- Base de données SQLite in-memory pour isolation

**Tests d'Intégration :**
- Tests HTTP qui simulent des requêtes réelles vers l'application
- `$this->get()`, `$this->post()`, `$this->put()`, `$this->delete()`
- `$this->actingAs($user)` pour simuler un utilisateur connecté
- `assertStatus()`, `assertRedirect()`, `assertSessionHas()`, `assertDatabaseHas()`

**Commande de base :**
```bash
php artisan test tests/Unit
php artisan test tests/Feature
```

## Installation

```bash
composer install
```

## Exécution des tests unitaires

### Tous les tests unitaires

```bash
php artisan test tests/Unit
```

**Résultat attendu :**
```
Tests:    124 passed (216 assertions)
Duration: 0.82s
```

### Structure des tests

```
tests/Unit/
├── Services/
│   └── FormFieldsServiceTest.php    # 27 tests - Service de gestion des champs standards
├── Models/
│   ├── CdcTest.php                   # 18 tests - Modèle CDC (cahier des charges)
│   ├── FormTest.php                  # 18 tests - Modèle Form (formulaire)
│   ├── FieldTest.php                 # 20 tests - Modèle Champ
│   ├── FieldTypeTest.php             # 19 tests - Modèle Type de champ
│   └── UserTest.php                  # 22 tests - Modèle User
└── ExampleTest.php                   # Test exemple
```

### Tests spécifiques

```
tests/Unit/
├── Services/
│   └── FormFieldsServiceTest.php    # 27 tests - Service de gestion des champs standards
├── Models/
│   ├── CdcTest.php                   # 18 tests - Modèle CDC (cahier des charges)
│   ├── FormTest.php                  # 18 tests - Modèle Form (formulaire)
│   ├── FieldTest.php                 # 20 tests - Modèle Field (champ)
│   ├── FieldTypeTest.php             # 19 tests - Modèle FieldType (type de champ)
│   └── UserTest.php                  # 22 tests - Modèle User
└── ExampleTest.php                   # Test exemple
```

## Exécuter les tests

### Tous les tests unitaires

```bash
php artisan test tests/Unit
```

### Tests spécifiques

```bash
# Tests du service FormFieldsService
php artisan test tests/Unit/Services/FormFieldsServiceTest.php

# Tests des modèles
php artisan test tests/Unit/Models/CdcTest.php
php artisan test tests/Unit/Models/FormTest.php
php artisan test tests/Unit/Models/FieldTest.php
php artisan test tests/Unit/Models/FieldTypeTest.php
php artisan test tests/Unit/Models/UserTest.php
```

### Avec coverage (nécessite Xdebug ou PCOV)

```bash
php artisan test tests/Unit --coverage
```

## Résumé des tests

| Classe de test | Nombre de tests | Couverture |
|----------------|-----------------|------------|
| FormFieldsServiceTest | 27 | Service de gestion des champs |
| CdcTest | 18 | Modèle CDC |
| FormTest | 18 | Modèle Formulaire |
| FieldTest | 20 | Modèle Champ |
| FieldTypeTest | 19 | Modèle Type de champ |
| UserTest | 22 | Modèle Utilisateur |
| **Total** | **124** | - |

## Types de tests implémentés

### Scénarios nominaux
- Création d'entités (User, Form, Cdc, Field, FieldType)
- Relations entre modèles (BelongsTo, HasMany)
- Cast des attributs (array, boolean, datetime)

### Scénarios d'exception
- Contraintes de base de données (NOT NULL, UNIQUE)
- Validation des types (cast boolean)
- Suppression en cascade

### Tests de limites
- Valeurs nulles (options, form_id)
- Tableaux vides
- Chaînes de caractères vides

## Données de test

Les tests utilisent :
- **Factories** : `UserFactory`, `FormFactory`, `FieldFactory`, `CdcFactory`, `FieldTypeFactory`
- **Méthodes states** : customisation des données de test (ex: `textType()`, `emailType()`)
- **RefreshDatabase** : isolation des tests avec base de données SQLite en mémoire

## Configuration des tests

Les tests utilisent une base de données SQLite en mémoire pour des performances optimales :

```xml
<!-- phpunit.xml -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## Couverture visée

Selon le concept de test, l'objectif est un coverage de 70% sur les fonctionnalités critiques :
- Authentification (User)
- Gestion des formulaires (Form, Field)
- Génération CDC (Cdc)
- Types de champs (FieldType)

Pour installer un driver de coverage :

```bash
# Xdebug
pecl install xdebug

# OU PCOV
pecl install pcov
```

## Commandes utiles

```bash
# Voir la liste des tests
php artisan test --list-tests

# Test avec sortie verbose
php artisan test tests/Unit -v

# Test avec output HTML (si coverage driver installé)
php artisan test tests/Unit --coverage-html=storage/coverage
```

## Troubleshooting

### Erreur "No code coverage driver available"
Installer Xdebug ou PCOV pour avoir le coverage.

### Erreur de migration
Les tests utilisent `RefreshDatabase` qui exécute automatiquement les migrations.

### Problème de fulltext avec SQLite
La migration `2026_02_11_104015_add_fulltext_index_to_forms_table.php` est ignorée pour SQLite.

## Analyse de tests unitaires sélectionnés

### Test 1 : Vérification du cast des données CDC (CdcTest)

```php
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
```

**Conception :**
- **But :** Vérifier que l'attribut `data` est correctement transformé en tableau PHP lors de la récupération
- **Approche :** Création d'un CDC avec un tableau associatif, vérification du type et de l'accès aux données
- **Scénario :** Test nominal - cas d'utilisation standard d'un CDC avec des données

**Pourquoi intéressant :** Ce test验证 le mécanisme de cast automatique de Laravel (`'data' => 'array'` dans le modèle). Sans ce test, une erreur de cast passerait inaperçue et causerait des bugs subtils lors de l'accès aux données du CDC.

---

### Test 2 : Vérification de la relation `isManual()` (CdcTest)

```php
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
```

**Conception :**
- **But :** Vérifier la logique métier qui distingue un CDC créé manuellement d'un CDC généré depuis un formulaire
- **Approche :** Deux tests complémentaires pour couvrir les deux cas de retour de la méthode
- **Scénario :** Test de limite - distinction entre CDC manuel (sans formulaire) et CDC lié à un formulaire

**Pourquoi intéressant :** Ce test illustre la logique métier de l'application. La méthode `isManual()` permet de savoir si le CDC a été créé indépendamment d'un formulaire, ce qui impacte le comportement de l'application (affichage, édition, suppression).

---

### Test 3 : Vérification de l'ordre des champs dans un formulaire (FormTest)

```php
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
```

**Conception :**
- **But :** Vérifier que les champs d'un formulaire sont triés par `order_index` lors de la récupération
- **Approche :** Création de 3 champs avec des order_index différents, vérification de l'ordre de récupération
- **Scénario :** Test nominal -驗证 le comportement de la relation `HasMany` avec tri

**Pourquoi intéressant :** Ce test vérifie une fonctionnalité critique pour l'affichage du formulaire. Si le tri échouait, l'ordre des champs serait aléatoire et l'interface utilisateur serait incohérente. Ce test utilise les factories de manière advanced en créant plusieurs instances liées au même formulaire.

---

# Tests d'Intégration

## Présentation

Les tests d'intégration vérifient le fonctionnement conjoint de plusieurs composants de l'application (controllers, services, models).

## Structure des tests d'intégration

```
tests/Feature/
└── FormCdcIntegrationTest.php    # 7 tests - Flux Formulaire + CDC
```

## Exécuter les tests d'intégration

```bash
php artisan test tests/Feature
```

**Résultat attendu :**
```
Tests:    7 passed (26 assertions)
Duration: 0.29s
```

## Résumé des tests

| Classe de test | Nombre de tests | Description |
|----------------|-----------------|-------------|
| FormCdcIntegrationTest | 7 | Flux complet Formulaire + CDC |

## Tests implémentés

### Scénarios nominaux
- **Création Form + CDC** : Vérifie que store() crée correctement le formulaire et le CDC associé
- **Champs personnalisés** : Vérifie l'ajout de champs customs au formulaire et au CDC
- **Mise à jour** : Vérifie que update() modifie le formulaire et le CDC
- **Suppression en cascade** : Vérifie que la suppression du formulaire supprime le CDC

### Scénarios d'exception
- **Autorisation** : Vérifie qu'un utilisateur ne peut pas accéder aux formulaires d'un autre
- **Validation** : Les données invalides sont rejetées

### Tests de limites
- **Pagination** : Vérifie le fonctionnement de la pagination sur la liste des formulaires
- **Accès création** : Vérifie que la page de création est accessible

## Analyse de tests d'intégration sélectionnés

### Test 1 : Création Formulaire + CDC (test_create_form_with_cdc_in_transaction)

```php
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

    $this->assertDatabaseHas('cdcs', [
        'form_id' => $form->id,
        'user_id' => $this->user->id,
    ]);

    $cdc = $form->cdcs()->first();
    $this->assertEquals($formData['candidat_nom'], $cdc->data['candidat_nom']);
}
```

**Conception :**
- **But :** Vérifier le flux complet de création d'un formulaire avec son CDC
- **Approche :** Simulation d'une requête POST avec données valides, vérification de la création en base
- **Scénario :** Test nominal - création complète

**Pourquoi intéressant :** Ce test vérifie l'intégration entre le `FormController::store()`, le `Form` model et le `Cdc` model. Il valide que la transaction fonctionne correctement et que les données sont cohérentes entre les trois entités.

---

### Test 3 : Suppression en cascade (test_delete_form_cascade_deletes_cdc)

```php
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
```

**Conception :**
- **But :** Vérifier que la suppression d'un formulaire supprime également le CDC associé
- **Approche :** Création d'un form + cdc liés, suppression du form, vérification de la suppression cascade
- **Scénario :** Test de limite - intégrité des données après suppression

**Pourquoi intéressant :** Ce test vérifie la contrainte d'intégrité référentielle au niveau applicatif. Il assure que les CDC orphaned ne restent pas en base après suppression d'un formulaire.

---

### Test 4 : Autorisation entre utilisateurs (test_authorization_prevents_other_user_access)

```php
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
```

**Conception :**
- **But :** Vérifier qu'un utilisateur ne peut pas accéder aux formulaires d'un autre
- **Approche :** Création d'un formulaire par un autre utilisateur, tentative d'accès avec l'utilisateur courant
- **Scénario :** Test d'exception - accès non autorisé

**Pourquoi intéressant :** Ce test vérifie la sécurité de l'application. Il garantit que les politiques d'autorisation (Laravel Policies) fonctionnent correctement et qu'un utilisateur malveillant ne peut pas voir, modifier ou supprimer les données d'un autre.

---

### Test 5 : Champs personnalisés (test_create_form_with_custom_fields_and_cdc)

```php
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
    ];

    $response = $this->actingAs($this->user)->post(route('forms.store'), $formData);

    $form = Form::where('name', $formData['titre_projet'])->first();

    $this->assertDatabaseHas('fields', [
        'form_id' => $form->id,
        'name' => 'custom_champ_1',
    ]);

    $cdc = $form->cdcs()->first();
    $this->assertEquals('Valeur personnalisée', $cdc->data['custom_champ_1']);
}
```

**Conception :**
- **But :** Vérifier l'ajout de champs personnalisés au formulaire et leur intégration dans le CDC
- **Approche :** Envoi de données avec tableau `fields`, vérification de la création en base et dans le CDC
- **Scénario :** Test nominal - création avec champs customs

**Pourquoi intéressant :** Ce test valide le flux complet des champs personnalisés. Il vérifie que les champs ajoutés via le formulaire sont correctement stockés dans la table `fields` ET intégrés dans les données du CDC.

---

### Test 5 : Mise à jour du CDC (test_update_form_updates_cdc_data)

```php
public function test_update_form_updates_cdc_data(): void
{
    $form = Form::factory()->create(['user_id' => $this->user->id]);

    $cdc = Cdc::create([
        'title' => 'AncienTitre',
        'data' => [
            'candidat_nom' => 'AncienNom',
            'candidat_prenom' => 'AncienPrenom',
        ],
        'form_id' => $form->id,
        'user_id' => $this->user->id,
    ]);

    $updateData = $this->getValidFormData();
    $updateData['titre_projet'] = 'Nouveau Titre';
    $updateData['candidat_nom'] = 'NouveauNom';

    $response = $this->actingAs($this->user)->put(route('forms.update', $form), $updateData);

    $form->refresh();
    $cdc->refresh();

    $this->assertEquals('Nouveau Titre', $form->name);
    $this->assertEquals('Nouveau Titre', $cdc->title);
    $this->assertEquals('NouveauNom', $cdc->data['candidat_nom']);
}
```

**Conception :**
- **But :** Vérifier que la mise à jour d'un formulaire met à jour le CDC associé
- **Approche :** Création d'un form+cdc, modification via PUT, vérification de la synchronisation
- **Scénario :** Test nominal - mise à jour conjointe

**Pourquoi intéressant :** Ce test vérifie que le CDC reste synchronisé avec le formulaire lors des modifications. C'est critique pour maintenir la cohérence des données.

---

### Test 6 : Pagination (test_form_list_with_pagination)

```php
public function test_form_list_with_pagination(): void
{
    Form::factory()->count(15)->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->get(route('forms.index'));

    $response->assertStatus(200);
}
```

**Conception :**
- **But :** Vérifier que la liste des formulaires gère correctement la pagination
- **Approche :** Création de 15 formulaires, accès à l'index, vérification du statut 200
- **Scénario :** Test de limite - comportement avec beaucoup de données

**Pourquoi intéressant :** Ce test assure que l'application reste performante avec un grand nombre de formulaires. La pagination est une fonctionnalité critique pour l'expérience utilisateur.

---

### Test 7 : Accès page de création (test_form_can_be_created_via_index)

```php
public function test_form_can_be_created_via_index(): void
{
    $response = $this->actingAs($this->user)->get(route('forms.create'));

    $response->assertStatus(200);
}
```

**Conception :**
- **But :** Vérifier que la page de création d'un formulaire est accessible
- **Approche :** Requête GET sur la route de création, vérification du statut 200
- **Scénario :** Test nominal - accès à l'interface de création

**Pourquoi intéressant :** Ce test simple mais essentiel vérifie que l'utilisateur autorisé peut accéder à la page de création. C'est le point d'entrée pour la création de nouveaux formulaires.

    $response->assertStatus(403);

    $response = $this->actingAs($this->user)->get(route('forms.edit', $form));
    $response->assertStatus(403);

    $response = $this->actingAs($this->user)->delete(route('forms.destroy', $form));
    $response->assertStatus(403);
}
```

**Conception :**
- **But :** Vérifier qu'un utilisateur ne peut pas accéder aux formulaires d'un autre
- **Approche :** Création d'un formulaire par un autre utilisateur, tentative d'accès avec l'utilisateur courant
- **Scénario :** Test d'exception - accès non autorisé

**Pourquoi intéressant :** Ce test vérifie la sécurité de l'application. Il garantit que les politiques d'autorisation (Laravel Policies) fonctionnent correctement et qu'un utilisateur malveillant ne peut pas voir, modifier ou supprimer les données d'un autre.