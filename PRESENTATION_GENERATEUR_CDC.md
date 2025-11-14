# Présentation du projet — Generateur CDC

Ce document présente de façon synthétique et pédagogique le projet « generateur-cdc ». Il sert pour une présentation générale (démo, revue, onboarding) et contient : but, stack, structure du code, composants clés, commandes pour exécuter et tester, points d'amélioration et fichiers potentiellement supprimables.

---

## 1. But du projet

Generateur CDC est une application Laravel destinée à créer, gérer et exporter des Cahiers des Charges (CDC). Elle permet de créer des formulaires, des champs, et de générer des documents (export Word via pandoc) à partir d'un CDC.

## 2. Stack technique

- Backend : PHP 8+ avec Laravel (structure standard de projet Laravel)
- DB : SQLite (fichier `database/database.sqlite` présent pour dev/test)
- Auth : système d'authentification Laravel + package Spatie (roles/permissions)
- Template / Front : Blade + TailwindCSS + Vite
- Tests : PHPUnit (fichiers de config fournis : `phpunit.xml`)

## 3. Démarrage rapide (environnement de développement)

1. Installer les dépendances PHP :

```bash
# fish shell
composer install
```

2. Préparer le stockage et l'autoload :

```bash
php artisan storage:link
composer dump-autoload
php artisan optimize:clear
```

3. Migrer / seed (exécution sur SQLite incluse) :

```bash
php artisan migrate --seed
```

4. Lancer l'application localement :

```bash
php artisan serve
```

5. Lancer les tests :

```bash
./vendor/bin/phpunit --colors=always
```

> Remarque : Si Pandoc est utilisé pour l'export `.docx`, il faut que `pandoc` soit installé sur la machine (ou fournir un fallback). Le service `app/Services/CdcPandocGenerator.php` exécute la commande `pandoc`.

## 4. Arborescence et composants clés

Racine :
- `artisan` : binaire Laravel
- `composer.json` / `composer.lock`
- `package.json` etc.

Dossiers importants (sélection) :

- `app/Models/` : modèles Eloquent
  - `Cdc.php` — modèle principal représentant un Cahier des Charges
  - `Form.php` — modèle Formulaire
  - `Field.php` — champs d'un formulaire
  - `FieldType.php` — types de champs
  - `User.php` — modèle utilisateur (utilise Spatie HasRoles)

- `app/Http/Controllers/` : contrôleurs HTTP (CRUD pour CDC, Forms, etc.)

- `app/Services/CdcPandocGenerator.php` : service qui génère un `.docx` via Pandoc à partir d'une vue Blade `cdcs.word-template`.

- `app/Policies/` : politiques d'autorisation
  - `CdcPolicy.php`, `FormPolicy.php`

- `app/View/Components/` : composants Blade (AppLayout, GuestLayout, ...)

- `app/Helpers/RoleHelper.php` : helper présent mais sans autres références externes (probablement orphelin)

- `resources/views/` : vues Blade
  - `cdcs/word-template.blade.php` (utilisée par le service Pandoc)

- `database/` : migrations, seeds et factories
  - `migrations/` : tables pour users, permissions, fields, forms, cdcs, etc.
  - `seeders/` : `AdminUserSeeder`, `FieldTypeSeeder`, `RolePermissionSeeder`

## 5. Comportements principaux et flux

- Création d'un CDC : via un formulaire, stocké dans `cdcs` (table + modèle `Cdc`)
- Association : un `User` a plusieurs `Form` et `Cdc` (relations Eloquent)
- Export Word : contrôleur `CdcController` appelle `CdcPandocGenerator->generate($cdc)` pour produire un `.docx` stocké sous `storage/app/public/cdcs/` et renvoie le chemin public.
- Autorisations : gérées par Spatie (rôles/permissions) et policies locales pour `Cdc` et `Form`.

## 6. Fichiers et éléments potentiellement supprimables ou à examiner

Avant toute suppression : faire un commit de sauvegarde et exécuter une recherche globale pour confirmer l'absence de références.

