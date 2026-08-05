# SyndicFlow — Suivi de réalisation

Référence : [SyndicFlow_Contexte_Projet_Complet.md](./SyndicFlow_Contexte_Projet_Complet.md) — fait foi sur tout point.

## Conventions

- `[ ]` à faire · `[~]` en cours · `[x]` terminé.
- **Définition de terminé** (doc §16) : migration + modèle + validation + autorisation + contrôleur/service + ressource API + tests correspondants, cohérents et au vert ensemble.
- Chaque étape se termine par : `vendor/bin/pint --format agent` sur les fichiers modifiés, puis `php artisan test --compact` (suite verte).
- Organisation backend (doc §14.1) : Controllers légers, Form Requests (autorisation + validation), Policies, Services/Actions, API Resources, Enums, Jobs asynchrones.
- Format des réponses API (doc §14.3) : `success`, `message`, `data`. Codes : 401, 403, 404, 409, 422.
- Frontend : React SPA (phase 7 du doc) — construit **après** le backend complet.

---

## Étape 0 — Rattrapage & conformité (base existante)

Objectif : rendre la base actuelle conforme au doc (§7.1, §10, §14) avant de poursuivre.
**Statut : terminée (36 tests au vert, pint passé).**

### Migrations
- [x] `database/migrations/0001_01_01_000000_create_users_table.php` : ajouter `$table->softDeletes()` (doc §10 — SoftDeletes sur `users`)
- [x] `database/migrations/2026_07_28_202736_create_residences_table.php` : ajouter `$table->softDeletes()` (doc §10 — SoftDeletes sur `residences`)
- [x] `database/migrations/2026_07_27_145139_add_role_to_users_table.php` : corriger la typo `dropColomn` → `dropColumn`

### Enums (doc §6)
- [x] `app/Enums/ReclamationPriorite.php` : `Low`, `Medium`, `High`, `Urgent` (valeurs `low`/`medium`/`high`/`urgent`)
- [x] `app/Enums/ReclamationStatut.php` : `Submitted`, `UnderReview`, `Accepted`, `Rejected`, `Resolved`, `Closed` (valeurs `submitted`/`under_review`/`accepted`/`rejected`/`resolved`/`closed`)
- [x] `app/Enums/ChargeStatut.php` : `Pending`, `Paid`, `Overdue` (valeurs `pending`/`paid`/`overdue`)
- [x] `app/Enums/AuditStatut.php` : `Pending`, `Processing`, `Completed`, `Failed`
- [x] `app/Enums/AuditDecision.php` : `Accepted`, `Rejected`, `Review` (valeurs `accepted`/`rejected`/`review`)
- Casse des clés : PascalCase (convention Laravel du projet, sémantique identique au doc).

### Authorization — ResidencePolicy (matrice §7.1)
- [x] `viewAny` : `true` pour les 3 rôles (le filtrage réel se fait dans `index()`)
- [x] `view` / `update` / `delete` : admin toujours ; syndic si propriétaire (`syndic_id === user.id`) ; résident jamais (jusqu'à l'étape 2 où le chemin par affectation sera ajouté)
- [x] `create` : admin **ou** syndic ; résident jamais
- [x] `restore` / `forceDelete` : `false`

