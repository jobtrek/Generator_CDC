# 📋 Générateur CDC - Installation Guide

## 📖 Description

**Générateur CDC** est une application web Laravel permettant de créer et gérer des Cahiers des Charges (CDC) pour les projets de qualification professionnelle. L'application génère automatiquement des documents Word formatés à partir de formulaires personnalisables.

### ✨ Fonctionnalités principales

- 📝 **Création de formulaires dynamiques** avec support Markdown
- 📄 **Génération automatique de CDC** au format Word (.docx)
- 👥 **Gestion des utilisateurs** avec système de rôles et permissions (Spatie)
- 🔐 **4 niveaux d'accès** : Super Admin, Admin, Formateur, Utilisateur
- 📊 **Dashboard** avec vue d'ensemble des CDC et formulaires
- 🎨 **Interface moderne** avec Tailwind CSS et Alpine.js

---

## 🛠️ Technologies utilisées

- **Backend** : Laravel 12.x (PHP 8.2+)
- **Frontend** : Tailwind CSS 3.x, Alpine.js 3.x, Vite
- **Base de données** : PostgreSQL
- **Génération de documents** : PHPWord
- **Permissions** : Spatie Laravel Permission
- **Markdown** : League CommonMark

---

## ⚙️ Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x et npm
- **PostgreSQL** >= 14.x
- **Git**

### Vérifier les versions installées

```bash
php -v
composer -V
node -v
npm -v
psql --version
```

---

## 📥 Installation

### 1️⃣ Cloner le projet

```bash
git clone <URL_DU_REPO>
cd generateur-cdc
```

### 2️⃣ Installer les dépendances PHP

```bash
composer install
```

### 3️⃣ Installer les dépendances Node.js

```bash
npm install
```

### 4️⃣ Configuration de l'environnement

#### Créer le fichier `.env`

```bash
cp .env.example .env
```

#### Générer la clé d'application

```bash
php artisan key:generate
```

#### Configurer la base de données

Ouvrez le fichier `.env` et modifiez les paramètres suivants :

```env
APP_NAME="Générateur CDC"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=generateur_cdc
DB_USERNAME=votre_utilisateur_postgres
DB_PASSWORD=votre_mot_de_passe_postgres

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

### 5️⃣ Créer la base de données PostgreSQL

Connectez-vous à PostgreSQL :

```bash
psql -U postgres
```

Créez la base de données :

```sql
CREATE DATABASE generateur_cdc;
\q
```

### 6️⃣ Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

### 7️⃣ Lancer les migrations

```bash
php artisan migrate
```

### 8️⃣ Lancer les seeders (utilisateurs et permissions)

```bash
php artisan db:seed
```

Cette commande va créer :
- Les rôles : `super-admin`, `admin`, `formateur`, `user`
- Les permissions pour CDC, formulaires, utilisateurs, etc.
- 4 utilisateurs de test (voir tableau ci-dessous)

---

## 👥 Comptes de test créés

| Email | Rôle | Mot de passe | Permissions |
|-------|------|--------------|-------------|
| `superadmin@cdcs.com` | Super Admin | `password123` | Accès total |
| `admin@cdcs.com` | Admin | `password123` | Gestion complète sauf certains paramètres système |
| `formateur@cdcs.com` | Formateur | `password123` | Créer/Éditer des formulaires et CDC |
| `user@cdcs.com` | User | `password123` | Consulter et exporter des CDC |

---

## 🚀 Lancement de l'application

### Méthode 1 : Commande de développement complète (recommandée)

```bash
composer dev
```

Cette commande lance automatiquement :
- Serveur Laravel (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Logs en temps réel (`php artisan pail`)
- Vite dev server (`npm run dev`)

### Méthode 2 : Lancement manuel

#### Terminal 1 : Serveur Laravel
```bash
php artisan serve
```

#### Terminal 2 : Vite (assets)
```bash
npm run dev
```

#### Terminal 3 : Queue worker (optionnel)
```bash
php artisan queue:work
```

---

## 🌐 Accéder à l'application

Une fois les serveurs lancés, ouvrez votre navigateur :

```
http://localhost:8000
```

Connectez-vous avec l'un des comptes de test ci-dessus.

---

## 📁 Structure du projet

```
generateur-cdc/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CdcController.php          # Gestion des CDC
│   │   │   ├── FormController.php         # Gestion des formulaires
│   │   │   └── Admin/
│   │   │       └── UserController.php     # Gestion des utilisateurs
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Cdc.php                        # Modèle CDC
│   │   ├── Form.php                       # Modèle Formulaire
│   │   └── User.php                       # Modèle Utilisateur
│   ├── Services/
│   │   └── CdcPhpWordGenerator.php        # Générateur Word
│   └── Helpers/
│       └── RoleHelper.php                 # Helper pour les rôles
├── database/
│   ├── migrations/                        # Migrations de la BDD
│   └── seeders/
│       └── RolePermissionSeeder.php       # Seeder rôles & permissions
├── resources/
│   ├── views/
│   │   ├── forms/                         # Vues formulaires
│   │   ├── admin/                         # Vues admin
│   │   └── dashboard.blade.php            # Dashboard principal
│   └── css/
│       └── app.css                        # Styles Tailwind
├── routes/
│   ├── web.php                            # Routes principales
│   └── auth.php                           # Routes authentification
├── storage/
│   └── app/
│       └── public/
│           └── cdcs/                      # Documents CDC générés
├── .env.example                           # Template configuration
├── composer.json                          # Dépendances PHP
├── package.json                           # Dépendances Node.js
└── README.md                              # Ce fichier
```

---

## 🔑 Système de permissions

### Rôles disponibles

| Rôle | Description | Permissions clés |
|------|-------------|------------------|
| **Super Admin** | Accès complet | Toutes les permissions |
| **Admin** | Administration | Gestion utilisateurs, CDC, formulaires, logs |
| **Formateur** | Gestionnaire de contenu | Créer/éditer formulaires et CDC |
| **User** | Utilisateur standard | Consulter et exporter CDC |

### Permissions principales

#### Formulaires
- `form.view` - Voir les formulaires
- `form.create` - Créer un formulaire
- `form.edit` - Modifier un formulaire
- `form.delete` - Supprimer un formulaire
- `form.publish` - Publier un formulaire

#### Utilisateurs (Admin)
- `user.view` - Voir les utilisateurs
- `user.create` - Créer un utilisateur
- `user.edit` - Modifier un utilisateur
- `user.delete` - Supprimer un utilisateur
- `user.roles` - Gérer les rôles

#### Système
- `dashboard.view` - Accéder au dashboard
- `settings.view` / `settings.edit` - Paramètres
- `logs.view` - Voir les logs
- `backup.create` / `backup.download` - Sauvegardes

---

## 🧪 Tests

### Lancer tous les tests

```bash
composer test
```

ou

```bash
php artisan test
```

---

## 📦 Build pour production

### 1️⃣ Compiler les assets

```bash
npm run build
```

### 2️⃣ Optimiser l'application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 3️⃣ Configurer `.env` pour production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
```

