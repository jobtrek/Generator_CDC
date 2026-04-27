# Tests E2E - Générateur CDC

Guide pour exécuter les tests E2E avec Laravel Dusk.

## Résumé

### Tests E2E Automatisés ✓
Fichier: `tests/Browser/CreateCdcFlowTest.php`

| # | Test | Description | Statut |
|---|------|-------------|--------|
| 1 | `test_user_can_login_and_access_dashboard` | Connexion et dashboard | ✓ Pass |
| 2 | `test_user_can_visit_forms_index` | Liste des formulaires | ✓ Pass |
| 3 | `test_user_can_visit_create_form_page` | Page création | ✓ Pass |
| 4 | `test_forms_show_displays_form` | Visualisation formulaire | ✓ Pass |
| 5 | `test_forms_list_shows_pagination` | Pagination liste | ✓ Pass |

### Tests E2E Manuels (à effectuer)

## Commandes

```bash
# Exécuter tous les tests E2E
php artisan dusk

# Modeheaded (voir navigateur)
php artisan dusk --headless=false
```

## Scénarios de Test E2E Manuels

### Scénario 1: Inscription/Connexion
1. Accéder à la page d'accueil
2. S'inscrire ou se connecter
3. Vérifier redirection vers dashboard

### Scénario 2: Création Complète CDC
1. Se connecter
2. Aller sur "Nouveau formulaire"
3. Remplir toutes les sections
4. Soumettre le formulaire
5. Vérifier message de succès

### Scénario 3: Génération et Téléchargement
1. Cliquer sur "Générer CDC"
2. Cliquer sur "Télécharger"
3. Vérifier fichier .docx téléchargé

### Scénario 4: Modification
1. Ouvrir un formulaire existant
2. Cliquer "Modifier"
3. Changer une information
4. Enregistrer

### Scénario 5: Suppression
1. Ouvrir un formulaire
2. Cliquer "Supprimer"
3. Confirmer

### Scénario 6: Sécurité - Accès Interdit
1. Créer formulaire avec utilisateur A
2. Se connecter avec utilisateur B
3. Tenter d'accéder au formulaire de A

### Scénario 7: Vérification Document Word
1. Télécharger le .docx
2. Ouvrir dans Word
3. Vérifier les données

## Résultats

- Tests automatisés: **5/5 passent**
- Tests manuels: **7 documentés**