Actions sûres proposées :
- `app/Helpers/RoleHelper.php` — aucune référence détectée dans le code applicatif (seule présence dans l'autoload généré). Probablement supprimable après vérification finale.

Actions à vérifier / refactor recommandés :
- `app/Services/CdcPandocGenerator.php` — utilisé par `CdcController`. Si tu veux réduire les dépendances externes, proposer :
  - Extraire une interface `App\Contracts\CdcGeneratorInterface` et faire binder l'implémentation `CdcPandocGenerator` dans `AppServiceProvider`. Ajouter un fallback `CdcSimpleGenerator` qui génère un `.docx` simplifié (ou export Markdown/HTML) sans Pandoc.
  - Ou rendre Pandoc optionnel : si `pandoc` non disponible, retourner un export HTML/MD.

- Composants Blade : vérifier `app/View/Components/*` si certains ne sont pas utilisés dans `resources/views`.

- Seeders / Factories : supprimer ceux inutilisés si le projet n'a pas besoin d'eux en prod.

- Dépendances composer : analyser `composer.json` et supprimer les paquets non utilisés (après vérification) ; Spatie doit rester si on utilise les rôles/permissions.

## 7. Cartographie rapide (fichiers -> responsabilité)

- `app/Models/Cdc.php` : données d'un CDC
- `app/Models/Form.php` : structure d'un formulaire lié au CDC
- `app/Models/Field.php` : champs (label, type, options, ordre)
- `app/Models/FieldType.php` : types (texte, textarea, checkbox, ...)
- `app/Models/User.php` : compte utilisateur, roles (Spatie)
- `app/Services/CdcPandocGenerator.php` : export `.docx` via Pandoc
- `app/Policies/*` : règles authorization
- `app/Helpers/RoleHelper.php` : helper rôle (probablement orphelin)

## 8. Commandes utiles (fish shell)

- Installer :

```bash
composer install
npm install # si tu veux builder les assets
```

- Build assets (dev / prod) :

```bash
# dev (vite)
npm run dev
# build
npm run build
```

- Artisan utiles :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear
php artisan serve
```

- Tests :

```bash
./vendor/bin/phpunit --colors=always
```

## 9. Recommandations pour la présentation (slides)

Structure conseillée pour une présentation de 10-15 minutes :

1. Intro & mission du projet
2. Stack et architecture technique
3. Flux utilisateur (création d'un CDC -> export)
4. Démo rapide (création + export) ou screenshots
5. Composants clés (models, controllers, services)
6. Points d'amélioration / roadmap (refactor pandoc, nettoyage, tests/QA)
7. Conclusion et Q&A

Inclure des captures d'écran : interface de création de CDC, exemple de fichier `.docx` généré.

## 10. Propositions d'améliorations détaillées (actionnable)

- Refactor 1 (faible risque) : supprimer `RoleHelper` si non utilisé
- Refactor 2 (moyen) : extraire interface pour générateurs (`CdcGeneratorInterface`) et fournir fallback si `pandoc` absent
- Refactor 3 (moyen) : ajouter phpstan/psalm pour un contrôle statique, configuer un pipeline (GitHub Actions) pour tests + analyse
- Refactor 4 (faible) : consolider/éclaircir les components Blade et partials
- Refactor 5 (moyen) : centraliser configuration (paths d'export) dans `config/cdcs.php` pour éviter chemins codés en dur

## 11. Check-list avant suppression de fichiers

- [ ] Faire un commit/branch de sauvegarde
- [ ] Rechercher globalement la référence du fichier à supprimer (`grep -R "RoleHelper" --exclude-dir=vendor`)
- [ ] Exécuter les tests
- [ ] Lancer l'application et tester les parcours critiques (création CDC, export)

## 12. Annexes: Recherches rapides effectuées

- `app/Services/CdcPandocGenerator.php` est utilisé dans `app/Http/Controllers/CdcController.php` pour la route d'export.
- `app/Helpers/RoleHelper.php` : aucune autre référence détectée dans le code applicatif (seulement autoload dans `vendor/`), donc potentiellement orphelin.
- `app/Models/User.php` : utilise `Spatie\Permission\Traits\HasRoles` et expose relations `forms()` et `cdcs()`.

---

Si tu veux, je peux :
- Committer automatiquement une branche `cleanup/rolehelper-remove` et supprimer `app/Helpers/RoleHelper.php` puis exécuter les tests pour valider (action sûre). Ou :
- Implémenter tout de suite un refactor pour `CdcPandocGenerator` en extrayant une interface + fallback HTML (je te ferai une PR locale dans le repo).

Dis-moi quelle action tu veux que j'effectue maintenant : supprimer `RoleHelper` en commit, ou créer l'interface + fallback pour l'export, ou générer des slides basés sur ce markdown (format PDF/slide)?