---

## 🐛 Dépannage

### Problème : Erreur "Class 'Role' not found"

```bash
php artisan cache:clear
composer dump-autoload
php artisan config:clear
```

### Problème : Les assets ne se chargent pas

```bash
npm run build
php artisan storage:link
```

### Problème : Erreur de connexion à PostgreSQL

Vérifiez que PostgreSQL est démarré :

```bash
# Linux/Mac
sudo service postgresql status

# Windows
pg_ctl status
```

### Problème : Les migrations échouent

Supprimez et recréez la base de données :

```bash
php artisan migrate:fresh --seed
```

⚠️ **Attention** : Cette commande supprime TOUTES les données !

---

## 📝 Utilisation

### 1. Créer un formulaire

1. Connectez-vous en tant que **Formateur** ou **Admin**
2. Allez dans **Formulaires** → **Nouveau formulaire**
3. Remplissez les champs (support Markdown)
4. Enregistrez

### 2. Générer un CDC

1. Allez dans **Nouveau CDC**
2. Sélectionnez un formulaire comme base
3. Remplissez les informations du candidat, experts, etc.
4. Cliquez sur **Générer le CDC**
5. Le document Word se télécharge automatiquement

### 3. Gérer les utilisateurs (Admin)

1. Allez dans **Admin** → **Utilisateurs**
2. Créez ou modifiez des utilisateurs
3. Assignez des rôles
4. Les permissions sont automatiquement appliquées

---

## 🔧 Configuration avancée

### Changer le stockage des fichiers

Par défaut, les CDC sont stockés dans `storage/app/public/cdcs/`.

Pour changer :

1. Modifiez `.env` :
```env
FILESYSTEM_DISK=s3  # Pour AWS S3
```

2. Configurez les credentials AWS dans `.env`

### Personnaliser les templates Word

Modifiez le service `CdcPhpWordGenerator.php` :

```php
app/Services/CdcPhpWordGenerator.php
```

### Ajouter des permissions personnalisées

Modifiez le seeder :

```php
database/seeders/RolePermissionSeeder.php
```

Puis relancez :

```bash
php artisan migrate:fresh --seed
```

---
