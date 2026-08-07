# SyndicFlow

Plateforme web de gestion de syndic (copropriété), enrichie par un agent IA qui analyse les réclamations des résidents.

Projet fil rouge (académique) — **Laravel 13 API + React SPA + MySQL**.

## Fonctionnalités

- **Authentification & rôles** : inscription libre (rôle résident), connexion, déconnexion via Sanctum. Trois rôles : `admin`, `syndic`, `resident`.
- **Gestion du patrimoine** : hiérarchie `Résidence → Immeuble → Appartement`, avec affectation/désaffectation d'un résident à un appartement.
- **Gestion des utilisateurs** : un administrateur crée des comptes pour n'importe quel rôle et modifie le rôle d'un compte existant ; un syndic récupère la liste des utilisateurs pour affecter un appartement.
- **Charges** : création, échéance, statut (`pending` / `paid` / `overdue`), déclaration manuelle du paiement.
- **Reçus** : téléversement d'un reçu scanné (JPG/PNG) sur une charge payée, consultation et téléchargement protégés.
- **Réclamations** : un résident dépose une réclamation pour l'un de ses appartements ; un syndic suit et traite les réclamations de ses résidences.
- **Analyse IA (audits)** : un agent IA (via Laravel AI / Groq) analyse une réclamation avec un snapshot figé des charges de l'appartement, produit un résultat structuré (`resume`, `decision`, `statut`) et l'historise dans des audits traçables.

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | Laravel 13, API REST versionnée `/api/v1`, Sanctum, Policies, Form Requests, API Resources, Enums, Jobs asynchrones |
| Frontend SPA | React 19 + Vite, Tailwind CSS 4, React Router, Axios |
| Intelligence artificielle | Laravel AI (fournisseur Groq, modèle configurable) |
| Base de données | MySQL (production) — SQLite par défaut dans `.env.example` |
| Qualité | Pest (tests), Laravel Pint (formatage), Laravel AI/halo Teams non liés |

## Prérequis

- PHP **8.3**+
- Composer 2
- Node.js (pour le build frontend)
- MySQL (ou SQLite en local)

## Installation

```bash
# 1. Cloner et installer les dépendances
composer install
npm install

# 2. Configuration
cp .env.example .env
php artisan key:generate

# 3. Bases de données
php artisan migrate --seed

# 4. Frontend
npm run build          # production
# ou pendant le dev : npm run dev

# 5. Lancer le serveur
php artisan serve
```

Le démarrage simultané (serveur + queue + Vite) est fourni par :

```bash
composer run dev
```

### Configuration de l'IA (optionnelle pour l'analyse des réclamations)

L'agent IA est piloté par les variables d'environnement suivantes (voir `.env`) :

```
GROQ_API_KEY=clé_du_provider
GROQ_MODEL=modèle_groq
AI_PROVIDER=groq
```

La clé API ne doit jamais être commitée (`.env` est ignoré par `.gitignore`). S'il n'y a pas de fournisseur configuration, les analyses renverront une erreur, ce qui ne bloque pas le traitement des réclamations.

## Documentation API

La documentation de l'API est générée avec [Scribe](https://scribe.knuckles.wtf) et accessible sur :

```
http://localhost:8000/docs
```

Régénérer la doc (à chaque modification des contrôleurs) :

```bash
composer docs
# équivalent : php artisan scribe:generate
```

La documentation couvre : authentification, utilisateurs, résidences, immeubles, appartements (+ affectation), charges (+ paiement), reçus (upload/téléchargement), réclamations et audits IA — avec exemple de réponses, jetons d'authentification et tester intégré ("Try It Out").

## Tests et qualité

```bash
# Toute la suite de tests (Pest)
php artisan test --compact

# Ou uniquement un fichier
php artisan test --compact --filter=ResidenceTest

# Formatage du code (Laravel Pint)
composer pint        # équivalent : vendor/bin/pint --dirty --format agent
vendor/bin/pint
```

## Arborescence

```
app/
  Agents/          Agent IA (analyses des réclamations)
  Models/          Élites Eloquent (modèles)
  Http/Controllers/Api/V1/   Contrôleurs de l'API
  Http/Requests/Api/V1/      Form Requests (validation + autorisation)
  Http/Resources/  API Resources (formats de réponses)
  Policies/        Policies d'autorisation
  Enums/           Rôles et statuts
  Services/        Logique métier (détachée des contrôleurs)
routes/
  api.php          Routes de l'API (préfixe /api/v1)
  web.php          Catch-all de la SPA React
resources/js/      Application React
docs/              Documentation du projet (contexte, suivi, diagrammes)
tests/             Suites Feature et Unit (Pest)
```

## Documentation du projet

- [Contexte projet complet](docs/SyndicFlow_Contexte_Projet_Complet.md) : cahier des charges, modèle de données, règles d'autorisation.
- [Suivi de réalisation](docs/Suivi_Projet_SyndicFlow.md) : étape actuelle et état du livrable.
- [User stories](projectcontext.md) : récits d'utilisation par rôle.

## Licence

Projet à vocation académique. Licence MIT.