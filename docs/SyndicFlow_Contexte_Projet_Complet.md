# SyndicFlow --- Contexte projet complet

Plateforme web de gestion de syndic enrichie par un agent IA.
Projet fil rouge (académique) --- Laravel 13 API + React SPA + MySQL.

Ce document consolide le cahier des charges initial et l'ensemble des
arbitrages validés jusqu'au 4 août 2026. Il fait foi sur tout point où
il diverge d'une version antérieure.

---

## 1. Objet et contexte

La gestion manuelle d'une copropriété disperse les informations entre
documents, échanges et fichiers. SyndicFlow centralise la gestion des
résidences, immeubles, appartements, résidents, charges, reçus et
réclamations, et assiste le traitement des réclamations par un agent
IA traçable.

Objectifs :

- Structurer le patrimoine immobilier selon la hiérarchie
  Résidence → Immeuble → Appartement.
- Donner à chaque utilisateur uniquement l'accès aux données
  correspondant à son rôle et à son périmètre.
- Suivre les charges par appartement et conserver la preuve scannée
  d'un paiement.
- Permettre au résident de déposer une réclamation concernant l'un de
  ses appartements.
- Faire analyser la réclamation par l'agent IA en tenant compte de
  l'état réel des charges.
- Conserver un historique immuable des informations utilisées et du
  résultat de chaque analyse.

---

## 2. Périmètre

### 2.1 Inclus

- Authentification et gestion de session (Sanctum).
- Gestion des utilisateurs et des rôles admin, syndic, resident.
- Gestion des résidences, immeubles et appartements.
- Affectation d'un appartement vacant à un résident.
- Gestion des charges, paiements et reçus scannés.
- Création, consultation et suivi des réclamations.
- Analyse IA des réclamations à partir de la réclamation et de l'état
  des charges.
- Journal d'audit et conversations Laravel AI.
- API REST versionnée, validations, policies et tests automatisés.

### 2.2 Hors périmètre (version actuelle)

- Comptabilité générale complète et rapprochement bancaire.
- Paiement bancaire en ligne.
- Application mobile native.
- Affectation simultanée de plusieurs résidents au même appartement.
- Historique détaillé des anciennes affectations d'appartements.
- Suppression définitive et restauration des résidences.
- Suppression d'un syndic et comportement de cascade associé.
- Écrans React au-delà des parcours décrits en section 8.
- Notifications (email, in-app).
- Taille maximale des fichiers reçus.

---

## 3. Acteurs et responsabilités

| Acteur | Responsabilités principales |
|---|---|
| Administrateur | Supervise la plateforme, gère l'ensemble des données autorisées, crée une résidence pour un syndic choisi, crée/modifie un compte de n'importe quel rôle. |
| Syndic | Possède et gère une ou plusieurs résidences, leurs immeubles, appartements, charges et dossiers associés ; assigne un résident existant à un appartement ; déclenche l'analyse IA d'une réclamation. |
| Résident | Accède aux appartements qui lui sont affectés, aux résidences correspondantes, à leurs charges et reçus, et dépose des réclamations pour ses appartements. |
| Agent IA | Analyse une réclamation avec l'état des charges applicable, produit une décision ou un résultat et alimente l'audit. |

---

## 4. User stories

### Administrateur
- En tant qu'admin, je peux créer un compte pour n'importe quel rôle
  et modifier le rôle d'un compte existant, afin de gérer les accès
  de la plateforme.
- En tant qu'admin, je peux créer une résidence pour un syndic choisi.
- En tant qu'admin, je peux consulter, modifier ou supprimer toute
  résidence, quel que soit son propriétaire.

### Syndic
- En tant que syndic, je peux créer mes propres résidences, immeubles
  et appartements.
- En tant que syndic, je peux assigner un résident déjà inscrit à un
  appartement vacant de mes résidences.
- En tant que syndic, je peux créer des charges pour un appartement et
  les marquer comme payées.
- En tant que syndic, je peux ajouter un reçu scanné sur une charge
  déjà marquée payée.
