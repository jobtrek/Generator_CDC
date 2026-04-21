# 📋 CONCEPT DE TEST COMPLET
## Application de Génération de Cahiers de Charges avec PHPWord

**Projet :** Application Web Laravel - Génération de CDC  
**Version :** 1.0  
**Module 450 - Tester des applications (2025/2026)**  
**Auteur :** [Votre nom]  
**Date :** Janvier 2026

---

## 1. OBJECTIFS DU CONCEPT

L'objectif principal est de **valider le bon fonctionnement global** de l'application en s'assurant que :

**Authentification & Autorisation**
- Les utilisateurs peuvent se connecter et se déconnecter
- Seul le propriétaire du formulaire/CDC peut y accéder et le modifier
- Les erreurs d'authentification sont gérées correctement (email invalide, mot de passe incorrect)

**Gestion des formulaires (CRUD)**
- Créer un formulaire avec des champs standards et personnalisés
- Lire et afficher les formulaires créés
- Modifier un formulaire existant
- Supprimer un formulaire

**Gestion des cahiers de charges (CDC)**
- Générer un CDC à partir d'un formulaire complété
- Accéder à l'historique des CDC créés
- Télécharger le fichier Word généré avec PHPWord
- Vérifier que les données sont correctement intégrées dans le document

**Génération de documents**
- Le fichier Word contient toutes les sections (informations générales, procédure, etc.)
- Les données saisies sont correctement injectées dans le document
- Le fichier est bien formé et téléchargeable
- Les formats (dates, horaires, calcul des heures) sont corrects

**Intégrité des données**
- Les données sont correctement stockées en base de données
- Les relations entre User, Form, Field, Cdc sont maintenues
- Les suppressions en cascade fonctionnent correctement

---

## 2. OBJETS À TESTER

### 2.1 Modèles & Entités
| Objet | Responsabilité | Cas à tester |
|-------|-----------------|--------------|
| **User** | Authentification, propriétaire de formulaires/CDC | Inscription, connexion, relation avec formulaires |
| **Form** | Conteneur de champs, lié à un utilisateur | CRUD, relation avec fields et cdc |
| **Field** | Champ d'un formulaire | Création, modification, suppression, ordre |
| **FieldType** | Type de champ (text, email, date, etc.) | Validation associée |
| **Cdc** | Cahier des charges généré | Génération, stockage des données JSON, téléchargement |

### 2.2 Contrôleurs & Routes
| Contrôleur | Action | Cas à tester |
|------------|--------|--------------|
| **FormController** | index() | Lister les formulaires de l'utilisateur avec pagination et recherche |
| | create() | Afficher le formulaire de création |
| | store() | Créer un formulaire + CDC |
| | show() | Afficher un formulaire |
| | edit() | Afficher le formulaire d'édition |
| | update() | Modifier un formulaire + CDC |
| | destroy() | Supprimer un formulaire |
| **CdcController** | create() | Accéder à la page de création de CDC |
| | download() | Télécharger le fichier Word généré |

### 2.3 Services
| Service | Responsabilité | Cas à tester |
|---------|-----------------|--------------|
| **FormService** | Créer/modifier formulaire + CDC en transaction | Cohérence des données |
| **CdcDataBuilder** | Construire les données du CDC | Formatage des dates, heures, planning |
| **CdcPhpWordGenerator** | Générer le document Word | Structure du document, injection de données |
| **FieldsManager** | Gérer les champs (créer, modifier, supprimer) | CRUD des champs |
| **DateTimeFormatter** | Formater les dates et horaires | Format correct (locale fr) |

---

## 3. TYPES DE TESTS

### 3.1 TESTS UNITAIRES
**Niveau :** Composant isolé | **Responsable :** Développeurs | **Fréquence :** À chaque commit

**Champs testés :**

```
TestDateTimeFormatter.php
  buildPeriodeRealisation() formate correctement les dates
  buildHoraireTravail() combine les heures correctement
  Les formats respectent la locale française

TestFormFieldsService.php
  isStandardField() identifie les champs standards
  getStandardFields() retourne la liste complète

TestCdcDataBuilder.php
  build() crée la structure de données correcte
  Les données optionnelles sont bien gérées
  Validation des données d'entrée

TestFieldsManager.php
  createCustomFields() crée les champs personnalisés
  isCustomField() distingue champs standards et perso
  updateCustomFields() modifie les données
  deleteFields() supprime les champs

TestCdc.php (Modèle)
  data est casté en array
  form() retourne une relation BelongsTo
  user() retourne une relation BelongsTo
  isManual() vérifie que form_id est null

TestForm.php (Modèle)
  fields() sont triés par order_index
  cdc() retourne une relation HasOne
  user() retourne le propriétaire
```