### Form Requests (doc §14.2)
- [x] Déplacer `app/Http/Requests/StoreResidenceRequest.php` → `app/Http/Requests/Api/V1/StoreResidenceRequest.php` (namespace `App\Http\Requests\Api\V1`, cohérent avec `Api\V1\Auth\`)
- [x] `authorize()` : admin ou syndic (via `$this->user()?->can('create', Residence::class)`)
- [x] Règles : `name` (required, string, max:255), `address` (required), `city` (required), `postal_code` (nullable, max:10), `description` (nullable)
- [x] Règle `syndic_id` **uniquement si admin** : `required`, `exists:users,id` + règle custom `role = syndic` (closure `Rule` dans `rules()` ou `after()`)
- [x] Créer `app/Http/Requests/Api/V1/UpdateResidenceRequest.php` : mêmes règles **sans** `syndic_id`
- [x] Supprimer l'ancien `app/Http/Requests/StoreResidenceRequest.php`

### ResidenceController (F-03)
- [x] `index()` : filtrage par rôle — admin = toutes les résidences ; syndic = les siennes (`syndic_id` = lui) ; résident = résidences déduites via ses appartements (vide pour l'instant, complété étape 2) ; pagination
- [x] `store()` : `syndic_id` = le sien si syndic, sinon le `syndic_id` validé (admin)
- [x] `show(Residence $residence)` : route-model binding + `authorize('view')`
- [x] `update(UpdateResidenceRequest, Residence $residence)` : `authorize('update')` + `$residence->update($request->validated())`
- [x] `destroy(Residence $residence)` : `authorize('delete')` + suppression douce (SoftDeletes)
- [x] `routes/api.php` : routes `GET/PUT/DELETE /residences/{residence}` (binding `{residence}`), toutes sous `auth:sanctum`
- [x] `Controller.php` de base : ajout du trait `AuthorizesRequests`
- [x] `ResidenceResource` : ajout de `syndic_id` dans la ressource

### Nettoyage
- [x] Supprimer `app/Http/Middleware/EnsureUserHasRole.php` (doublon mort, jamais enregistré, encodage cassé)
- [x] `app/Http/Middleware/RoleMiddleware.php` : corriger l'encodage du message d'erreur (mojibake)

### Fixtures
- [x] `database/factories/UserFactory.php` : `role` par défaut = `UserRole::Resident` + états `asSyndic()` et `asAdmin()` (`role` => `UserRole::Syndic` / `UserRole::Admin`)

### Tests (matrice d'autorisation Résidences)
- [x] `tests/Feature/ResidenceTest.php` : refondre en tests endpoints :
  - invité (sans token) → 401 sur index/store/show/update/destroy
  - résident → 403 sur store/show/update/destroy ; 200 sur index (liste vide)
  - syndic : index = ses résidences uniquement (celle d'un autre syndic absente) ; store crée pour lui-même ; show/update/destroy sur la sienne = 200 ; sur celle d'un autre syndic = 403
  - admin : index = toutes ; store avec `syndic_id` valide crée pour le syndic ciblé ; store avec `syndic_id` d'un utilisateur non-syndic → 422 ; store sans `syndic_id` → 422 ; show/update/destroy sur n'importe quelle résidence = 200
  - destroy : la résidence n'apparaît plus dans `index()` (soft delete)
- [x] Conserver les tests modèles existants (relation `syndic()`, plusieurs résidences)
- [x] `tests/Feature/Middleware/RoleMiddlewareTest.php` : message d'erreur mis à jour (encodage corrigé)

### Verrouillage
- [x] `vendor/bin/pint --format agent` sur les fichiers modifiés
- [x] `php artisan test --compact` → suite verte (36 tests, 85 assertions)

---

## Étape 1 — Utilisateurs & rôles (F-02)

Objectif : dashboard admin API — seul canal pour créer/modifier des comptes de n'importe quel rôle (doc §9).
**Statut : terminée (52 tests au vert, pint passé).**

### Backend
- [x] `app/Http/Resources/UserResource.php` : `id`, `name`, `email`, `role`, `created_at`, `updated_at` (convention existante : ressources à la racine `App\Http\Resources`)
- [x] `app/Http/Requests/Api/V1/StoreUserRequest.php` : `name`, `email` (unique), `password` (min:8), `role` (`Rule::enum(UserRole::class)`) — autorisé admin uniquement (`authorize()`)
- [x] `app/Http/Requests/Api/V1/UpdateUserRequest.php` : `name`, `email` (unique en ignorant l'utilisateur courant), `role` modifiables — autorisé admin uniquement
- [x] `app/Http/Controllers/Api/V1/UserController.php` : `index` (liste paginée + filtre `?role=`), `store`, `update` (dont changement de rôle)
- [x] `routes/api.php` : `GET/POST /users` + `PUT/PATCH /users/{user}` sous `middleware('auth:sanctum')` + `role:admin`
- [x] Vérifier : une réclamation de rôle est refusée pour un non-admin (403), invité (401)
- [x] Pas de suppression d'utilisateur (hors périmètre doc)

### Tests
- [x] `tests/Feature/UserManagementTest.php` : 401 invité ; 403 syndic/résident ; admin crée admin/syndic/résident ; changement de rôle ; email dupliqué → 422 ; filtre par rôle ; rôle invalide → 422

---

## Étape 2 — Immeubles, appartements & affectation (F-04, F-05)

**Statut : terminée (77 tests au vert, pint passé).**

### Migrations
- [x] `immeubles` : `residence_id` FK (cascadeOnDelete), `name`, `address` nullable, `nombre_etages` nullable, timestamps, SoftDeletes (doc §10)
- [x] `appartements` : `immeuble_id` FK, `resident_id` FK nullable → users (nullOnDelete), `numero`, `etage`, `superficie` DECIMAL(8,2), timestamps, SoftDeletes — **pas de colonne `statut`** (accessor calculé, doc §6)

### Modèles & relations
- [x] `Immeuble` : `belongsTo(Residence)`, `hasMany(Appartement)` (+ `ResidenceFactory` / `ImmeubleFactory`)
- [x] `Appartement` : `belongsTo(Immeuble)`, `belongsTo(User, 'resident_id')`, accessor `statut` (`vacant` si `resident_id === null`, sinon `occupied`) + `$appends` (+ `AppartementFactory` avec état `forResident()`)
- [x] `Residence` : ajouter `hasMany(Immeuble)`
- [x] `User` : ajouter `appartements()` hasMany (résident)
- [x] Relations `hasMany(Charge)` / `hasMany(Reclamation)` sur Appartement : reportées aux étapes 3 et 4 (modèles inexistants pour l'instant)

### Authorization (doc §7.2)
- [x] `ImmeublePolicy` : `viewAny` → 3 rôles ; `view` → admin/syndic propriétaire/résident avec appartement dans l'immeuble ; `create` (avec `Residence` parente) → admin ou syndic propriétaire ; update/delete → admin ou syndic propriétaire
- [x] `AppartementPolicy` : `viewAny` → 3 rôles ; `view` → admin/syndic propriétaire/résident affecté ; `create` (avec `Immeuble` parent) → admin ou syndic propriétaire ; `assign` → admin ou syndic propriétaire ; update/delete → admin ou syndic propriétaire

### Contrôleurs / Resources / Form Requests
- [x] `ImmeubleController` : index/store/show/update/destroy, routes imbriquées `residences/{residence}/immeubles` (apiResource imbriqué)
- [x] `AppartementController` : index/store/show/update/destroy, routes imbriquées `immeubles/{immeuble}/appartements`
- [x] `ImmeubleResource`, `AppartementResource` (statut inclus)
- [x] `StoreImmeubleRequest`, `UpdateImmeubleRequest`, `StoreAppartementRequest`, `UpdateAppartementRequest` (authorize via policy avec la ressource parente)

### Affectation / désaffectation (F-05)
- [x] `AssignResidentRequest` : `resident_id` → `exists:users,id` + règle custom `role = resident` (doc §14.2) ; authorize → `can('assign', appartement)`
- [x] Route `PUT /appartements/{appartement}/assign` : affecte dans une transaction (remplacement de l'ancienne affectation)
- [x] Route `DELETE /appartements/{appartement}/assign` : désaffectation (`resident_id = null`)
- [x] Vérifier le statut `vacant`/`occupied` reflété dans la ressource
- [x] Compléter `ResidenceController@index` pour le résident : chemin USER → APPARTEMENT → IMMEUBLE → RESIDENCE (§7.1) ; `ResidencePolicy@view` : résident avec affectation indirecte

### Tests
- [x] CRUD immeubles/appartements par rôle (syndic propriétaire, autre syndic 403, résident, invité 401) — `ImmeubleTest.php`, `AppartementTest.php`
- [x] Affectation : résident valide OK ; non-résident → 422 ; utilisateur inexistant → 422 ; remplacement d'affectation ; désaffectation — `AssignationTest.php`
- [x] Résident : voit uniquement ses appartements (index filtré), sa résidence accessible via l'appartement (chemin complet USER → APPARTEMENT → IMMEUBLE → RESIDENCE)
- [x] `statut` accessor correct (vacant/occupied)

---

## Étape 3 — Charges & reçus (F-06, F-07)

**Statut : terminée (92 tests au vert, pint passé).**

### Migrations (doc §10, §11)
- [x] `charges` : `appartement_id` FK, `libelle`, `description` nullable, `montant` DECIMAL(10,2), `date_echeance`, `statut` string (défaut `pending`, cast enum `ChargeStatut`), `periode` nullable, `date_paiement` nullable, timestamps, SoftDeletes
- [x] `recus` : `charge_id` FK **unique** (un reçu max par charge), `fichier`, `nom_original`, `type_mime`, `taille`, `date_paiement`, `montant_paye` DECIMAL(10,2), timestamps, SoftDeletes

### Modèles
- [x] `Charge` : `belongsTo(Appartement)`, `hasOne(Recu)` (+ `ChargeFactory` avec états `paid()`/`overdue()`)
- [x] `Recu` : `belongsTo(Charge)` (+ `RecuFactory`)
- [x] `Appartement` : ajouter `hasMany(Charge)`

### Authorization (doc §7.2)
- [x] `ChargePolicy` : syndic propriétaire de l'appartement = CRUD complet ; résident affecté = `view` seul
- [x] `RecuPolicy` : syndic = upload + consultation ; résident affecté = consultation seule

### Contrôleurs
- [x] `ChargeController` : index/store/show/update/destroy + `markAsPaid` (`pending → paid`, manuel par le syndic, doc §12 ; charge déjà payée → 409) ; `statut` non modifiable par le CRUD
- [x] `RecuController` : `store` (upload JPG/PNG **uniquement**, `mimes:jpg,jpeg,png`, doc §14.2 + §18) sur une charge `statut = paid` (règle custom en plus de la contrainte unique DB) ; `show` ; `download` (Storage::response, accès autorisé uniquement) ; `destroy` (soft) — un reçu soft-deleted peut être remplacé (forceDelete du précédent dans une transaction)
- [x] Stockage : disque `private` ajouté dans `config/filesystems.php` (racine `storage/app/private`, visibilité private), chemin `recus/` par charge
- [x] `ChargeResource` (avec `recu` quand chargé), `RecuResource` (avec `download_url` via route nommée)

### Tests
- [x] CRUD charges par rôle (syndic, résident view seul, autre syndic 403, invité 401)
- [x] Reçu : refusé si charge non payée (422) ; refusé si second reçu actif (422) ; refusé si mime invalide (422) ; accepté si charge payée
- [x] Téléchargement du fichier : 200 pour syndic/résident affecté (header Content-Disposition), 403 sinon
- [x] Montants : `numeric`, `min:0`, stockage DECIMAL (montant `120.50` renvoyé en string)
- [x] Cycle : marquage payé → `statut=paid` + `date_paiement` renseignée ; re-marquage → 409

---

## Étape 4 — Réclamations (F-08, F-09)

**Statut : terminée (104 tests au vert, pint passé).**

### Migration (doc §10)
- [x] `reclamations` : `resident_id` FK, `appartement_id` FK, `titre`, `description`, `statut` string (défaut `submitted`, cast enum `ReclamationStatut`), `priorite` string (défaut `medium`, cast enum `ReclamationPriorite`), timestamps, SoftDeletes

### Modèle
- [x] `Reclamation` : `belongsTo(User, 'resident_id')`, `belongsTo(Appartement)`, `hasMany(Audit)` (+ `ReclamationFactory` avec état `withStatut()`)
- [x] `Appartement` : ajouter `hasMany(Reclamation)` ; `User` : ajouter `reclamations()`

### Authorization (doc §7.2)
- [x] `ReclamationPolicy` :
  - `create` : résident, uniquement pour un appartement qui lui est affecté (vérifié en Policy **et** en Form Request : `Appartement::resident_id === user`)
  - `view` : résident sur les siennes ; syndic sur celles des appartements de ses résidences ; admin tout
  - `update` (traitement) : syndic (appartements de ses résidences) ou admin
  - `delete` : admin uniquement

### Contrôleur / Form Requests / Resource
- [x] `StoreReclamationRequest` : `appartement_id` doit appartenir à l'utilisateur authentifié (règle custom dans `authorize()`, doc §14.2) ; `priorite` optionnelle (`Rule::enum`) ; `titre`, `description` requis
- [x] `UpdateReclamationRequest` (traitement par syndic) : changement de `statut` (`Rule::enum(ReclamationStatut)`)
- [x] `ReclamationController` : `index` (filtré par rôle), `store` (statut initial `submitted`), `show`, `update`, `destroy` (admin)
- [x] `ReclamationResource`

### Tests
- [x] Résident : ne peut pas créer pour l'appartement d'un autre résident (403), ni pour un appartement vacant (403) ; voit uniquement ses réclamations
- [x] Syndic : voit/traite celles de ses résidences (loop statut `submitted → under_review`), pas celles d'un autre syndic (403)
- [x] Admin : tout (index, traitement, suppression)
- [x] Invité : 401
- [x] Statut / priorité invalide → 422

---

## Étape 5 — IA, audits & conversations (F-10 → F-12)

### Dépendance & configuration
- [ ] Installer `laravel/ai` (`composer require laravel/ai`)
- [ ] Fournisseur **Groq** : configurer le client OpenAI-compatible vers `https://api.groq.com/openai/v1` (ou provider dédié si disponible) ; clé API dans `.env` (`GROQ_API_KEY` ou équivalent) — jamais commitée
- [ ] Publier/configurer les migrations imposées par `laravel/ai` (conversations + messages IA, doc §10)

### Migrations (doc §10)
- [ ] `audits` : `reclamation_id` FK **non unique** (réanalyse autorisée, doc §5), `charges_snapshot` JSON (figé à l'analyse, jamais recalculé), `resultat` JSON, `decision` enum `AuditDecision`, `statut` enum `AuditStatut` (défaut `pending`), `modele_ia` nullable, `traite_at` nullable, timestamps, SoftDeletes
- [ ] `conversations` : `audit_id` FK **unique** (relation 1–1 audit–conversation, doc §5 + §18), champs imposés par laravel/ai, timestamps, SoftDeletes

### Modèles
- [ ] `Audit` : `belongsTo(Reclamation)`, `hasOne(Conversation)`
- [ ] `Conversation` : `belongsTo(Audit)`
- [ ] `Reclamation` : ajouter `hasMany(Audit)`

### Authorization (doc §7.2)
- [ ] `AuditPolicy` :
  - `create`/`trigger` : syndic (réclamations de ses résidences) ou admin ; **jamais résident**
  - `view` : syndic propriétaire et admin uniquement ; le résident ne voit jamais un AUDIT ni `decision`/`resultat` (doc §18)

### Orchestration IA
- [ ] `app/Services/AnalyseReclamationService.php` (ou Action) : récupère l'état des charges de l'appartement (toutes les charges, doc §9/F-09), fige le `charges_snapshot` JSON, appelle l'agent IA, enregistre `resultat`, `decision`, `statut`, `modele_ia`, `traite_at`
- [ ] Job asynchrone `AnalyserReclamationJob` (queue, doc §14.1) : traitement rejouable sans doublons incohérents
- [ ] Gestion de panne IA : statut `failed` explicite, réclamation préservée, relance contrôlée possible (doc §16)
- [ ] `AuditController` : `POST /reclamations/{reclamation}/analyser` (déclenchement), `GET /audits` + `GET /audits/{audit}` (syndic/admin), `GET /reclamations/{reclamation}/audits`
- [ ] `AuditResource`

### Tests (doc §16)
- [ ] Snapshot figé : modification d'une charge après analyse → `charges_snapshot` inchangé
- [ ] Multi-analyses : plusieurs audits pour une même réclamation autorisés
- [ ] Panne IA : statut `failed`, réclamation intacte, relance OK
- [ ] Résident : jamais d'accès aux audits (403), suit uniquement `reclamations.statut`
- [ ] Conversation créée et associée 1–1 à l'audit

---

## Étape 6 — Frontend React (phase 7 du doc)

### Scaffold
- [ ] Installer `react`, `react-dom`, `react-router-dom`, `axios` (+ `@vitejs/plugin-react`), configurer `vite.config.js` et `resources/js` (SPA, pas de Blade)
- [ ] Client API (`resources/js/api/client.js`) : axios, base `/api/v1`, interceptor Bearer token (Sanctum, localStorage), gestion 401
- [ ] Contexte/état d'authentification (login, register, me, logout)

### Écrans Auth (F-01)
- [ ] Page register (crée toujours un compte résident — jamais de champ rôle, doc §9)
- [ ] Page login
- [ ] Guard de routes par rôle (admin/syndic/resident)

### Écrans admin
- [ ] Dashboard (statistiques simples résidences/utilisateurs)
- [ ] Gestion des utilisateurs : liste, création (n'importe quel rôle), changement de rôle
- [ ] Résidences : liste complète, création pour un syndic choisi, modification, suppression

### Écrans syndic
- [ ] Résidences : CRUD sur les siennes
- [ ] Immeubles / appartements : CRUD imbriqué, affectation/désaffectation d'un résident existant
- [ ] Charges : CRUD, marquage payé
- [ ] Reçus : upload (JPG/PNG), consultation, téléchargement
- [ ] Réclamations : liste, détail, traitement (changement de statut), déclenchement analyse IA
- [ ] Audits : consultation des résultats IA (syndic seulement)

### Écrans résident
- [ ] Mes appartements : liste + détail (immeuble, résidence)
- [ ] Charges & reçus de mes appartements (consultation seule)
- [ ] Réclamations : création pour un de mes appartements, suivi du `statut` **seulement** (pas d'audit visible, doc §18)

### Qualité UX
- [ ] États chargement / erreur / vide sur chaque écran (doc §15 UX)
- [ ] Confirmation des actions destructrices
- [ ] Responsive
- [ ] Pas d'écrans hors parcours §8 (hors périmètre doc)

### Intégration
- [ ] `npm run build` sans erreur ; parcours complet testé main dans la main avec l'API

---

## Étape 7 — Stabilisation & documentation (phase 8 du doc)

### Tests & qualité
- [ ] Tests E2E / smoke des parcours §13 (création résidence, affectation, traitement réclamation)
- [ ] Revue sécurité : validations serveur, policies sur toutes les routes, fichiers reçus non publics, mots de passe hachés
- [ ] Performance : index sur toutes les FK/champs filtrés, eager loading (anti-N+1), pagination des listes, transactions multi-écritures
- [ ] `vendor/bin/pint --format agent` sur tout le code (zéro diff restant)
- [ ] `php artisan test --compact` : suite complète au vert

### Documentation
- [ ] README : installation, configuration (y compris clé Groq), arborescence, conventions API
- [ ] Vérifier la cohérence de ce fichier de suivi (toutes cases `[x]`)
- [ ] Mettre à jour `docs/SyndicFlow_Contexte_Projet_Complet.md` si besoin (décisions validées pendant la réalisation)

### Déploiement
- [ ] Préparer le déploiement Laravel Cloud (doc : Laravel Cloud = voie recommandée)
- [ ] Checklist production : APP_KEY, cache, migrations, stockage des reçus, queue worker pour les jobs IA
