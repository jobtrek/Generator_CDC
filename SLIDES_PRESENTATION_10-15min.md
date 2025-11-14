---
title: Présentation — Generateur CDC
author: Équipe Projet
date: 2025-11-14
---

# Generateur CDC

**Durée totale suggérée : 10-15 minutes**

Speaker notes: ouverture rapide, se présenter, expliquer l'objectif de la démo.

Temps suggéré : 30-45s

---

# Agenda

1. Intro & mission
2. Stack et architecture technique
3. Flux utilisateur (création d'un CDC → export)
4. Démo rapide / screenshots
5. Composants clés (models, controllers, services)
6. Points d'amélioration / roadmap
7. Conclusion & Q&A

Temps suggéré : 30s

---

# 1) Intro & mission

- Objectif : fournir une application permettant de créer, gérer et exporter des Cahiers des Charges (CDC).
- Valeur : standardiser la collecte des besoins, produire automatiquement un document de livraison (DOCX/Markdown/HTML).
- Public cible : PO, BA, chefs de projet, équipes techniques.

Points clés à dire : problème résolu, résultat attendu, bénéfices immédiats.

Temps suggéré : 1 min

---

# 2) Stack & architecture technique

- Backend : Laravel (PHP 8+)
- Base de données : SQLite (dev), facilement adaptable à MySQL/Postgres
- Auth & perms : Laravel Auth + Spatie Laravel Permission
- Front : Blade + Tailwind + Vite
- Export : service `CdcPandocGenerator` (utilise pandoc pour produire DOCX)

Schéma simplifié à montrer : Utilisateur → Controllers → Models (Cdc/Form/Field) → Service d'export → Fichier (.docx)

Temps suggéré : 1-2 min

---

# 3) Flux utilisateur (création d'un CDC → export)

1. L'utilisateur crée un formulaire (Form) et ajoute des champs (Field)
2. On compose un CDC à partir d'un Form ou plusieurs sections
3. Le CDC est sauvegardé (table `cdcs`), lié à l'utilisateur
4. L'utilisateur clique "Exporter" → Controller appelle le service d'export
5. `CdcPandocGenerator` rend une vue Blade (template Word) et appelle Pandoc pour générer `.docx`
6. Fichier disponible en téléchargement via storage/public

Temps suggéré : 2 min

Visuel recommandé : 3 captures : écran création, écran liste CDC, téléchargement export

---

# 4) Démo rapide (ou screenshots)

Option A — Demo live (recommandé si stable) :
- Montrer création d'un nouveau CDC (1 min)
- Lancer l'export et ouvrir le fichier `.docx` (1 min)

Option B — Screenshots :
- Création du formulaire
- Interface de liste des CDC
- Fichier DOCX généré (aperçu dans Word/LibreOffice)

Temps suggéré : 2-3 min

Notes techniques : si live, exécuter `php artisan serve` localement et montrer le parcours. Si Pandoc n'est pas installé, montrer le fallback (si implémenté) ou expliquer la dépendance.

---

# 5) Composants clés (code & responsabilités)

- `app/Models/` :
  - `Cdc.php` — modèle CDC
  - `Form.php` — modèle Formulaire
  - `Field.php` — champs
  - `FieldType.php` — types de champs
  - `User.php` — utilisateur (HasRoles)

- `app/Http/Controllers/` : contrôleurs CRUD pour CDC / Form
  - `CdcController.php` — actions : index, show, create, store, download (export)

- `app/Services/CdcPandocGenerator.php` : service responsable de la génération du document (rendu Blade → Pandoc)

- `app/Policies/` : règles d'accès (CdcPolicy / FormPolicy)

Temps suggéré : 1-2 min

Conseil : lors de la présentation, montrer brièvement le fichier du service d'export (2-3 extraits) pour expliquer le rendu puis l'appel système à pandoc.

---

# 6) Points d'amélioration / Roadmap

Priorités immédiates :
- Rendre l'export Pandoc optionnel (fallback HTML/MD) — éviter une dépendance qui casse la démo
- Extraire une interface `CdcGeneratorInterface` et fournir plusieurs implémentations (Pandoc, HTML/MD)
- Nettoyage : supprimer code orphelin (`app/Helpers/RoleHelper.php`) et composants inutilisés
- Tests automatisés : renforcer la couverture, ajouter tests d'intégration pour l'export

Améliorations long terme :
- CI (GitHub Actions) : tests + phpstan + style
- Ajout d'une UI plus riche (React/Vue) si besoin
- Export multipage / templates paramétrables

Temps suggéré : 1-2 min

---

# 7) Conclusion & Q&A

- Résumé : objectif atteint — création et export de CDC automatisés
- Prochaines étapes proposées : rendre l'export résilient, nettoyage et CI
- Ouvrir pour questions

Temps suggéré : 30-60s

---

# Annexes (notes du présentateur et commandes utiles)

- Fichiers à montrer rapidement :
  - `app/Services/CdcPandocGenerator.php`
  - `app/Http/Controllers/CdcController.php` (méthode `download`)
  - `resources/views/cdcs/word-template.blade.php`

- Commandes rapides (local) — exécuter seulement si besoin :

```bash
# fish shell
composer install
php artisan migrate --seed
php artisan serve
# (optionnel) pour tests
./vendor/bin/phpunit --colors=always
```

- Convertir ce markdown en slides (optionnel) :
  - Avec reveal.js ou `reveal-md`, ou avec pandoc → reveal.js
  - Exemple (si pandoc/reveal installés) :

```bash
# convertir en HTML reveal.js
pandoc -t revealjs -s SLIDES_PRESENTATION_10-15min.md -o slides.html
```

---

# Conseils pour respecter la contrainte de temps

- 0:30 — Intro & mission
- 1:00 — Stack & architecture
- 3:00 — Flux utilisateur
- 5:30 — Démo / screenshots
- 8:30 — Composants clés
- 10:30 — Roadmap & conclusion
- 12:00 — Q&A (reste)

Ajuste le tempo selon l'intérêt et les questions.

---

Fin du deck — prêt à l'emploi.