---

### 3.2 TESTS D'INTÉGRATION
**Niveau :** Plusieurs composants + Base de données | **Responsable :** QA/Développeurs | **Fréquence :** Quotidienne

**Scénarios testés :**

```
TestFormIntegration.php
  Créer un formulaire → vérifier en base de données
  Ajouter des champs au formulaire → vérifier les relations
  Modifier un formulaire → champs mis à jour
  Supprimer un formulaire → suppression en cascade des champs
  Créer un formulaire + CDC → transaction réussie
  
TestCdcIntegration.php
  Créer un CDC avec données → vérifier en base
  CDC contient les données JSON correctes
  Relation User → CDC → Form fonctionne
  form_id nullable → CDC peut exister sans formulaire

TestFormServiceIntegration.php
  createFormWithCdc() transaction réussie (form + fields + cdc)
  updateFormWithCdc() met à jour form, fields et cdc
  getPrefillDataForEdit() retourne les bonnes données
  Données personnalisées persistent après modification

TestWordGeneratorIntegration.php
  generate() crée un fichier .docx valide
  Le fichier contient les sections attendues
  Les données sont injectées correctement
  Les tableaux sont bien structurés
  Gestion du markdown dans descriptif_projet
  Calcul du nombre d'heures correct
```

---

### 3.3 TESTS E2E (End-to-End)
**Niveau :** Flux complet utilisateur | **Responsable :** QA | **Fréquence :** Avant chaque release | **Outil :** Playwright

**Cas de test E2E :**

```
E2E_CreateCdcFlow.spec.ts
Scénario: Créer un CDC complet du début à la fin

  Étape 1: AUTHENTIFICATION
    Accéder à la page d'accueil
    Se connecter avec email/password
    Arriver sur le dashboard

  Étape 2: CRÉER UN FORMULAIRE
    Cliquer sur "Nouveau formulaire"
    Remplir "Titre projet"
    Remplir toutes les données standards:
      - Candidat (nom, prénom)
      - Lieu de travail
      - Chef de projet (nom, prenom, email, tel)
      - Expert 1 et 2 (nom, prenom, email, tel)
      - Dates (début, fin)
      - Horaires (matin début/fin, aprem début/fin)
      - Planning (analyse, implémentation, tests, docs)
      - Procédure
      - Matériel logiciel
      - Prérequis
      - Descriptif projet (avec markdown)
      - Livrables
    Soumettre le formulaire
    Vérifier "Formulaire créé avec succès"

  Étape 3: VISUALISER LE FORMULAIRE
    Accéder à la page de visualisation
    Vérifier que tous les champs sont remplis
    Lien "Générer CDC" visible

  Étape 4: GÉNÉRER ET TÉLÉCHARGER LE CDC
    Cliquer sur "Générer CDC"
    Vérifier page de confirmation
    Cliquer sur "Télécharger"
    Fichier téléchargé: cdc-[slug].docx
    Vérifier que le fichier n'est pas vide
    Vérifier que c'est un document Word valide

  Étape 5: VÉRIFIER LE DOCUMENT GÉNÉRÉ
    Ouvrir le fichier Word téléchargé
    Vérifier header/footer (page, version)
    Vérifier "1 INFORMATIONS GÉNÉRALES":
      - Candidat: nom + prénom corrects
      - Chef de projet: email visible
      - Experts: données présentes
      - Horaires: formatés correctement
      - Planning: total heures calculé
    Vérifier "2 PROCÉDURE" contient le texte
    Vérifier "3 TITRE" contient le titre du projet
    Vérifier "4 MATÉRIEL ET LOGICIEL"
    Vérifier "5 PRÉREQUIS"
    Vérifier "6 DESCRIPTIF DU PROJET" (markdown parsé)
    Vérifier "7 LIVRABLES"
    Vérifier "8 POINTS TECHNIQUES" (champs custom si présents)
    Vérifier "9 VALIDATION" (tableau des signatures)

  Étape 6: MODIFIER LE FORMULAIRE
    Aller sur le formulaire créé
    Cliquer "Modifier"
    Changer le titre du projet
    Ajouter un nouveau champ personnalisé
    Remplir la valeur du champ
    Soumettre la modification
    Vérifier "Formulaire mis à jour avec succès"
    Générer à nouveau le CDC
    Vérifier que le nouveau champ apparaît dans la section 8

  Étape 7: LISTER LES FORMULAIRES
    Aller sur le dashboard
    Afficher la liste de tous les formulaires créés
    Pagination fonctionne (8 par page)
    Recherche par titre fonctionne
    Filtrage par date fonctionne

  Étape 8: SUPPRIMER UN FORMULAIRE
    Cliquer sur "Supprimer" pour un formulaire
    Confirmer la suppression
    Vérifier "Formulaire supprimé avec succès"
    Formulaire n'apparaît plus dans la liste
    CDC associé supprimé en cascade

  Étape 9: TEST DE SÉCURITÉ
    Utilisateur A crée un formulaire
    Utilisateur B essaie d'accéder au formulaire → 403
    Utilisateur B essaie de modifier le formulaire → 403
    Utilisateur B essaie de télécharger le CDC → 403

  Étape 10: DÉCONNEXION
    Cliquer sur "Déconnexion"
    Redirection vers page d'accueil
    Les routes protégées retournent 302 redirect login
```

