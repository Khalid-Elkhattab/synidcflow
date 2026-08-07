# Introduction

API REST de la plateforme de gestion de syndic (résidences, immeubles, appartements, charges, reçus, réclamations et analyse IA).

<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>

    Bienvenue sur la documentation de l'API **SyndicFlow**, versionnée sous le préfixe `/api/v1`.

    ## Authentification

    La grande majorité des endpoints exigent un jeton **Bearer** (Sanctum) :
    1. Créez un compte avec `POST /api/v1/register` (rôle résident) — ou connectez-vous avec `POST /api/v1/login`.
    2. Récupérez le champ `token` de la réponse.
    3. Ajoutez l'en-tête `Authorization: Bearer <token>` à vos requêtes.

    ## Rôles

    Les droits sont contrôlés par les policies Laravel : administrateur, syndic (propriétaire de ses résidences) et résident (accès via ses appartements affectés).

    ## Format des réponses

    Toutes les réponses respectent la convention : `success`, `message`, `data`. Codes HTTP : `422` (validation), `401` (non authentifié), `403` (accès refusé), `404` (ressource introuvable), `409` (conflit métier).