- En tant que syndic, je peux consulter les réclamations des
  résidents de mes résidences et déclencher leur analyse par l'agent
  IA.

### Résident
- En tant que visiteur, je peux m'inscrire librement ; mon compte est
  automatiquement créé avec le rôle résident.
- En tant que résident, je peux consulter les appartements qui me
  sont affectés, leurs charges et les reçus associés.
- En tant que résident, je peux déposer une réclamation pour un de
  mes appartements.
- En tant que résident, je peux suivre l'état de ma réclamation
  (`statut`) mais je n'ai pas accès au détail de l'audit IA
  (`decision`, `resultat`).

### Agent IA
- En tant qu'agent IA, je reçois la réclamation et un snapshot figé
  des charges de l'appartement concerné, et je produis un résultat
  structuré et traçable (`resultat`, `decision`, `statut`).

---

## 5. Modèle métier validé

- Une seule entité `USER` représente tous les comptes. Chaque
  utilisateur possède exactement un rôle parmi `ADMIN`, `SYNDIC`,
  `RESIDENT`.
- Un syndic possède zéro à plusieurs résidences ; chaque résidence
  appartient à un seul syndic.
- Une résidence contient un ou plusieurs immeubles ; chaque immeuble
  appartient à une seule résidence.
- Un immeuble contient un ou plusieurs appartements ; chaque
  appartement appartient à un seul immeuble.
- Un appartement peut être vacant ou accueillir un seul résident ; un
  résident peut avoir plusieurs appartements. Relation portée par
  `appartements.resident_id` nullable --- pas de table `ASSIGNMENT`
  (pas d'historique d'affectation requis).
- Chaque charge est rattachée directement à un appartement, jamais
  directement au résident.
- Une charge peut avoir zéro ou un reçu ; un reçu appartient à une
  seule charge et ne peut être associé qu'à une charge payée.
- Une réclamation est créée par un résident et concerne obligatoirement
  l'un des appartements actuellement affectés à ce résident.
- L'agent IA récupère l'état des charges pertinentes de l'appartement,
  puis analyse ces informations avec le contenu de la réclamation. Le
  résultat est enregistré dans `AUDIT`.
- Chaque audit référence une réclamation et conserve `charges_snapshot`
  au format JSON, figé au moment de l'analyse --- les modifications
  ultérieures des charges ne changent jamais la justification
  historique. Pas de table `audit_charge`.
- Une réclamation peut être réanalysée plusieurs fois : l'état des
  charges pouvant évoluer entre deux analyses, plusieurs `AUDIT` liés
  à une même réclamation sont autorisés.
- Chaque audit est documenté par exactement une conversation Laravel
  AI (relation 1--1).

---

## 6. Enums

```php
enum UserRole: string
{
    case ADMIN = 'admin';
    case SYNDIC = 'syndic';
    case RESIDENT = 'resident';
}

enum ReclamationPriorite: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';
}

enum ReclamationStatut: string
{
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
}

enum ChargeStatut: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
}

enum AuditStatut: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}

enum AuditDecision: string
{
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case REVIEW = 'review';
}
```

`appartements.statut` (`vacant` / `occupied`) n'est **pas** une colonne
DB : c'est une valeur calculée, dérivée de `resident_id === null`.

---

## 7. Règles d'autorisation

Les contrôles sont appliqués côté backend, indépendamment de
l'affichage côté React. Une interface masquée ne remplace jamais une
Policy Laravel ou un filtrage de requête.

### 7.1 Matrice officielle --- Résidences

| Action | Admin | Syndic | Résident |
|---|---|---|---|
| Lister | Toutes | Ses propres résidences | Résidence(s) déduite(s) de ses appartements |
| Consulter | Toute résidence | Propriétaire uniquement | Affectation indirecte requise |
| Créer | Oui, pour un syndic choisi | Oui, pour lui-même | Non |
| Modifier | Toute résidence | Propriétaire uniquement | Non |
| Supprimer | Toute résidence | Propriétaire uniquement | Non |