---

## 4. INFRASTRUCTURE DE TEST

### 4.1 Environnements
| Environnement | Base | Cache | Fichiers | Usage |
|---|---|---|---|---|
| **Testing** (PHPUnit) | SQLite in-memory | Array | Storage/testing | Tests unitaires + intégration |
| **Testing** (Playwright) | SQLite fichier | Redis/file | Storage/public/testing | Tests E2E |
| **CI/CD** (GitHub Actions) | SQLite | Redis | Storage | Automatisation |

### 4.2 Base de données de test
```
phpunit.xml
<php>
  <env name="DB_CONNECTION" value="sqlite"/>
  <env name="DB_DATABASE" value=":memory:"/>
  <env name="MAIL_DRIVER" value="log"/>
</php>
```

### 4.3 Données de test
- **FieldTypes** pré-créés (text, email, date, textarea, etc.)
- **Users** test créés via factories
- **Forms** avec champs variés
- **Files** cleanup après chaque test

### 4.4 Outils & Dépendances

**Tests Unitaires & Intégration :**
```
- PHPUnit (Laravel built-in)
- Faker (génération données)
- Database transactions / Refresh database
```

**Tests E2E :**
```
- Playwright (automatisation navigateur)
- Node.js
- Chrome/Chromium headless
```

---

## 5. ORGANISATION DES TESTS

### 5.1 Rôles & Responsabilités

| Rôle | Tâches | Outils |
|------|--------|--------|
| **Développeur** | Tests unitaires + d'intégration | PHPUnit |
| **QA** | Tests E2E, tests de régression | Playwright |
| **DevOps** | Automatisation CI/CD | GitHub Actions |
| **Lead** | Validation, approbation des tests | Jira/Trello |

### 5.2 Moyens de communication

- **Signalement de défauts** → Issues GitHub + Slack
- **Récupération d'informations** → Comments GitHub
- **Signalement corrections** → Pull Request reviewed
- **Documentation** → README + Comments de code

### 5.3 Formalisme des défauts

Tout défaut doit contenir :
- **Titre** : Clair et concis
- **Sévérité** : Critique / Majeur / Mineur
- **Étapes de reproduction** : Pas à pas
- **Résultat attendu** : Ce qui devrait se passer
- **Résultat obtenu** : Ce qui se passe réellement
- **Logs/Screenshots** : Preuves
- **Environnement** : Browser, OS, version app

---

## 6. PLAN DE TEST

### 6.1 Timeline de test

| Phase | Dates | Tests | Validateur |
|-------|-------|-------|-----------|
| **Préparation** | Semaine 1 | Setup infra, création factories | Dev |
| **Tests Unitaires** | Semaine 1-2 | 40+ tests | Dev |
| **Tests Intégration** | Semaine 2-3 | 25+ tests | Dev + QA |
| **Tests E2E** | Semaine 3 | 10 scénarios complets | QA |
| **Régression** | Semaine 4 | Rejeux complet | QA |
| **Release** | Semaine 4 | Validation finale | Lead |

### 6.2 Progression (Burndown)

```
Target: 100% coverage des fonctionnalités critiques
- Semaine 1: 30% (unitaires)
- Semaine 2: 65% (intégration)
- Semaine 3: 90% (E2E)
- Semaine 4: 100% (régression)
```

---

## 7. CLASSIFICATION DES DÉFAUTS

### 7.1 Par Sévérité

| Sévérité | Description | Exemple | Action |
|----------|-------------|---------|--------|
| CRITIQUE | Bloque l'utilisateur complètement | CDC non généré, authentification cassée | Fix immédiat |
| MAJEUR | Fonctionnalité cassée partiellement | Données manquantes dans le Word | Fix dans le sprint |
| MINEUR | Problème cosmétique/UX | Alignement d'un bouton | Fix avant release |
| COSMÉTIQUE | Amélioration future | Notification pourrait être mieux | Backlog |

### 7.2 Par Fréquence

| Fréquence | Définition |
|-----------|-----------|
| **Systématique** | Se reproduit 100% |
| **Fréquent** | Se reproduit 70%+ |
| **Occasionnel** | Se reproduit 30-70% |
| **Rarissime** | Se reproduit <30% |

### 7.3 Par Type

| Type | Catégorie |
|------|-----------|
| **Fonctionnel** | La feature ne marche pas |
| **Interface** | Bouton manquant, texte cassé |
| **Performance** | Lent (>5s pour générer) |
| **Sécurité** | Accès non autorisé possible |
| **Données** | Données incorrectes ou perdues |
| **Compatibilité** | Chrome/Firefox/Safari |

### 7.4 Par Priorité

| Priorité | Règles |
|----------|--------|
| **P0** | Critique + Systématique = Fix immédiat |
| **P1** | Critique + Fréquent OU Majeur + Systématique = ASAP |
| **P2** | Majeur + Fréquent OU Mineur + Systématique = Ce sprint |
| **P3** | Mineur + Non systématique = Prochain sprint |

---

## 8. CRITÈRES DE SUCCÈS

Tous les tests sont passants
- Tests unitaires : 100% des cas couverts
- Tests intégration : Tous les flux fonctionnent
- Tests E2E : Scénarios critiques réussis

Code quality
- Pas d'erreurs PHP (phpstan level 8)
- Pas de warnings
- Coverage > 70%

Performance
- Génération CDC < 3 secondes
- Chargement page < 1 seconde
- Téléchargement fichier immédiat

Sécurité
- Pas de failles d'autorisation détectées
- Validation des données côté serveur
- Injection SQL impossible

Documentation
- README avec instructions test
- Commentaires dans le code
- Guides de test E2E

---

## ANNEXE A : Structure des répertoires de test

```
tests/
├── Unit/
│   ├── Services/
│   │   ├── DateTimeFormatterTest.php
│   │   ├── FormFieldsServiceTest.php
│   │   ├── CdcDataBuilderTest.php
│   │   ├── FieldsManagerTest.php
│   │   └── CdcPhpWordGeneratorTest.php
│   └── Models/
│       ├── FormTest.php
│       ├── CdcTest.php
│       ├── UserTest.php
│       └── FieldTest.php
│
├── Integration/
│   ├── FormIntegrationTest.php
│   ├── CdcIntegrationTest.php
│   ├── FormServiceIntegrationTest.php
│   └── WordGeneratorIntegrationTest.php
│
├── Feature/ (HTTP tests)
│   ├── FormControllerTest.php
│   ├── CdcControllerTest.php
│   └── AuthenticationTest.php
│
├── E2E/ (Playwright)
│   ├── create-cdc.spec.ts
│   ├── edit-cdc.spec.ts
│   ├── list-forms.spec.ts
│   ├── delete-form.spec.ts
│   └── security.spec.ts
│
└── Fixtures/
    ├── create-user.php
    ├── create-form.php
    └── create-cdc.php
```

---

## ANNEXE B : Commandes d'exécution

```bash
# Tests unitaires & intégration
php artisan test

# Seulement unitaires
php artisan test tests/Unit

# Seulement intégration
php artisan test tests/Integration

# Avec coverage
php artisan test --coverage

# Tests E2E
npx playwright test

# Tests E2E headed (voir navigateur)
npx playwright test --headed

# Test spécifique
php artisan test tests/Feature/FormControllerTest.php
```

---

**Document approuvé par :** [À compléter]  
**Dernière révision :** Janvier 2026