En Laravel, `viewAny()` autorise l'accès à l'endpoint de liste pour
les trois rôles (elle ne peut pas vérifier une résidence précise, elle
ne reçoit que l'utilisateur). Le filtrage réel s'effectue dans
`index()` ; `view()` vérifie la propriété du syndic ou l'affectation
du résident pour une résidence déterminée.

> **Chemin d'accès du résident** : `USER → APPARTEMENT → IMMEUBLE →
> RESIDENCE`. Aucune relation directe `user`--`residence` ne doit être
> inventée pour le résident.

### 7.2 Policies détaillées

**ResidencePolicy**
- `viewAny` : true pour les 3 rôles (filtrage réel dans `index()`).
- `view` / `update` / `delete` : admin toujours ; syndic si
  propriétaire ; résident jamais.
- `create` : admin (avec `syndic_id` fourni, doit référencer un user
  `role=syndic`) ou syndic (pour lui-même) ; résident jamais.

**ImmeublePolicy / AppartementPolicy**
- Accès hérité via la chaîne de propriété : syndic propriétaire de la
  résidence parente (CRUD) ; résident via `appartement.resident_id`
  (consultation seule).

**ChargePolicy**
- Syndic propriétaire de l'appartement : CRUD complet.
- Résident affecté à l'appartement : `view` seul.

**RecuPolicy**
- Syndic : upload et consultation.
- Résident affecté : consultation seule.

**ReclamationPolicy**
- Résident : `create` pour un appartement qui lui est affecté (vérifié
  en Policy **et** en Form Request), `view` sur les siennes.
- Syndic : `view` / `update` (traitement) sur les réclamations des
  appartements de ses résidences.
- Admin : tout.

**AuditPolicy**
- `create` / `trigger` (déclenchement de l'analyse IA) : syndic
  (réclamations de ses résidences) ou admin (toutes). Jamais résident.
- `view` : syndic propriétaire et admin uniquement.
- Le résident ne voit jamais un `AUDIT` ni sa `decision`/`resultat` ;
  il suit uniquement l'évolution de `reclamations.statut`.

---

## 8. Exigences fonctionnelles détaillées

| ID | Module | Exigence |
|---|---|---|
| F-01 | Authentification | Inscription, connexion, consultation du compte courant et déconnexion avec Sanctum. Réponses JSON ; routes privées exigent une session/jeton valide. |
| F-02 | Utilisateurs et rôles | Attribuer et contrôler les rôles ADMIN, SYNDIC, RESIDENT. Rôle non autorisé → 403 ; visiteur non authentifié → 401. |
| F-03 | Résidences | CRUD selon la matrice officielle. L'admin choisit le syndic à la création ; le syndic devient automatiquement propriétaire de sa création. |
| F-04 | Immeubles | Gérer les immeubles uniquement dans une résidence accessible à l'utilisateur connecté. |
| F-05 | Appartements | Gérer les appartements d'un immeuble ; affecter/désaffecter un résident unique via `resident_id` nullable. |
| F-06 | Charges | Créer et suivre les charges d'un appartement (libellé, montant, échéance, statut, informations de suivi). |
| F-07 | Reçus | Téléverser et consulter un document scanné pour une charge payée ; garantir une seule preuve active par charge. |
| F-08 | Réclamations | Permettre à un résident de créer et suivre une réclamation pour l'un de ses appartements. |
| F-09 | Précondition de traitement | Avant toute analyse, récupérer l'état des charges de l'appartement (toutes les charges sont éligibles ; décision d'instruire au cas par cas par le syndic). |
| F-10 | Analyse IA | Produire un résultat structuré et traçable à partir de la réclamation et du snapshot des charges. |
| F-11 | Audits | Conserver la réclamation, `charges_snapshot` JSON, résultat, décision, statut, modèle et horodatage du traitement. |
| F-12 | Conversations | Associer une conversation IA à chaque audit et conserver les messages nécessaires à la traçabilité. |

---

## 9. Comptes et inscription

- **`/register` public** : crée toujours un compte avec
  `role = resident`. Le rôle n'est jamais un champ exposé côté client.
- **Dashboard admin** : seul canal permettant de créer un compte avec
  n'importe quel rôle (admin, syndic, résident) et de modifier le rôle
  d'un compte existant.
- **Dashboard syndic** : aucune création de compte ; le syndic assigne
  uniquement un résident déjà existant à un appartement.
- Le patrimoine (résidence/immeuble/appartement) est créé par le
  syndic pour lui-même, ou par l'admin pour un syndic choisi.

---

## 10. Modèle de données (migrations et modèles)

### users
`id`, `name`, `email` (unique), `password`, `role` (enum `UserRole`),
`email_verified_at`, `timestamps`, `SoftDeletes`.
Relations : `residences()` hasMany (syndic), `appartements()` hasMany
(resident), `reclamations()` hasMany (resident).

### residences
`id`, `syndic_id` FK → `users.id`, `name`, `address`, `city`,
`postal_code`, `description` nullable, `timestamps`, `SoftDeletes`.
`belongsTo(User, 'syndic_id')`, `hasMany(Immeuble)`.

### immeubles
`id`, `residence_id` FK, `name`, `address` nullable, `nombre_etages`
nullable, `timestamps`, `SoftDeletes`.
`belongsTo(Residence)`, `hasMany(Appartement)`.

### appartements
`id`, `immeuble_id` FK, `resident_id` FK nullable → `users.id`,
`numero`, `etage`, `superficie`, `timestamps`, `SoftDeletes`.
Pas de colonne `statut` --- accessor calculé (voir section 6).
`belongsTo(Immeuble)`, `belongsTo(User, 'resident_id')`,
`hasMany(Charge)`, `hasMany(Reclamation)`.

### charges
`id`, `appartement_id` FK, `libelle`, `description` nullable,
`montant` DECIMAL, `date_echeance`, `statut` (enum `ChargeStatut`),
`periode` nullable, `date_paiement` nullable,`timestamps`, `SoftDeletes`.
`belongsTo(Appartement)`, `hasOne(Recu)`.

### recus
`id`, `charge_id` FK **unique**, `fichier` (chemin), `nom_original`,
`type_mime`, `taille`, `date_paiement`, `montant_paye` DECIMAL,
`timestamps`, `SoftDeletes`.
`belongsTo(Charge)`.

### reclamations
`id`, `resident_id` FK, `appartement_id` FK, `titre`, `description`,
`statut` (enum `ReclamationStatut`, défaut `submitted`), `priorite`
(enum `ReclamationPriorite`, défaut `medium`), `timestamps`,
`SoftDeletes`.
`belongsTo(User, 'resident_id')`, `belongsTo(Appartement)`,
`hasMany(Audit)`.

### audits
`id`, `reclamation_id` FK (non unique), `charges_snapshot` JSON,
`resultat` JSON, `decision` (enum `AuditDecision`), `statut` (enum
`AuditStatut`), `modele_ia` nullable, `traite_at` nullable,
`timestamps`, `SoftDeletes`.
`belongsTo(Reclamation)`, `hasOne(Conversation)`.

### conversations
`id`, `audit_id` FK **unique**, champs imposés par `laravel/ai`,
`timestamps`, `SoftDeletes`.
`belongsTo(Audit)`.

### messages IA
Table imposée par `laravel/ai`, liée à `conversations`.

> **Principe de modélisation** : le MCD reste conceptuel (entités,
> associations, cardinalités, sans `resident_id`/`syndic_id` comme
> relations physiques) ; clés étrangères et index appartiennent au
> MLD.

---

## 11. Contraintes d'intégrité

- `users.email` unique ; `users.role` restreint aux 3 valeurs.
- `residences.syndic_id` doit référencer un utilisateur `role=SYNDIC`.
- `appartements.resident_id` nullable ; si renseigné, doit référencer
  un utilisateur `role=RESIDENT`.
- Une réclamation doit référencer le même résident que l'appartement
  au moment de sa création.
- `recus.charge_id` unique (un reçu maximum par charge).
- Montants toujours positifs ou nuls selon leur sens métier, stockés
  en DECIMAL, jamais en float.
- `charges_snapshot` écrit au moment de l'audit, jamais recalculé pour
  modifier un audit historique.
- Les suppressions préservent la traçabilité légale (`SoftDeletes`) ;
  comportement cascade/restriction/nullification défini table par
  table.

---

## 12. Cycle de vie --- charge / reçu

1. Charge créée → `statut = pending`.
2. Le syndic déclare manuellement le paiement → `statut = paid`.
3. Le syndic ajoute le reçu (JPG/PNG uniquement) sur une charge déjà
   `paid`. Un reçu ne peut jamais précéder ce changement de statut.

---

## 13. Parcours fonctionnels

### 13.1 Création d'une résidence
1. L'utilisateur s'authentifie.
2. Le backend vérifie la permission `create`.
3. Si syndic → `syndic_id` prend son propre identifiant.
4. Si admin → il fournit un syndic valide.
5. La requête est validée, la résidence est créée, une ressource JSON
   normalisée est retournée.

### 13.2 Affectation d'un résident
6. Le syndic ou l'admin sélectionne un appartement accessible.
7. Le backend vérifie que l'utilisateur cible a le rôle RESIDENT.
8. `appartements.resident_id` est renseigné ; une nouvelle affectation
   remplace l'ancienne après confirmation métier.
9. Le résident obtient l'accès à l'appartement, à son immeuble et à sa
   résidence.

### 13.3 Traitement d'une réclamation
10. Le résident crée une réclamation pour l'un de ses appartements.
11. Le backend vérifie l'affectation et récupère les charges
    pertinentes (toutes les charges de l'appartement).
12. Le syndic décide, au cas par cas, d'instruire la réclamation.
13. Un snapshot JSON des charges utilisées est figé.
14. L'agent IA analyse la réclamation et le snapshot.
15. Le résultat, la décision et les métadonnées sont enregistrés dans
    `AUDIT`.
16. La conversation et ses messages conservent la trace du traitement.
    Le résident suit uniquement `reclamations.statut`.

---

## 14. Architecture technique

| Couche | Technologie / responsabilité |
|---|---|
| Frontend | React, interface responsive, appels API, gestion des états de chargement et erreurs. |
| Backend | Laravel 13, API REST versionnée `/api/v1`, validation, Services, Policies, Resources. |
| Authentification | Laravel Sanctum. |
| Données | MySQL, migrations Laravel, contraintes et index. |
| Fichiers | Stockage Laravel sécurisé pour les reçus scannés ; accès autorisé uniquement. |
| IA | Laravel AI : orchestration du traitement, conversations et messages. |
| Qualité | Pest/PHPUnit pour tests Feature et Unit ; lint/format via Pint. |

### 14.1 Organisation backend recommandée
- Controllers légers : orchestration HTTP uniquement.
- Form Requests : autorisation et validation des entrées.
- Policies : autorisations sur les ressources.
- Services/Actions : règles métier d'affectation, paiement et analyse
  IA.
- API Resources : format stable des réponses.
- Enums : rôles et statuts.
- Jobs : traitements IA ou fichiers longs exécutés de manière
  asynchrone si nécessaire.

### 14.2 Form Requests --- règles clés
- **StoreResidenceRequest** (admin) : `syndic_id` →
  `exists:users,id` + règle custom `role = syndic`.
- **AssignResidentRequest** : `resident_id` → `exists:users,id` +
  règle custom `role = resident`.
- **StoreReclamationRequest** : `appartement_id` doit appartenir à
  l'utilisateur authentifié ; `priorite` optionnelle, défaut `medium`.
- **StoreRecuRequest** : `mimes:jpg,jpeg,png` ; `charge_id` doit
  référencer une charge `statut = paid` (règle custom, en plus de la
  contrainte unique DB).
- Montants (`charges.montant`, `recus.montant_paye`) : `numeric`,
  `min:0`, DECIMAL en DB.

### 14.3 Format des réponses API
Convention `success`, `message`, `data`. Codes : `422` validation,
`401` non authentifié, `403` accès refusé, `404` ressource absente,
`409` conflit métier lorsque pertinent.

---

## 15. Exigences non fonctionnelles

| Domaine | Exigence |
|---|---|
| Sécurité | Validation serveur, Policies, protection des routes, mots de passe hachés, fichiers non publics par défaut. |
| Performance | Index sur FK et champs filtrés ; pagination des listes ; éviter les requêtes N+1 (eager loading). |
| Traçabilité | Horodatages, audits immuables, snapshot des données utilisées par l'IA. |
| Fiabilité | Transactions pour les opérations multi-écritures ; traitements IA rejouables sans doublons incohérents. |
| Maintenabilité | Nommage Laravel, responsabilités séparées, enums, tests et documentation des règles métier. |
| UX | Interface responsive, messages clairs, états vide/chargement/erreur, confirmation des actions destructrices. |
| Confidentialité | Un résident ne peut jamais accéder aux données d'un appartement non affecté. |

---

## 16. Stratégie de tests et critères d'acceptation

- Authentification : register, login, me, logout, accès invité.
- Rôles : admin, syndic, résident avec réponses 401/403 correctes.
- `ResidencePolicy` et filtrage `index` pour chaque rôle.
- Un syndic ne voit ni ne modifie la résidence d'un autre syndic.
- Un résident accède à une résidence uniquement via un appartement qui
  lui est affecté.
- Un appartement refuse un utilisateur qui n'a pas le rôle RESIDENT.
- Une réclamation ne peut cibler l'appartement d'un autre résident.
- Une charge payée peut avoir un reçu ; un second reçu est refusé.
- L'audit conserve `charges_snapshot` même après modification d'une
  charge.
- Panne IA : statut d'échec explicite, aucune perte de la réclamation,
  possibilité de relance contrôlée.

> **Définition de terminé** : une fonctionnalité est terminée
> seulement lorsque migration, modèle, validation, autorisation,
> contrôleur/service, ressource API et tests correspondants sont
> cohérents et réussissent ensemble.

---

## 17. Plan de réalisation

| Phase | Livrables |
|---|---|
| 1 --- Socle | Authentification, rôles, format API, middleware et tests. |
| 2 --- Patrimoine | Résidences, immeubles, appartements, Policies et filtrage. |
| 3 --- Affectation | `resident_id`, flux d'affectation/désaffectation et accès résident. |
| 4 --- Finances | Charges, statuts, reçus scannés et règles de paiement. |
| 5 --- Réclamations | Création, suivi, autorisations et cycle de statut. |
| 6 --- IA et audit | Snapshot JSON, agent, audit, conversations, messages et reprise sur erreur. |
| 7 --- Frontend React | Écrans par rôle, intégration API, UX et gestion des erreurs. |
| 8 --- Stabilisation | Tests E2E, sécurité, performance, documentation et déploiement. |

---

## 18. Décisions actualisées (état final, post-3 août 2026)

Remplace toute interprétation antérieure sur ces points.

| Sujet | Décision |
|---|---|
| Auto-inscription | `/register` public → toujours `role=resident`. Admin seul peut créer/modifier un rôle. |
| Création patrimoine | Syndic pour lui-même ; admin pour un syndic choisi. |
| Éligibilité réclamation | Toutes les charges de l'appartement ; décision d'instruire au cas par cas, par le syndic. |
| `appartements.statut` | Calculé (accessor dérivé de `resident_id`), pas stocké en DB. |
| Cycle CHARGE | `pending` → `paid` (manuel, syndic) → reçu ajouté après. `overdue` possible. |
| Réanalyse réclamation | Autorisée : plusieurs `AUDIT` par réclamation. |
| Relation conversation--audit | 1--1, `conversations.audit_id` unique. |
| Visibilité AUDIT | Syndic + admin uniquement. Résident suit `reclamations.statut` seulement. |
| Formats reçus | JPG, PNG uniquement. |
| Suppressions | `SoftDeletes` partout, y compris `users`. Suppression syndic hors périmètre. |
| Écrans React / notifications | Hors périmètre pour cette version. |
| Taille max reçus | Non définie, ne bloque pas l'implémentation. |
