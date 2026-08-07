<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SyndicFlow — Documentation de l'API</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.11.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.11.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-systeme" class="tocify-header">
                <li class="tocify-item level-1" data-unique="systeme">
                    <a href="#systeme">Système</a>
                </li>
                                    <ul id="tocify-subheader-systeme" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="systeme-GETapi-v1-health">
                                <a href="#systeme-GETapi-v1-health">GET api/v1/health</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="systeme-GETapi-v1-admin-only">
                                <a href="#systeme-GETapi-v1-admin-only">GET api/v1/admin-only</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-authentification" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authentification">
                    <a href="#authentification">Authentification</a>
                </li>
                                    <ul id="tocify-subheader-authentification" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="authentification-POSTapi-v1-register">
                                <a href="#authentification-POSTapi-v1-register">Inscription d'un nouveau résident.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentification-POSTapi-v1-login">
                                <a href="#authentification-POSTapi-v1-login">Connexion d'un utilisateur existant.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentification-GETapi-v1-me">
                                <a href="#authentification-GETapi-v1-me">Profil de l'utilisateur authentifié.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentification-POSTapi-v1-logout">
                                <a href="#authentification-POSTapi-v1-logout">Déconnexion : révoque le jeton courant.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-utilisateurs" class="tocify-header">
                <li class="tocify-item level-1" data-unique="utilisateurs">
                    <a href="#utilisateurs">Utilisateurs</a>
                </li>
                                    <ul id="tocify-subheader-utilisateurs" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="utilisateurs-POSTapi-v1-users">
                                <a href="#utilisateurs-POSTapi-v1-users">Crée un compte avec le rôle choisi (admin, syndic ou résident).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="utilisateurs-PUTapi-v1-users--user_id-">
                                <a href="#utilisateurs-PUTapi-v1-users--user_id-">Modifie un compte, y compris son rôle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="utilisateurs-GETapi-v1-users">
                                <a href="#utilisateurs-GETapi-v1-users">Liste paginée des utilisateurs, filtrable par rôle.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-residences" class="tocify-header">
                <li class="tocify-item level-1" data-unique="residences">
                    <a href="#residences">Résidences</a>
                </li>
                                    <ul id="tocify-subheader-residences" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="residences-GETapi-v1-residences">
                                <a href="#residences-GETapi-v1-residences">Liste filtrée par rôle : admin = toutes, syndic = les siennes,
résident = déduites de ses appartements.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="residences-POSTapi-v1-residences">
                                <a href="#residences-POSTapi-v1-residences">Syndic : création pour lui-même. Admin : création pour un syndic choisi.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="residences-GETapi-v1-residences--id-">
                                <a href="#residences-GETapi-v1-residences--id-">Affiche une résidence précise.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="residences-PUTapi-v1-residences--id-">
                                <a href="#residences-PUTapi-v1-residences--id-">Met à jour une résidence.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="residences-DELETEapi-v1-residences--id-">
                                <a href="#residences-DELETEapi-v1-residences--id-">Supprime (doucement) une résidence.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-immeubles" class="tocify-header">
                <li class="tocify-item level-1" data-unique="immeubles">
                    <a href="#immeubles">Immeubles</a>
                </li>
                                    <ul id="tocify-subheader-immeubles" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="immeubles-GETapi-v1-residences--residence_id--immeubles">
                                <a href="#immeubles-GETapi-v1-residences--residence_id--immeubles">Liste des immeubles d'une résidence, filtrée par rôle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="immeubles-POSTapi-v1-residences--residence_id--immeubles">
                                <a href="#immeubles-POSTapi-v1-residences--residence_id--immeubles">Crée un immeuble dans la résidence courante.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="immeubles-GETapi-v1-residences--residence_id--immeubles--id-">
                                <a href="#immeubles-GETapi-v1-residences--residence_id--immeubles--id-">Affiche un immeuble précis.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="immeubles-PUTapi-v1-residences--residence_id--immeubles--id-">
                                <a href="#immeubles-PUTapi-v1-residences--residence_id--immeubles--id-">Met à jour un immeuble.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="immeubles-DELETEapi-v1-residences--residence_id--immeubles--id-">
                                <a href="#immeubles-DELETEapi-v1-residences--residence_id--immeubles--id-">Supprime (doucement) un immeuble.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-appartements" class="tocify-header">
                <li class="tocify-item level-1" data-unique="appartements">
                    <a href="#appartements">Appartements</a>
                </li>
                                    <ul id="tocify-subheader-appartements" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="appartements-GETapi-v1-immeubles--immeuble_id--appartements">
                                <a href="#appartements-GETapi-v1-immeubles--immeuble_id--appartements">Liste des appartements d'un immeuble, filtrée par rôle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appartements-POSTapi-v1-immeubles--immeuble_id--appartements">
                                <a href="#appartements-POSTapi-v1-immeubles--immeuble_id--appartements">Crée un appartement dans l'immeuble courant.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appartements-GETapi-v1-immeubles--immeuble_id--appartements--id-">
                                <a href="#appartements-GETapi-v1-immeubles--immeuble_id--appartements--id-">Affiche un appartement précis.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appartements-PUTapi-v1-immeubles--immeuble_id--appartements--id-">
                                <a href="#appartements-PUTapi-v1-immeubles--immeuble_id--appartements--id-">Met à jour un appartement.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appartements-DELETEapi-v1-immeubles--immeuble_id--appartements--id-">
                                <a href="#appartements-DELETEapi-v1-immeubles--immeuble_id--appartements--id-">Supprime (doucement) un appartement.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appartements-PUTapi-v1-appartements--appartement_id--assign">
                                <a href="#appartements-PUTapi-v1-appartements--appartement_id--assign">Affecte un résident existant à un appartement vacant (transaction).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appartements-DELETEapi-v1-appartements--appartement_id--assign">
                                <a href="#appartements-DELETEapi-v1-appartements--appartement_id--assign">Désaffecte le résident courant de l'appartement.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-charges" class="tocify-header">
                <li class="tocify-item level-1" data-unique="charges">
                    <a href="#charges">Charges</a>
                </li>
                                    <ul id="tocify-subheader-charges" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="charges-GETapi-v1-appartements--appartement_id--charges">
                                <a href="#charges-GETapi-v1-appartements--appartement_id--charges">Liste des charges d'un appartement accessible.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="charges-POSTapi-v1-appartements--appartement_id--charges">
                                <a href="#charges-POSTapi-v1-appartements--appartement_id--charges">Crée une charge (statut initial : pending, doc §12).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="charges-GETapi-v1-appartements--appartement_id--charges--id-">
                                <a href="#charges-GETapi-v1-appartements--appartement_id--charges--id-">Affiche une charge précise.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="charges-PUTapi-v1-appartements--appartement_id--charges--id-">
                                <a href="#charges-PUTapi-v1-appartements--appartement_id--charges--id-">Met à jour une charge (hors statut, géré par markAsPaid).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="charges-DELETEapi-v1-appartements--appartement_id--charges--id-">
                                <a href="#charges-DELETEapi-v1-appartements--appartement_id--charges--id-">Supprime (doucement) une charge.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="charges-PATCHapi-v1-charges--charge_id--payer">
                                <a href="#charges-PATCHapi-v1-charges--charge_id--payer">Déclare manuellement le paiement : pending → paid (doc §10).</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-recus" class="tocify-header">
                <li class="tocify-item level-1" data-unique="recus">
                    <a href="#recus">Reçus</a>
                </li>
                                    <ul id="tocify-subheader-recus" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="recus-POSTapi-v1-charges--charge_id--recus">
                                <a href="#recus-POSTapi-v1-charges--charge_id--recus">Téléverse un reçu scanné (JPG/PNG) pour une charge payée.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="recus-GETapi-v1-recus--id-">
                                <a href="#recus-GETapi-v1-recus--id-">Affiche les métadonnées d'un reçu.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="recus-GETapi-v1-recus--recu_id--download">
                                <a href="#recus-GETapi-v1-recus--recu_id--download">Télécharge le fichier scanné (accès autorisé uniquement).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="recus-DELETEapi-v1-recus--id-">
                                <a href="#recus-DELETEapi-v1-recus--id-">Supprime (doucement) un reçu.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-reclamations" class="tocify-header">
                <li class="tocify-item level-1" data-unique="reclamations">
                    <a href="#reclamations">Réclamations</a>
                </li>
                                    <ul id="tocify-subheader-reclamations" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="reclamations-GETapi-v1-reclamations">
                                <a href="#reclamations-GETapi-v1-reclamations">Liste filtrée par rôle : résident = les siennes, syndic = celles de
ses résidences, admin = toutes.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reclamations-POSTapi-v1-reclamations">
                                <a href="#reclamations-POSTapi-v1-reclamations">Crée une réclamation pour l'un des appartements du résident.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reclamations-GETapi-v1-reclamations--id-">
                                <a href="#reclamations-GETapi-v1-reclamations--id-">Affiche une réclamation précise.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reclamations-PUTapi-v1-reclamations--id-">
                                <a href="#reclamations-PUTapi-v1-reclamations--id-">Traitement par le syndic ou l'admin : évolution du statut.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reclamations-DELETEapi-v1-reclamations--id-">
                                <a href="#reclamations-DELETEapi-v1-reclamations--id-">Suppression réservée à l'admin.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-audits-ia" class="tocify-header">
                <li class="tocify-item level-1" data-unique="audits-ia">
                    <a href="#audits-ia">Audits IA</a>
                </li>
                                    <ul id="tocify-subheader-audits-ia" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="audits-ia-POSTapi-v1-reclamations--reclamation_id--analyser">
                                <a href="#audits-ia-POSTapi-v1-reclamations--reclamation_id--analyser">Déclenche l'analyse IA asynchrone d'une réclamation.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="audits-ia-GETapi-v1-reclamations--reclamation_id--audits">
                                <a href="#audits-ia-GETapi-v1-reclamations--reclamation_id--audits">Liste des audits d'une réclamation précisée (syndic propriétaire
ou admin uniquement).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="audits-ia-GETapi-v1-audits">
                                <a href="#audits-ia-GETapi-v1-audits">Liste des audits visible par le syndic propriétaire ou l'admin.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="audits-ia-GETapi-v1-audits--id-">
                                <a href="#audits-ia-GETapi-v1-audits--id-">Détail d'un audit (syndic propriétaire ou admin uniquement).</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: August 7, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>API REST de la plateforme de gestion de syndic (résidences, immeubles, appartements, charges, reçus, réclamations et analyse IA).</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<pre><code>Bienvenue sur la documentation de l'API **SyndicFlow**, versionnée sous le préfixe `/api/v1`.

## Authentification

La grande majorité des endpoints exigent un jeton **Bearer** (Sanctum) :
1. Créez un compte avec `POST /api/v1/register` (rôle résident) — ou connectez-vous avec `POST /api/v1/login`.
2. Récupérez le champ `token` de la réponse.
3. Ajoutez l'en-tête `Authorization: Bearer &lt;token&gt;` à vos requêtes.

## Rôles

Les droits sont contrôlés par les policies Laravel : administrateur, syndic (propriétaire de ses résidences) et résident (accès via ses appartements affectés).

## Format des réponses

Toutes les réponses respectent la convention : `success`, `message`, `data`. Codes HTTP : `422` (validation), `401` (non authentifié), `403` (accès refusé), `404` (ressource introuvable), `409` (conflit métier).</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {VOTRE_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Récupérez votre jeton via <code>POST /api/v1/register</code> ou <code>POST /api/v1/login</code>, puis transmettez-le dans l'en-tête <code>Authorization: Bearer &lt;jeton&gt;</code>.</p>

        <h1 id="systeme">Système</h1>

    

                                <h2 id="systeme-GETapi-v1-health">GET api/v1/health</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-health">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/health" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/health"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-health">
    </span>
<span id="execution-results-GETapi-v1-health" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-health"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-health"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-health" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-health">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-health" data-method="GET"
      data-path="api/v1/health"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-health', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-health"
                    onclick="tryItOut('GETapi-v1-health');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-health"
                    onclick="cancelTryOut('GETapi-v1-health');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-health"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/health</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-health"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-health"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="systeme-GETapi-v1-admin-only">GET api/v1/admin-only</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-admin-only">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/admin-only" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/admin-only"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-only">
    </span>
<span id="execution-results-GETapi-v1-admin-only" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-only"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-only"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-only" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-only">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-only" data-method="GET"
      data-path="api/v1/admin-only"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-only', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-only"
                    onclick="tryItOut('GETapi-v1-admin-only');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-only"
                    onclick="cancelTryOut('GETapi-v1-admin-only');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-only"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin-only</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-only"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-only"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="authentification">Authentification</h1>

    

                                <h2 id="authentification-POSTapi-v1-register">Inscription d&#039;un nouveau résident.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Jean Dupont\",
    \"email\": \"jean@exemple.fr\",
    \"password\": \"MotDePasse123!\",
    \"password_confirmation\": \"MotDePasse123!\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Jean Dupont",
    "email": "jean@exemple.fr",
    "password": "MotDePasse123!",
    "password_confirmation": "MotDePasse123!"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-register">
            <blockquote>
            <p>Example response (201, Compte créé. Le rôle résident est toujours attribué.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;compte cree avec sucess&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Jean Dupont&quot;,
            &quot;email&quot;: &quot;jean@exemple.fr&quot;,
            &quot;role&quot;: &quot;resident&quot;
        },
        &quot;token&quot;: &quot;1|abc123token&quot;,
        &quot;token_type&quot;: &quot;Bearer&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-register" data-method="POST"
      data-path="api/v1/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-register"
                    onclick="tryItOut('POSTapi-v1-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-register"
                    onclick="cancelTryOut('POSTapi-v1-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-register"
               value="Jean Dupont"
               data-component="body">
    <br>
<p>Nom complet de l'utilisateur. Example: <code>Jean Dupont</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-register"
               value="jean@exemple.fr"
               data-component="body">
    <br>
<p>Adresse e-mail (unique). Example: <code>jean@exemple.fr</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-register"
               value="MotDePasse123!"
               data-component="body">
    <br>
<p>Mot de passe (min 8, lettres, casse mixte, chiffres, symboles). Example: <code>MotDePasse123!</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-v1-register"
               value="MotDePasse123!"
               data-component="body">
    <br>
<p>Confirmation du mot de passe. Example: <code>MotDePasse123!</code></p>
        </div>
        </form>

                    <h2 id="authentification-POSTapi-v1-login">Connexion d&#039;un utilisateur existant.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"jean@exemple.fr\",
    \"password\": \"MotDePasse123!\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "jean@exemple.fr",
    "password": "MotDePasse123!"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-login">
            <blockquote>
            <p>Example response (200, Connexion réussie.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Connexion r&eacute;ussie.&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Jean Dupont&quot;,
            &quot;email&quot;: &quot;jean@exemple.fr&quot;,
            &quot;role&quot;: &quot;resident&quot;
        },
        &quot;token&quot;: &quot;1|abc123token&quot;,
        &quot;token_type&quot;: &quot;Bearer&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Identifiants incorrects.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;les informations fournit sont incorrects&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;les informations fournit sont incorrects&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-login" data-method="POST"
      data-path="api/v1/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-login"
                    onclick="tryItOut('POSTapi-v1-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-login"
                    onclick="cancelTryOut('POSTapi-v1-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-login"
               value="jean@exemple.fr"
               data-component="body">
    <br>
<p>Adresse e-mail du compte. Example: <code>jean@exemple.fr</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-login"
               value="MotDePasse123!"
               data-component="body">
    <br>
<p>Mot de passe du compte. Example: <code>MotDePasse123!</code></p>
        </div>
        </form>

                    <h2 id="authentification-GETapi-v1-me">Profil de l&#039;utilisateur authentifié.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/me" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/me"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-me">
            <blockquote>
            <p>Example response (200, Utilisateur courant.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;compte cree avec sucess&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Jean Dupont&quot;,
            &quot;email&quot;: &quot;jean@exemple.fr&quot;,
            &quot;role&quot;: &quot;resident&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-me" data-method="GET"
      data-path="api/v1/me"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-me"
                    onclick="tryItOut('GETapi-v1-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-me"
                    onclick="cancelTryOut('GETapi-v1-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-me"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentification-POSTapi-v1-logout">Déconnexion : révoque le jeton courant.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/logout" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/logout"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-logout">
            <blockquote>
            <p>Example response (200, Jeton révoqué.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;D&eacute;connexion r&eacute;ussie.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-logout" data-method="POST"
      data-path="api/v1/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-logout"
                    onclick="tryItOut('POSTapi-v1-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-logout"
                    onclick="cancelTryOut('POSTapi-v1-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-logout"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="utilisateurs">Utilisateurs</h1>

    

                                <h2 id="utilisateurs-POSTapi-v1-users">Crée un compte avec le rôle choisi (admin, syndic ou résident).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/users" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Marie Martin\",
    \"email\": \"marie@exemple.fr\",
    \"password\": \"MotDePasse123!\",
    \"role\": \"syndic\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/users"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Marie Martin",
    "email": "marie@exemple.fr",
    "password": "MotDePasse123!",
    "role": "syndic"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-users">
            <blockquote>
            <p>Example response (201, Compte créé. Réservé à l&#039;administrateur.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Utilisateur cr&eacute;&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Marie Martin&quot;,
            &quot;email&quot;: &quot;marie@exemple.fr&quot;,
            &quot;role&quot;: &quot;syndic&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-users" data-method="POST"
      data-path="api/v1/users"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-users"
                    onclick="tryItOut('POSTapi-v1-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-users"
                    onclick="cancelTryOut('POSTapi-v1-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-users"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-users"
               value="Marie Martin"
               data-component="body">
    <br>
<p>Nom complet de l'utilisateur. Example: <code>Marie Martin</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-users"
               value="marie@exemple.fr"
               data-component="body">
    <br>
<p>Adresse e-mail (unique). Example: <code>marie@exemple.fr</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-users"
               value="MotDePasse123!"
               data-component="body">
    <br>
<p>Mot de passe (min 8 caractères). Example: <code>MotDePasse123!</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-v1-users"
               value="syndic"
               data-component="body">
    <br>
<p>Rôle à attribuer. Example: <code>syndic</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>admin</code></li> <li><code>syndic</code></li> <li><code>resident</code></li></ul>
        </div>
        </form>

                    <h2 id="utilisateurs-PUTapi-v1-users--user_id-">Modifie un compte, y compris son rôle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-users--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/users/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Marie Martin\",
    \"email\": \"marie@exemple.fr\",
    \"role\": \"resident\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/users/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Marie Martin",
    "email": "marie@exemple.fr",
    "role": "resident"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-users--user_id-">
            <blockquote>
            <p>Example response (200, Compte modifié. Réservé à l&#039;administrateur.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Utilisateur mis &agrave; jour avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Marie Martin&quot;,
            &quot;email&quot;: &quot;marie@exemple.fr&quot;,
            &quot;role&quot;: &quot;admin&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-users--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-users--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-users--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-users--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-users--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-users--user_id-" data-method="PUT"
      data-path="api/v1/users/{user_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-users--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-users--user_id-"
                    onclick="tryItOut('PUTapi-v1-users--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-users--user_id-"
                    onclick="cancelTryOut('PUTapi-v1-users--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-users--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/users/{user_id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/users/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-users--user_id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-users--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="PUTapi-v1-users--user_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user"                data-endpoint="PUTapi-v1-users--user_id-"
               value="2"
               data-component="url">
    <br>
<p>Identifiant de l'utilisateur. Example: <code>2</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-users--user_id-"
               value="Marie Martin"
               data-component="body">
    <br>
<p>Nom complet de l'utilisateur. Example: <code>Marie Martin</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PUTapi-v1-users--user_id-"
               value="marie@exemple.fr"
               data-component="body">
    <br>
<p>Adresse e-mail (unique, ignore l'utilisateur courant). Example: <code>marie@exemple.fr</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="PUTapi-v1-users--user_id-"
               value="resident"
               data-component="body">
    <br>
<p>Nouveau rôle à attribuer. Example: <code>resident</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>admin</code></li> <li><code>syndic</code></li> <li><code>resident</code></li></ul>
        </div>
        </form>

                    <h2 id="utilisateurs-GETapi-v1-users">Liste paginée des utilisateurs, filtrable par rôle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/users?role=syndic" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/users"
);

const params = {
    "role": "syndic",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-users">
            <blockquote>
            <p>Example response (200, Liste paginée. Accès réservé à l&#039;admin ou au syndic.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Utilisateurs r&eacute;cup&eacute;r&eacute;s avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;users&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Jean Dupont&quot;,
                &quot;email&quot;: &quot;jean@exemple.fr&quot;,
                &quot;role&quot;: &quot;syndic&quot;,
                &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;/api/v1/users?page=1&quot;,
            &quot;last&quot;: &quot;/api/v1/users?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;links&quot;: [],
            &quot;path&quot;: &quot;/api/v1/users&quot;,
            &quot;per_page&quot;: 15,
            &quot;to&quot;: 1,
            &quot;total&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-users" data-method="GET"
      data-path="api/v1/users"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-users"
                    onclick="tryItOut('GETapi-v1-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-users"
                    onclick="cancelTryOut('GETapi-v1-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-users"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="GETapi-v1-users"
               value="syndic"
               data-component="query">
    <br>
<p>Filtre sur le rôle (admin, syndic, resident). Example: <code>syndic</code></p>
            </div>
                </form>

                <h1 id="residences">Résidences</h1>

    

                                <h2 id="residences-GETapi-v1-residences">Liste filtrée par rôle : admin = toutes, syndic = les siennes,
résident = déduites de ses appartements.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-residences">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/residences" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-residences">
            <blockquote>
            <p>Example response (200, Liste paginée filtrée par rôle.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;sidences r&eacute;cup&eacute;r&eacute;es avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;residences&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;syndic_id&quot;: 2,
                &quot;name&quot;: &quot;R&eacute;sidence Les Oliviers&quot;,
                &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
                &quot;city&quot;: &quot;Casablanca&quot;,
                &quot;postal_code&quot;: &quot;20000&quot;,
                &quot;description&quot;: null,
                &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;/api/v1/residences?page=1&quot;,
            &quot;last&quot;: &quot;/api/v1/residences?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;path&quot;: &quot;/api/v1/residences&quot;,
            &quot;per_page&quot;: 15,
            &quot;to&quot;: 1,
            &quot;total&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-residences" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-residences"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-residences"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-residences" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-residences">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-residences" data-method="GET"
      data-path="api/v1/residences"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-residences', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-residences"
                    onclick="tryItOut('GETapi-v1-residences');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-residences"
                    onclick="cancelTryOut('GETapi-v1-residences');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-residences"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/residences</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-residences"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-residences"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-residences"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="residences-POSTapi-v1-residences">Syndic : création pour lui-même. Admin : création pour un syndic choisi.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-residences">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/residences" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Résidence Les Oliviers\",
    \"address\": \"12 rue des Fleurs\",
    \"city\": \"Casablanca\",
    \"postal_code\": \"20000\",
    \"description\": \"Résidence principale\",
    \"syndic_id\": 2
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Résidence Les Oliviers",
    "address": "12 rue des Fleurs",
    "city": "Casablanca",
    "postal_code": "20000",
    "description": "Résidence principale",
    "syndic_id": 2
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-residences">
            <blockquote>
            <p>Example response (201, Résidence créée. Le syndic devient automatiquement propriétaire pour un compte syndic.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;sidence cr&eacute;&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;residence&quot;: {
            &quot;id&quot;: 1,
            &quot;syndic_id&quot;: 2,
            &quot;name&quot;: &quot;R&eacute;sidence Les Oliviers&quot;,
            &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
            &quot;city&quot;: &quot;Casablanca&quot;,
            &quot;postal_code&quot;: &quot;20000&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-residences" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-residences"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-residences"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-residences" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-residences">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-residences" data-method="POST"
      data-path="api/v1/residences"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-residences', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-residences"
                    onclick="tryItOut('POSTapi-v1-residences');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-residences"
                    onclick="cancelTryOut('POSTapi-v1-residences');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-residences"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/residences</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-residences"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-residences"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-residences"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-residences"
               value="Résidence Les Oliviers"
               data-component="body">
    <br>
<p>Nom de la résidence. Example: <code>Résidence Les Oliviers</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-v1-residences"
               value="12 rue des Fleurs"
               data-component="body">
    <br>
<p>Adresse. Example: <code>12 rue des Fleurs</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-v1-residences"
               value="Casablanca"
               data-component="body">
    <br>
<p>Ville. Example: <code>Casablanca</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>postal_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="postal_code"                data-endpoint="POSTapi-v1-residences"
               value="20000"
               data-component="body">
    <br>
<p>Code postal. Example: <code>20000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-residences"
               value="Résidence principale"
               data-component="body">
    <br>
<p>Description. Example: <code>Résidence principale</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>syndic_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="syndic_id"                data-endpoint="POSTapi-v1-residences"
               value="2"
               data-component="body">
    <br>
<p>Identifiant du syndic propriétaire (uniquement si l'appelant est un administrateur). Example: <code>2</code></p>
        </div>
        </form>

                    <h2 id="residences-GETapi-v1-residences--id-">Affiche une résidence précise.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-residences--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/residences/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-residences--id-">
            <blockquote>
            <p>Example response (200, Détail d&#039;une résidence.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;sidence r&eacute;cup&eacute;r&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;residence&quot;: {
            &quot;id&quot;: 1,
            &quot;syndic_id&quot;: 2,
            &quot;name&quot;: &quot;R&eacute;sidence Les Oliviers&quot;,
            &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
            &quot;city&quot;: &quot;Casablanca&quot;,
            &quot;postal_code&quot;: &quot;20000&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-residences--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-residences--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-residences--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-residences--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-residences--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-residences--id-" data-method="GET"
      data-path="api/v1/residences/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-residences--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-residences--id-"
                    onclick="tryItOut('GETapi-v1-residences--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-residences--id-"
                    onclick="cancelTryOut('GETapi-v1-residences--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-residences--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/residences/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-residences--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-residences--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-residences--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-residences--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="GETapi-v1-residences--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="residences-PUTapi-v1-residences--id-">Met à jour une résidence.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-residences--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/residences/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Résidence Les Oliviers\",
    \"address\": \"12 rue des Fleurs\",
    \"city\": \"Casablanca\",
    \"postal_code\": \"20000\",
    \"description\": \"Résidence principale\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Résidence Les Oliviers",
    "address": "12 rue des Fleurs",
    "city": "Casablanca",
    "postal_code": "20000",
    "description": "Résidence principale"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-residences--id-">
            <blockquote>
            <p>Example response (200, Résidence mise à jour.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;sidence mise &agrave; jour avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;residence&quot;: {
            &quot;id&quot;: 1,
            &quot;syndic_id&quot;: 2,
            &quot;name&quot;: &quot;R&eacute;sidence Les Oliviers&quot;,
            &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
            &quot;city&quot;: &quot;Casablanca&quot;,
            &quot;postal_code&quot;: &quot;20000&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-residences--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-residences--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-residences--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-residences--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-residences--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-residences--id-" data-method="PUT"
      data-path="api/v1/residences/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-residences--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-residences--id-"
                    onclick="tryItOut('PUTapi-v1-residences--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-residences--id-"
                    onclick="cancelTryOut('PUTapi-v1-residences--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-residences--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/residences/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/residences/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-residences--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-residences--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-residences--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-residences--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="PUTapi-v1-residences--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-residences--id-"
               value="Résidence Les Oliviers"
               data-component="body">
    <br>
<p>Nom de la résidence. Example: <code>Résidence Les Oliviers</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="PUTapi-v1-residences--id-"
               value="12 rue des Fleurs"
               data-component="body">
    <br>
<p>Adresse. Example: <code>12 rue des Fleurs</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="PUTapi-v1-residences--id-"
               value="Casablanca"
               data-component="body">
    <br>
<p>Ville. Example: <code>Casablanca</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>postal_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="postal_code"                data-endpoint="PUTapi-v1-residences--id-"
               value="20000"
               data-component="body">
    <br>
<p>Code postal. Example: <code>20000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-v1-residences--id-"
               value="Résidence principale"
               data-component="body">
    <br>
<p>Description. Example: <code>Résidence principale</code></p>
        </div>
        </form>

                    <h2 id="residences-DELETEapi-v1-residences--id-">Supprime (doucement) une résidence.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-residences--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/residences/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-residences--id-">
            <blockquote>
            <p>Example response (200, Résidence supprimée (soft delete).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;sidence supprim&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-residences--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-residences--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-residences--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-residences--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-residences--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-residences--id-" data-method="DELETE"
      data-path="api/v1/residences/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-residences--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-residences--id-"
                    onclick="tryItOut('DELETEapi-v1-residences--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-residences--id-"
                    onclick="cancelTryOut('DELETEapi-v1-residences--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-residences--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/residences/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-residences--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-residences--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-residences--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-v1-residences--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="DELETEapi-v1-residences--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="immeubles">Immeubles</h1>

    

                                <h2 id="immeubles-GETapi-v1-residences--residence_id--immeubles">Liste des immeubles d&#039;une résidence, filtrée par rôle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-residences--residence_id--immeubles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/residences/1/immeubles" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1/immeubles"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-residences--residence_id--immeubles">
            <blockquote>
            <p>Example response (200, Liste paginée des immeubles d&#039;une résidence.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Immeubles r&eacute;cup&eacute;r&eacute;s avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;immeubles&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;residence_id&quot;: 1,
                &quot;name&quot;: &quot;B&acirc;timent A&quot;,
                &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
                &quot;nombre_etages&quot;: 5,
                &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;/api/v1/residences/1/immeubles?page=1&quot;,
            &quot;last&quot;: &quot;/api/v1/residences/1/immeubles?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;path&quot;: &quot;/api/v1/residences/1/immeubles&quot;,
            &quot;per_page&quot;: 15,
            &quot;to&quot;: 1,
            &quot;total&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-residences--residence_id--immeubles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-residences--residence_id--immeubles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-residences--residence_id--immeubles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-residences--residence_id--immeubles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-residences--residence_id--immeubles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-residences--residence_id--immeubles" data-method="GET"
      data-path="api/v1/residences/{residence_id}/immeubles"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-residences--residence_id--immeubles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-residences--residence_id--immeubles"
                    onclick="tryItOut('GETapi-v1-residences--residence_id--immeubles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-residences--residence_id--immeubles"
                    onclick="cancelTryOut('GETapi-v1-residences--residence_id--immeubles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-residences--residence_id--immeubles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/residences/{residence_id}/immeubles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-residences--residence_id--immeubles"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-residences--residence_id--immeubles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-residences--residence_id--immeubles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_id"                data-endpoint="GETapi-v1-residences--residence_id--immeubles"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="GETapi-v1-residences--residence_id--immeubles"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="immeubles-POSTapi-v1-residences--residence_id--immeubles">Crée un immeuble dans la résidence courante.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-residences--residence_id--immeubles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/residences/1/immeubles" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Bâtiment A\",
    \"address\": \"12 rue des Fleurs\",
    \"nombre_etages\": 5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1/immeubles"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Bâtiment A",
    "address": "12 rue des Fleurs",
    "nombre_etages": 5
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-residences--residence_id--immeubles">
            <blockquote>
            <p>Example response (201, Immeuble créé.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Immeuble cr&eacute;&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;immeuble&quot;: {
            &quot;id&quot;: 1,
            &quot;residence_id&quot;: 1,
            &quot;name&quot;: &quot;B&acirc;timent A&quot;,
            &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
            &quot;nombre_etages&quot;: 5,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-residences--residence_id--immeubles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-residences--residence_id--immeubles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-residences--residence_id--immeubles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-residences--residence_id--immeubles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-residences--residence_id--immeubles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-residences--residence_id--immeubles" data-method="POST"
      data-path="api/v1/residences/{residence_id}/immeubles"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-residences--residence_id--immeubles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-residences--residence_id--immeubles"
                    onclick="tryItOut('POSTapi-v1-residences--residence_id--immeubles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-residences--residence_id--immeubles"
                    onclick="cancelTryOut('POSTapi-v1-residences--residence_id--immeubles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-residences--residence_id--immeubles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/residences/{residence_id}/immeubles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_id"                data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="Bâtiment A"
               data-component="body">
    <br>
<p>Nom de l'immeuble. Example: <code>Bâtiment A</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="12 rue des Fleurs"
               data-component="body">
    <br>
<p>Adresse de l'immeuble. Example: <code>12 rue des Fleurs</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nombre_etages</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="nombre_etages"                data-endpoint="POSTapi-v1-residences--residence_id--immeubles"
               value="5"
               data-component="body">
    <br>
<p>Nombre d'étages. Example: <code>5</code></p>
        </div>
        </form>

                    <h2 id="immeubles-GETapi-v1-residences--residence_id--immeubles--id-">Affiche un immeuble précis.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-residences--residence_id--immeubles--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/residences/1/immeubles/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1/immeubles/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-residences--residence_id--immeubles--id-">
            <blockquote>
            <p>Example response (200, Détail d&#039;un immeuble.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Immeuble r&eacute;cup&eacute;r&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;immeuble&quot;: {
            &quot;id&quot;: 1,
            &quot;residence_id&quot;: 1,
            &quot;name&quot;: &quot;B&acirc;timent A&quot;,
            &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
            &quot;nombre_etages&quot;: 5,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-residences--residence_id--immeubles--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-residences--residence_id--immeubles--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-residences--residence_id--immeubles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-residences--residence_id--immeubles--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-residences--residence_id--immeubles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-residences--residence_id--immeubles--id-" data-method="GET"
      data-path="api/v1/residences/{residence_id}/immeubles/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-residences--residence_id--immeubles--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-residences--residence_id--immeubles--id-"
                    onclick="tryItOut('GETapi-v1-residences--residence_id--immeubles--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-residences--residence_id--immeubles--id-"
                    onclick="cancelTryOut('GETapi-v1-residences--residence_id--immeubles--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-residences--residence_id--immeubles--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/residences/{residence_id}/immeubles/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-residences--residence_id--immeubles--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-residences--residence_id--immeubles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-residences--residence_id--immeubles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_id"                data-endpoint="GETapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="GETapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="GETapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="immeubles-PUTapi-v1-residences--residence_id--immeubles--id-">Met à jour un immeuble.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-residences--residence_id--immeubles--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/residences/1/immeubles/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Bâtiment A\",
    \"address\": \"12 rue des Fleurs\",
    \"nombre_etages\": 5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1/immeubles/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Bâtiment A",
    "address": "12 rue des Fleurs",
    "nombre_etages": 5
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-residences--residence_id--immeubles--id-">
            <blockquote>
            <p>Example response (200, Immeuble mis à jour.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Immeuble mis &agrave; jour avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;immeuble&quot;: {
            &quot;id&quot;: 1,
            &quot;residence_id&quot;: 1,
            &quot;name&quot;: &quot;B&acirc;timent A&quot;,
            &quot;address&quot;: &quot;12 rue des Fleurs&quot;,
            &quot;nombre_etages&quot;: 6,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-residences--residence_id--immeubles--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-residences--residence_id--immeubles--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-residences--residence_id--immeubles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-residences--residence_id--immeubles--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-residences--residence_id--immeubles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-residences--residence_id--immeubles--id-" data-method="PUT"
      data-path="api/v1/residences/{residence_id}/immeubles/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-residences--residence_id--immeubles--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-residences--residence_id--immeubles--id-"
                    onclick="tryItOut('PUTapi-v1-residences--residence_id--immeubles--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-residences--residence_id--immeubles--id-"
                    onclick="cancelTryOut('PUTapi-v1-residences--residence_id--immeubles--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-residences--residence_id--immeubles--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/residences/{residence_id}/immeubles/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/residences/{residence_id}/immeubles/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_id"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="Bâtiment A"
               data-component="body">
    <br>
<p>Nom de l'immeuble. Example: <code>Bâtiment A</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="12 rue des Fleurs"
               data-component="body">
    <br>
<p>Adresse de l'immeuble. Example: <code>12 rue des Fleurs</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nombre_etages</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="nombre_etages"                data-endpoint="PUTapi-v1-residences--residence_id--immeubles--id-"
               value="5"
               data-component="body">
    <br>
<p>Nombre d'étages. Example: <code>5</code></p>
        </div>
        </form>

                    <h2 id="immeubles-DELETEapi-v1-residences--residence_id--immeubles--id-">Supprime (doucement) un immeuble.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-residences--residence_id--immeubles--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/residences/1/immeubles/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/residences/1/immeubles/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-residences--residence_id--immeubles--id-">
            <blockquote>
            <p>Example response (200, Immeuble supprimé (soft delete).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Immeuble supprim&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-residences--residence_id--immeubles--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-residences--residence_id--immeubles--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-residences--residence_id--immeubles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-residences--residence_id--immeubles--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-residences--residence_id--immeubles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-residences--residence_id--immeubles--id-" data-method="DELETE"
      data-path="api/v1/residences/{residence_id}/immeubles/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-residences--residence_id--immeubles--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-residences--residence_id--immeubles--id-"
                    onclick="tryItOut('DELETEapi-v1-residences--residence_id--immeubles--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-residences--residence_id--immeubles--id-"
                    onclick="cancelTryOut('DELETEapi-v1-residences--residence_id--immeubles--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-residences--residence_id--immeubles--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/residences/{residence_id}/immeubles/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-residences--residence_id--immeubles--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-residences--residence_id--immeubles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-residences--residence_id--immeubles--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_id"                data-endpoint="DELETEapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the residence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>residence</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence"                data-endpoint="DELETEapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la résidence. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="DELETEapi-v1-residences--residence_id--immeubles--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="appartements">Appartements</h1>

    

                                <h2 id="appartements-GETapi-v1-immeubles--immeuble_id--appartements">Liste des appartements d&#039;un immeuble, filtrée par rôle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-immeubles--immeuble_id--appartements">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/immeubles/1/appartements" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/immeubles/1/appartements"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-immeubles--immeuble_id--appartements">
            <blockquote>
            <p>Example response (200, Liste paginée des appartements. Un résident ne voit que les siens.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Appartements r&eacute;cup&eacute;r&eacute;s avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;appartements&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;immeuble_id&quot;: 1,
                &quot;resident_id&quot;: null,
                &quot;numero&quot;: &quot;A1&quot;,
                &quot;etage&quot;: 1,
                &quot;superficie&quot;: &quot;85.00&quot;,
                &quot;statut&quot;: &quot;vacant&quot;,
                &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;/api/v1/immeubles/1/appartements?page=1&quot;,
            &quot;last&quot;: &quot;/api/v1/immeubles/1/appartements?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;path&quot;: &quot;/api/v1/immeubles/1/appartements&quot;,
            &quot;per_page&quot;: 15,
            &quot;to&quot;: 1,
            &quot;total&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-immeubles--immeuble_id--appartements" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-immeubles--immeuble_id--appartements"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-immeubles--immeuble_id--appartements"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-immeubles--immeuble_id--appartements" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-immeubles--immeuble_id--appartements">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-immeubles--immeuble_id--appartements" data-method="GET"
      data-path="api/v1/immeubles/{immeuble_id}/appartements"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-immeubles--immeuble_id--appartements', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-immeubles--immeuble_id--appartements"
                    onclick="tryItOut('GETapi-v1-immeubles--immeuble_id--appartements');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-immeubles--immeuble_id--appartements"
                    onclick="cancelTryOut('GETapi-v1-immeubles--immeuble_id--appartements');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-immeubles--immeuble_id--appartements"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/immeubles/{immeuble_id}/appartements</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble_id"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="appartements-POSTapi-v1-immeubles--immeuble_id--appartements">Crée un appartement dans l&#039;immeuble courant.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-immeubles--immeuble_id--appartements">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/immeubles/1/appartements" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"numero\": \"A\",
    \"etage\": 1,
    \"superficie\": 85
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/immeubles/1/appartements"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "numero": "A",
    "etage": 1,
    "superficie": 85
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-immeubles--immeuble_id--appartements">
            <blockquote>
            <p>Example response (201, Appartement créé.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Appartement cr&eacute;&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;appartement&quot;: {
            &quot;id&quot;: 1,
            &quot;immeuble_id&quot;: 1,
            &quot;resident_id&quot;: null,
            &quot;numero&quot;: &quot;A&quot;,
            &quot;etage&quot;: 1,
            &quot;superficie&quot;: &quot;85.00&quot;,
            &quot;statut&quot;: &quot;vacant&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-immeubles--immeuble_id--appartements" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-immeubles--immeuble_id--appartements"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-immeubles--immeuble_id--appartements"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-immeubles--immeuble_id--appartements" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-immeubles--immeuble_id--appartements">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-immeubles--immeuble_id--appartements" data-method="POST"
      data-path="api/v1/immeubles/{immeuble_id}/appartements"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-immeubles--immeuble_id--appartements', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-immeubles--immeuble_id--appartements"
                    onclick="tryItOut('POSTapi-v1-immeubles--immeuble_id--appartements');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-immeubles--immeuble_id--appartements"
                    onclick="cancelTryOut('POSTapi-v1-immeubles--immeuble_id--appartements');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-immeubles--immeuble_id--appartements"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/immeubles/{immeuble_id}/appartements</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble_id"                data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>numero</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="numero"                data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="A"
               data-component="body">
    <br>
<p>Numéro de l'appartement (unique dans l'immeuble). Example: <code>A</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>etage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="etage"                data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="1"
               data-component="body">
    <br>
<p>Étage. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>superficie</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="superficie"                data-endpoint="POSTapi-v1-immeubles--immeuble_id--appartements"
               value="85"
               data-component="body">
    <br>
<p>Superficie en m². Example: <code>85</code></p>
        </div>
        </form>

                    <h2 id="appartements-GETapi-v1-immeubles--immeuble_id--appartements--id-">Affiche un appartement précis.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-immeubles--immeuble_id--appartements--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/immeubles/1/appartements/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/immeubles/1/appartements/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-immeubles--immeuble_id--appartements--id-">
            <blockquote>
            <p>Example response (200, Détail d&#039;un appartement.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Appartement r&eacute;cup&eacute;r&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;appartement&quot;: {
            &quot;id&quot;: 1,
            &quot;immeuble_id&quot;: 1,
            &quot;resident_id&quot;: 3,
            &quot;numero&quot;: &quot;A&quot;,
            &quot;etage&quot;: 1,
            &quot;superficie&quot;: &quot;85.00&quot;,
            &quot;statut&quot;: &quot;occup&eacute;&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-immeubles--immeuble_id--appartements--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-immeubles--immeuble_id--appartements--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-immeubles--immeuble_id--appartements--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-immeubles--immeuble_id--appartements--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-immeubles--immeuble_id--appartements--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-immeubles--immeuble_id--appartements--id-" data-method="GET"
      data-path="api/v1/immeubles/{immeuble_id}/appartements/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-immeubles--immeuble_id--appartements--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-immeubles--immeuble_id--appartements--id-"
                    onclick="tryItOut('GETapi-v1-immeubles--immeuble_id--appartements--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-immeubles--immeuble_id--appartements--id-"
                    onclick="cancelTryOut('GETapi-v1-immeubles--immeuble_id--appartements--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-immeubles--immeuble_id--appartements--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/immeubles/{immeuble_id}/appartements/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble_id"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="GETapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="appartements-PUTapi-v1-immeubles--immeuble_id--appartements--id-">Met à jour un appartement.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-immeubles--immeuble_id--appartements--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/immeubles/1/appartements/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"numero\": \"A\",
    \"etage\": 1,
    \"superficie\": 85
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/immeubles/1/appartements/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "numero": "A",
    "etage": 1,
    "superficie": 85
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-immeubles--immeuble_id--appartements--id-">
            <blockquote>
            <p>Example response (200, Appartement mis à jour.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Appartement mis &agrave; jour avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;appartement&quot;: {
            &quot;id&quot;: 1,
            &quot;immeuble_id&quot;: 1,
            &quot;resident_id&quot;: 3,
            &quot;numero&quot;: &quot;A&quot;,
            &quot;etage&quot;: 2,
            &quot;superficie&quot;: &quot;85.00&quot;,
            &quot;statut&quot;: &quot;occup&eacute;&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-immeubles--immeuble_id--appartements--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-immeubles--immeuble_id--appartements--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-immeubles--immeuble_id--appartements--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-immeubles--immeuble_id--appartements--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-immeubles--immeuble_id--appartements--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-immeubles--immeuble_id--appartements--id-" data-method="PUT"
      data-path="api/v1/immeubles/{immeuble_id}/appartements/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-immeubles--immeuble_id--appartements--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-immeubles--immeuble_id--appartements--id-"
                    onclick="tryItOut('PUTapi-v1-immeubles--immeuble_id--appartements--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-immeubles--immeuble_id--appartements--id-"
                    onclick="cancelTryOut('PUTapi-v1-immeubles--immeuble_id--appartements--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-immeubles--immeuble_id--appartements--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/immeubles/{immeuble_id}/appartements/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/immeubles/{immeuble_id}/appartements/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble_id"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>numero</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="numero"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="A"
               data-component="body">
    <br>
<p>Numéro de l'appartement. Example: <code>A</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>etage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="etage"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="body">
    <br>
<p>Étage. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>superficie</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="superficie"                data-endpoint="PUTapi-v1-immeubles--immeuble_id--appartements--id-"
               value="85"
               data-component="body">
    <br>
<p>Superficie en m². Example: <code>85</code></p>
        </div>
        </form>

                    <h2 id="appartements-DELETEapi-v1-immeubles--immeuble_id--appartements--id-">Supprime (doucement) un appartement.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-immeubles--immeuble_id--appartements--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/immeubles/1/appartements/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/immeubles/1/appartements/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-immeubles--immeuble_id--appartements--id-">
            <blockquote>
            <p>Example response (200, Appartement supprimé (soft delete).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Appartement supprim&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-immeubles--immeuble_id--appartements--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-immeubles--immeuble_id--appartements--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-immeubles--immeuble_id--appartements--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-immeubles--immeuble_id--appartements--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-immeubles--immeuble_id--appartements--id-" data-method="DELETE"
      data-path="api/v1/immeubles/{immeuble_id}/appartements/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-immeubles--immeuble_id--appartements--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
                    onclick="tryItOut('DELETEapi-v1-immeubles--immeuble_id--appartements--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
                    onclick="cancelTryOut('DELETEapi-v1-immeubles--immeuble_id--appartements--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/immeubles/{immeuble_id}/appartements/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble_id"                data-endpoint="DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>immeuble</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="immeuble"                data-endpoint="DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'immeuble. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="DELETEapi-v1-immeubles--immeuble_id--appartements--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="appartements-PUTapi-v1-appartements--appartement_id--assign">Affecte un résident existant à un appartement vacant (transaction).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-appartements--appartement_id--assign">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/appartements/1/assign" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"resident_id\": 3
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/appartements/1/assign"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "resident_id": 3
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-appartements--appartement_id--assign">
            <blockquote>
            <p>Example response (200, Résident affecté. Une affectation existante est remplacée.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;sident affect&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;appartement&quot;: {
            &quot;id&quot;: 1,
            &quot;immeuble_id&quot;: 1,
            &quot;resident_id&quot;: 3,
            &quot;numero&quot;: &quot;A&quot;,
            &quot;etage&quot;: 1,
            &quot;superficie&quot;: &quot;85.00&quot;,
            &quot;statut&quot;: &quot;occup&eacute;&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-appartements--appartement_id--assign" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-appartements--appartement_id--assign"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-appartements--appartement_id--assign"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-appartements--appartement_id--assign" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-appartements--appartement_id--assign">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-appartements--appartement_id--assign" data-method="PUT"
      data-path="api/v1/appartements/{appartement_id}/assign"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-appartements--appartement_id--assign', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-appartements--appartement_id--assign"
                    onclick="tryItOut('PUTapi-v1-appartements--appartement_id--assign');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-appartements--appartement_id--assign"
                    onclick="cancelTryOut('PUTapi-v1-appartements--appartement_id--assign');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-appartements--appartement_id--assign"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/appartements/{appartement_id}/assign</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-appartements--appartement_id--assign"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-appartements--appartement_id--assign"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-appartements--appartement_id--assign"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="PUTapi-v1-appartements--appartement_id--assign"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="PUTapi-v1-appartements--appartement_id--assign"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>resident_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="resident_id"                data-endpoint="PUTapi-v1-appartements--appartement_id--assign"
               value="3"
               data-component="body">
    <br>
<p>Identifiant d'un utilisateur ayant le rôle résident. Example: <code>3</code></p>
        </div>
        </form>

                    <h2 id="appartements-DELETEapi-v1-appartements--appartement_id--assign">Désaffecte le résident courant de l&#039;appartement.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-appartements--appartement_id--assign">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/appartements/1/assign" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/appartements/1/assign"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-appartements--appartement_id--assign">
            <blockquote>
            <p>Example response (200, Résident désaffecté (appartement vacant).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;sident d&eacute;saffect&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;appartement&quot;: {
            &quot;id&quot;: 1,
            &quot;immeuble_id&quot;: 1,
            &quot;resident_id&quot;: null,
            &quot;numero&quot;: &quot;A&quot;,
            &quot;etage&quot;: 1,
            &quot;superficie&quot;: &quot;85.00&quot;,
            &quot;statut&quot;: &quot;vacant&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-appartements--appartement_id--assign" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-appartements--appartement_id--assign"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-appartements--appartement_id--assign"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-appartements--appartement_id--assign" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-appartements--appartement_id--assign">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-appartements--appartement_id--assign" data-method="DELETE"
      data-path="api/v1/appartements/{appartement_id}/assign"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-appartements--appartement_id--assign', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-appartements--appartement_id--assign"
                    onclick="tryItOut('DELETEapi-v1-appartements--appartement_id--assign');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-appartements--appartement_id--assign"
                    onclick="cancelTryOut('DELETEapi-v1-appartements--appartement_id--assign');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-appartements--appartement_id--assign"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/appartements/{appartement_id}/assign</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-appartements--appartement_id--assign"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-appartements--appartement_id--assign"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-appartements--appartement_id--assign"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="DELETEapi-v1-appartements--appartement_id--assign"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="DELETEapi-v1-appartements--appartement_id--assign"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="charges">Charges</h1>

    

                                <h2 id="charges-GETapi-v1-appartements--appartement_id--charges">Liste des charges d&#039;un appartement accessible.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-appartements--appartement_id--charges">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/appartements/1/charges" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/appartements/1/charges"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-appartements--appartement_id--charges">
            <blockquote>
            <p>Example response (200, Liste paginée des charges d&#039;un appartement.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Charges r&eacute;cup&eacute;r&eacute;es avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;charges&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;appartement_id&quot;: 1,
                &quot;libelle&quot;: &quot;Charge de copropri&eacute;t&eacute;&quot;,
                &quot;description&quot;: null,
                &quot;montant&quot;: &quot;150.50&quot;,
                &quot;date_echeance&quot;: &quot;2026-08-31&quot;,
                &quot;statut&quot;: &quot;pending&quot;,
                &quot;periode&quot;: &quot;Ao&ucirc;t 2026&quot;,
                &quot;date_paiement&quot;: null,
                &quot;recu&quot;: null,
                &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;/api/v1/appartements/1/charges?page=1&quot;,
            &quot;last&quot;: &quot;/api/v1/appartements/1/charges?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;path&quot;: &quot;/api/v1/appartements/1/charges&quot;,
            &quot;per_page&quot;: 15,
            &quot;to&quot;: 1,
            &quot;total&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-appartements--appartement_id--charges" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-appartements--appartement_id--charges"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-appartements--appartement_id--charges"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-appartements--appartement_id--charges" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-appartements--appartement_id--charges">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-appartements--appartement_id--charges" data-method="GET"
      data-path="api/v1/appartements/{appartement_id}/charges"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-appartements--appartement_id--charges', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-appartements--appartement_id--charges"
                    onclick="tryItOut('GETapi-v1-appartements--appartement_id--charges');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-appartements--appartement_id--charges"
                    onclick="cancelTryOut('GETapi-v1-appartements--appartement_id--charges');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-appartements--appartement_id--charges"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/appartements/{appartement_id}/charges</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-appartements--appartement_id--charges"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-appartements--appartement_id--charges"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-appartements--appartement_id--charges"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="GETapi-v1-appartements--appartement_id--charges"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="GETapi-v1-appartements--appartement_id--charges"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="charges-POSTapi-v1-appartements--appartement_id--charges">Crée une charge (statut initial : pending, doc §12).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-appartements--appartement_id--charges">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/appartements/1/charges" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"libelle\": \"Charge de copropriété\",
    \"description\": \"Charges communes du mois\",
    \"montant\": 120.5,
    \"date_echeance\": \"2026-08-31\",
    \"periode\": \"Août 2026\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/appartements/1/charges"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "libelle": "Charge de copropriété",
    "description": "Charges communes du mois",
    "montant": 120.5,
    "date_echeance": "2026-08-31",
    "periode": "Août 2026"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-appartements--appartement_id--charges">
            <blockquote>
            <p>Example response (201, Charge créée avec le statut `pending`.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Charge cr&eacute;&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;charge&quot;: {
            &quot;id&quot;: 1,
            &quot;appartement_id&quot;: 1,
            &quot;libelle&quot;: &quot;Charge de copropri&eacute;t&eacute;&quot;,
            &quot;description&quot;: null,
            &quot;montant&quot;: &quot;120.50&quot;,
            &quot;date_echeance&quot;: &quot;2026-08-31&quot;,
            &quot;statut&quot;: &quot;pending&quot;,
            &quot;periode&quot;: &quot;Ao&ucirc;t 2026&quot;,
            &quot;date_paiement&quot;: null,
            &quot;recu&quot;: null,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-appartements--appartement_id--charges" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-appartements--appartement_id--charges"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-appartements--appartement_id--charges"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-appartements--appartement_id--charges" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-appartements--appartement_id--charges">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-appartements--appartement_id--charges" data-method="POST"
      data-path="api/v1/appartements/{appartement_id}/charges"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-appartements--appartement_id--charges', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-appartements--appartement_id--charges"
                    onclick="tryItOut('POSTapi-v1-appartements--appartement_id--charges');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-appartements--appartement_id--charges"
                    onclick="cancelTryOut('POSTapi-v1-appartements--appartement_id--charges');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-appartements--appartement_id--charges"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/appartements/{appartement_id}/charges</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>libelle</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="libelle"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="Charge de copropriété"
               data-component="body">
    <br>
<p>Libellé de la charge. Example: <code>Charge de copropriété</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="Charges communes du mois"
               data-component="body">
    <br>
<p>Description. Example: <code>Charges communes du mois</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>montant</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="montant"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="120.5"
               data-component="body">
    <br>
<p>Montant de la charge (&gt;= 0). Example: <code>120.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date_echeance</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date_echeance"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="2026-08-31"
               data-component="body">
    <br>
<p>Date d'échéance (format date). Example: <code>2026-08-31</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>periode</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="periode"                data-endpoint="POSTapi-v1-appartements--appartement_id--charges"
               value="Août 2026"
               data-component="body">
    <br>
<p>Période concernée. Example: <code>Août 2026</code></p>
        </div>
        </form>

                    <h2 id="charges-GETapi-v1-appartements--appartement_id--charges--id-">Affiche une charge précise.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-appartements--appartement_id--charges--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/appartements/1/charges/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/appartements/1/charges/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-appartements--appartement_id--charges--id-">
            <blockquote>
            <p>Example response (200, Détail d&#039;une charge, avec son reçu s&#039;il a été téléversé.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Charge r&eacute;cup&eacute;r&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;charge&quot;: {
            &quot;id&quot;: 1,
            &quot;appartement_id&quot;: 1,
            &quot;libelle&quot;: &quot;Charge de copropri&eacute;t&eacute;&quot;,
            &quot;description&quot;: null,
            &quot;montant&quot;: &quot;120.50&quot;,
            &quot;date_echeance&quot;: &quot;2026-08-31&quot;,
            &quot;statut&quot;: &quot;paid&quot;,
            &quot;periode&quot;: &quot;Ao&ucirc;t 2026&quot;,
            &quot;date_paiement&quot;: &quot;2026-08-15&quot;,
            &quot;recu&quot;: {
                &quot;id&quot;: 1,
                &quot;charge_id&quot;: 1,
                &quot;nom_original&quot;: &quot;recu.jpg&quot;,
                &quot;type_mime&quot;: &quot;image/jpeg&quot;,
                &quot;taille&quot;: 102400,
                &quot;date_paiement&quot;: &quot;2026-08-15&quot;,
                &quot;montant_paye&quot;: &quot;120.50&quot;,
                &quot;download_url&quot;: &quot;/api/v1/recus/1/download&quot;,
                &quot;created_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;
            },
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-appartements--appartement_id--charges--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-appartements--appartement_id--charges--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-appartements--appartement_id--charges--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-appartements--appartement_id--charges--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-appartements--appartement_id--charges--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-appartements--appartement_id--charges--id-" data-method="GET"
      data-path="api/v1/appartements/{appartement_id}/charges/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-appartements--appartement_id--charges--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-appartements--appartement_id--charges--id-"
                    onclick="tryItOut('GETapi-v1-appartements--appartement_id--charges--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-appartements--appartement_id--charges--id-"
                    onclick="cancelTryOut('GETapi-v1-appartements--appartement_id--charges--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-appartements--appartement_id--charges--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/appartements/{appartement_id}/charges/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-appartements--appartement_id--charges--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-appartements--appartement_id--charges--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-appartements--appartement_id--charges--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="GETapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the charge. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="GETapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>charge</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="charge"                data-endpoint="GETapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la charge. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="charges-PUTapi-v1-appartements--appartement_id--charges--id-">Met à jour une charge (hors statut, géré par markAsPaid).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-appartements--appartement_id--charges--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/appartements/1/charges/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"libelle\": \"Charge de copropriété\",
    \"description\": \"Chargement annuel du mois\",
    \"montant\": 150,
    \"date_echeance\": \"2026-09-30\",
    \"periode\": \"Septembre 2026\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/appartements/1/charges/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "libelle": "Charge de copropriété",
    "description": "Chargement annuel du mois",
    "montant": 150,
    "date_echeance": "2026-09-30",
    "periode": "Septembre 2026"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-appartements--appartement_id--charges--id-">
            <blockquote>
            <p>Example response (200, Charge mise à jour (le statut n&#039;est pas modifiable ici).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Charge mise &agrave; jour avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;charge&quot;: {
            &quot;id&quot;: 1,
            &quot;appartement_id&quot;: 1,
            &quot;libelle&quot;: &quot;Charge de copropri&eacute;t&eacute;&quot;,
            &quot;description&quot;: null,
            &quot;montant&quot;: &quot;150.00&quot;,
            &quot;date_echeance&quot;: &quot;2026-09-30&quot;,
            &quot;statut&quot;: &quot;pending&quot;,
            &quot;periode&quot;: &quot;Septembre 2026&quot;,
            &quot;date_paiement&quot;: null,
            &quot;recu&quot;: null,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-appartements--appartement_id--charges--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-appartements--appartement_id--charges--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-appartements--appartement_id--charges--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-appartements--appartement_id--charges--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-appartements--appartement_id--charges--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-appartements--appartement_id--charges--id-" data-method="PUT"
      data-path="api/v1/appartements/{appartement_id}/charges/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-appartements--appartement_id--charges--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-appartements--appartement_id--charges--id-"
                    onclick="tryItOut('PUTapi-v1-appartements--appartement_id--charges--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-appartements--appartement_id--charges--id-"
                    onclick="cancelTryOut('PUTapi-v1-appartements--appartement_id--charges--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-appartements--appartement_id--charges--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/appartements/{appartement_id}/charges/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/appartements/{appartement_id}/charges/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the charge. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>charge</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="charge"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la charge. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>libelle</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="libelle"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="Charge de copropriété"
               data-component="body">
    <br>
<p>Libellé de la charge. Example: <code>Charge de copropriété</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="Chargement annuel du mois"
               data-component="body">
    <br>
<p>Description. Example: <code>Chargement annuel du mois</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>montant</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="montant"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="150"
               data-component="body">
    <br>
<p>Montant de la charge. Example: <code>150</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date_echeance</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date_echeance"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="2026-09-30"
               data-component="body">
    <br>
<p>Date d'échéance. Example: <code>2026-09-30</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>periode</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="periode"                data-endpoint="PUTapi-v1-appartements--appartement_id--charges--id-"
               value="Septembre 2026"
               data-component="body">
    <br>
<p>Période concernée. Example: <code>Septembre 2026</code></p>
        </div>
        </form>

                    <h2 id="charges-DELETEapi-v1-appartements--appartement_id--charges--id-">Supprime (doucement) une charge.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-appartements--appartement_id--charges--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/appartements/1/charges/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/appartements/1/charges/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-appartements--appartement_id--charges--id-">
            <blockquote>
            <p>Example response (200, Charge supprimée (soft delete).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Charge supprim&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-appartements--appartement_id--charges--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-appartements--appartement_id--charges--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-appartements--appartement_id--charges--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-appartements--appartement_id--charges--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-appartements--appartement_id--charges--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-appartements--appartement_id--charges--id-" data-method="DELETE"
      data-path="api/v1/appartements/{appartement_id}/charges/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-appartements--appartement_id--charges--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-appartements--appartement_id--charges--id-"
                    onclick="tryItOut('DELETEapi-v1-appartements--appartement_id--charges--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-appartements--appartement_id--charges--id-"
                    onclick="cancelTryOut('DELETEapi-v1-appartements--appartement_id--charges--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-appartements--appartement_id--charges--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/appartements/{appartement_id}/charges/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-appartements--appartement_id--charges--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-appartements--appartement_id--charges--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-appartements--appartement_id--charges--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="DELETEapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the charge. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appartement</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement"                data-endpoint="DELETEapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'appartement. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>charge</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="charge"                data-endpoint="DELETEapi-v1-appartements--appartement_id--charges--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la charge. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="charges-PATCHapi-v1-charges--charge_id--payer">Déclare manuellement le paiement : pending → paid (doc §10).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PATCHapi-v1-charges--charge_id--payer">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost:8000/api/v1/charges/1/payer" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"date_paiement\": \"2026-08-15\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/charges/1/payer"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "date_paiement": "2026-08-15"
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-charges--charge_id--payer">
            <blockquote>
            <p>Example response (200, Charge marquée payée.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Charge marqu&eacute;e comme pay&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;charge&quot;: {
            &quot;id&quot;: 1,
            &quot;appartement_id&quot;: 1,
            &quot;libelle&quot;: &quot;Charge de copropri&eacute;t&eacute;&quot;,
            &quot;description&quot;: null,
            &quot;montant&quot;: &quot;120.50&quot;,
            &quot;date_echeance&quot;: &quot;2026-08-31&quot;,
            &quot;statut&quot;: &quot;paid&quot;,
            &quot;periode&quot;: &quot;Ao&ucirc;t 2026&quot;,
            &quot;date_paiement&quot;: &quot;2026-08-15&quot;,
            &quot;recu&quot;: null,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, La charge est déjà payée.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Cette charge est d&eacute;j&agrave; marqu&eacute;e comme pay&eacute;e.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-v1-charges--charge_id--payer" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-charges--charge_id--payer"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-charges--charge_id--payer"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-charges--charge_id--payer" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-charges--charge_id--payer">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-v1-charges--charge_id--payer" data-method="PATCH"
      data-path="api/v1/charges/{charge_id}/payer"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-charges--charge_id--payer', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-charges--charge_id--payer"
                    onclick="tryItOut('PATCHapi-v1-charges--charge_id--payer');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-charges--charge_id--payer"
                    onclick="cancelTryOut('PATCHapi-v1-charges--charge_id--payer');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-charges--charge_id--payer"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/charges/{charge_id}/payer</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-v1-charges--charge_id--payer"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-v1-charges--charge_id--payer"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-v1-charges--charge_id--payer"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>charge_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="charge_id"                data-endpoint="PATCHapi-v1-charges--charge_id--payer"
               value="1"
               data-component="url">
    <br>
<p>The ID of the charge. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>charge</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="charge"                data-endpoint="PATCHapi-v1-charges--charge_id--payer"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la charge. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date_paiement</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date_paiement"                data-endpoint="PATCHapi-v1-charges--charge_id--payer"
               value="2026-08-15"
               data-component="body">
    <br>
<p>Date du paiement. Par défaut : aujourd'hui. Example: <code>2026-08-15</code></p>
        </div>
        </form>

                <h1 id="recus">Reçus</h1>

    

                                <h2 id="recus-POSTapi-v1-charges--charge_id--recus">Téléverse un reçu scanné (JPG/PNG) pour une charge payée.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-charges--charge_id--recus">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/charges/1/recus" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "date_paiement=2026-08-15"\
    --form "montant_paye=120.5"\
    --form "fichier=@C:\Users\khalid\AppData\Local\Temp\phpDC11.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/charges/1/recus"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('date_paiement', '2026-08-15');
body.append('montant_paye', '120.5');
body.append('fichier', document.querySelector('input[name="fichier"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-charges--charge_id--recus">
            <blockquote>
            <p>Example response (201, Reçu téléversé. Un seul reçu actif par charge.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Re&ccedil;u t&eacute;l&eacute;vers&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;recu&quot;: {
            &quot;id&quot;: 1,
            &quot;charge_id&quot;: 1,
            &quot;nom_original&quot;: &quot;recu.jpg&quot;,
            &quot;type_mime&quot;: &quot;image/jpeg&quot;,
            &quot;taille&quot;: 102400,
            &quot;date_paiement&quot;: &quot;2026-08-15&quot;,
            &quot;montant_paye&quot;: &quot;120.50&quot;,
            &quot;download_url&quot;: &quot;/api/v1/recus/1/download&quot;,
            &quot;created_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, La charge n&#039;est pas payée ou possède déjà un reçu.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Un re&ccedil;u ne peut &ecirc;tre ajout&eacute; qu&#039;&agrave; une charge pay&eacute;e.&quot;,
    &quot;errors&quot;: {
        &quot;fichier&quot;: [
            &quot;Un re&ccedil;u ne peut &ecirc;tre ajout&eacute; qu&#039;&agrave; une charge pay&eacute;e.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-charges--charge_id--recus" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-charges--charge_id--recus"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-charges--charge_id--recus"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-charges--charge_id--recus" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-charges--charge_id--recus">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-charges--charge_id--recus" data-method="POST"
      data-path="api/v1/charges/{charge_id}/recus"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-charges--charge_id--recus', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-charges--charge_id--recus"
                    onclick="tryItOut('POSTapi-v1-charges--charge_id--recus');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-charges--charge_id--recus"
                    onclick="cancelTryOut('POSTapi-v1-charges--charge_id--recus');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-charges--charge_id--recus"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/charges/{charge_id}/recus</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>charge_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="charge_id"                data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value="1"
               data-component="url">
    <br>
<p>The ID of the charge. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>charge</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="charge"                data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la charge (doit être payée). Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>fichier</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="fichier"                data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value=""
               data-component="body">
    <br>
<p>Fichier image du reçu (JPG, JPEG ou PNG, max 10 Mo). Example: <code>C:\Users\khalid\AppData\Local\Temp\phpDC11.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date_paiement</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date_paiement"                data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value="2026-08-15"
               data-component="body">
    <br>
<p>Date du paiement. Example: <code>2026-08-15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>montant_paye</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="montant_paye"                data-endpoint="POSTapi-v1-charges--charge_id--recus"
               value="120.5"
               data-component="body">
    <br>
<p>Montant payé. Example: <code>120.5</code></p>
        </div>
        </form>

                    <h2 id="recus-GETapi-v1-recus--id-">Affiche les métadonnées d&#039;un reçu.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-recus--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/recus/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/recus/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-recus--id-">
            <blockquote>
            <p>Example response (200, Métadonnées du reçu (le fichier se télécharge via `download_url`).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Re&ccedil;u r&eacute;cup&eacute;r&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;recu&quot;: {
            &quot;id&quot;: 1,
            &quot;charge_id&quot;: 1,
            &quot;nom_original&quot;: &quot;recu.jpg&quot;,
            &quot;type_mime&quot;: &quot;image/jpeg&quot;,
            &quot;taille&quot;: 102400,
            &quot;date_paiement&quot;: &quot;2026-08-15&quot;,
            &quot;montant_paye&quot;: &quot;120.50&quot;,
            &quot;download_url&quot;: &quot;/api/v1/recus/1/download&quot;,
            &quot;created_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-15T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-recus--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-recus--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-recus--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-recus--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-recus--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-recus--id-" data-method="GET"
      data-path="api/v1/recus/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-recus--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-recus--id-"
                    onclick="tryItOut('GETapi-v1-recus--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-recus--id-"
                    onclick="cancelTryOut('GETapi-v1-recus--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-recus--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/recus/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-recus--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-recus--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-recus--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-recus--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the recu. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>recu</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="recu"                data-endpoint="GETapi-v1-recus--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant du reçu. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="recus-GETapi-v1-recus--recu_id--download">Télécharge le fichier scanné (accès autorisé uniquement).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-recus--recu_id--download">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/recus/1/download" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/recus/1/download"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-recus--recu_id--download">
            <blockquote>
            <p>Example response (200, Retourne le fichier du reçu en pièce jointe (Content-Disposition: attachment).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Fichier absent du stockage.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Le fichier du re&ccedil;u est introuvable.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-recus--recu_id--download" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-recus--recu_id--download"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-recus--recu_id--download"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-recus--recu_id--download" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-recus--recu_id--download">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-recus--recu_id--download" data-method="GET"
      data-path="api/v1/recus/{recu_id}/download"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-recus--recu_id--download', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-recus--recu_id--download"
                    onclick="tryItOut('GETapi-v1-recus--recu_id--download');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-recus--recu_id--download"
                    onclick="cancelTryOut('GETapi-v1-recus--recu_id--download');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-recus--recu_id--download"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/recus/{recu_id}/download</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-recus--recu_id--download"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-recus--recu_id--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-recus--recu_id--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>recu_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="recu_id"                data-endpoint="GETapi-v1-recus--recu_id--download"
               value="1"
               data-component="url">
    <br>
<p>The ID of the recu. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>recu</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="recu"                data-endpoint="GETapi-v1-recus--recu_id--download"
               value="1"
               data-component="url">
    <br>
<p>Identifiant du reçu. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="recus-DELETEapi-v1-recus--id-">Supprime (doucement) un reçu.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-recus--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/recus/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/recus/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-recus--id-">
            <blockquote>
            <p>Example response (200, Reçu supprimé (soft delete).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Re&ccedil;u supprim&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-recus--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-recus--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-recus--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-recus--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-recus--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-recus--id-" data-method="DELETE"
      data-path="api/v1/recus/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-recus--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-recus--id-"
                    onclick="tryItOut('DELETEapi-v1-recus--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-recus--id-"
                    onclick="cancelTryOut('DELETEapi-v1-recus--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-recus--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/recus/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-recus--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-recus--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-recus--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-v1-recus--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the recu. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>recu</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="recu"                data-endpoint="DELETEapi-v1-recus--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant du reçu. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="reclamations">Réclamations</h1>

    

                                <h2 id="reclamations-GETapi-v1-reclamations">Liste filtrée par rôle : résident = les siennes, syndic = celles de
ses résidences, admin = toutes.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-reclamations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/reclamations" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reclamations"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-reclamations">
            <blockquote>
            <p>Example response (200, Liste paginée des réclamations visibles selon le rôle.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;clamations r&eacute;cup&eacute;r&eacute;es avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;reclamations&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;resident_id&quot;: 3,
                &quot;appartement_id&quot;: 1,
                &quot;titre&quot;: &quot;Fuite d&#039;eau&quot;,
                &quot;description&quot;: &quot;Fuite au niveau de la salle de bain.&quot;,
                &quot;statut&quot;: &quot;submitted&quot;,
                &quot;priorite&quot;: &quot;high&quot;,
                &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;/api/v1/reclamations?page=1&quot;,
            &quot;last&quot;: &quot;/api/v1/reclamations?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;path&quot;: &quot;/api/v1/reclamations&quot;,
            &quot;per_page&quot;: 15,
            &quot;to&quot;: 1,
            &quot;total&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-reclamations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-reclamations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-reclamations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-reclamations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-reclamations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-reclamations" data-method="GET"
      data-path="api/v1/reclamations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reclamations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-reclamations"
                    onclick="tryItOut('GETapi-v1-reclamations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-reclamations"
                    onclick="cancelTryOut('GETapi-v1-reclamations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-reclamations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/reclamations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-reclamations"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-reclamations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-reclamations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="reclamations-POSTapi-v1-reclamations">Crée une réclamation pour l&#039;un des appartements du résident.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-reclamations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/reclamations" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"appartement_id\": 1,
    \"titre\": \"Fuite d\'eau\",
    \"description\": \"Fuite au niveau de la salle de bain.\",
    \"priorite\": \"high\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reclamations"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "appartement_id": 1,
    "titre": "Fuite d'eau",
    "description": "Fuite au niveau de la salle de bain.",
    "priorite": "high"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-reclamations">
            <blockquote>
            <p>Example response (201, Réclamation créée avec le statut `submitted`.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;clamation d&eacute;pos&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;reclamation&quot;: {
            &quot;id&quot;: 1,
            &quot;resident_id&quot;: 3,
            &quot;appartement_id&quot;: 1,
            &quot;titre&quot;: &quot;Fuite d&#039;eau&quot;,
            &quot;description&quot;: &quot;Fuite au niveau de la salle de bain.&quot;,
            &quot;statut&quot;: &quot;submitted&quot;,
            &quot;priorite&quot;: &quot;high&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-reclamations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-reclamations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-reclamations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-reclamations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-reclamations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-reclamations" data-method="POST"
      data-path="api/v1/reclamations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-reclamations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-reclamations"
                    onclick="tryItOut('POSTapi-v1-reclamations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-reclamations"
                    onclick="cancelTryOut('POSTapi-v1-reclamations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-reclamations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/reclamations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-reclamations"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-reclamations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-reclamations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>appartement_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appartement_id"                data-endpoint="POSTapi-v1-reclamations"
               value="1"
               data-component="body">
    <br>
<p>Identifiant de l'appartement (doit être affecté à l'utilisateur). Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>titre</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="titre"                data-endpoint="POSTapi-v1-reclamations"
               value="Fuite d'eau"
               data-component="body">
    <br>
<p>Titre de la réclamation. Example: <code>Fuite d'eau</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-reclamations"
               value="Fuite au niveau de la salle de bain."
               data-component="body">
    <br>
<p>Description détaillée. Example: <code>Fuite au niveau de la salle de bain.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priorite</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priorite"                data-endpoint="POSTapi-v1-reclamations"
               value="high"
               data-component="body">
    <br>
<p>Priorité de la réclamation. Example: <code>high</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>low</code></li> <li><code>medium</code></li> <li><code>high</code></li> <li><code>urgent</code></li></ul>
        </div>
        </form>

                    <h2 id="reclamations-GETapi-v1-reclamations--id-">Affiche une réclamation précise.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-reclamations--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/reclamations/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reclamations/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-reclamations--id-">
            <blockquote>
            <p>Example response (200, Détail d&#039;une réclamation.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;clamation r&eacute;cup&eacute;r&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;reclamation&quot;: {
            &quot;id&quot;: 1,
            &quot;resident_id&quot;: 3,
            &quot;appartement_id&quot;: 1,
            &quot;titre&quot;: &quot;Fuite d&#039;eau&quot;,
            &quot;description&quot;: &quot;Fuite au niveau de la salle de bain.&quot;,
            &quot;statut&quot;: &quot;submitted&quot;,
            &quot;priorite&quot;: &quot;high&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-reclamations--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-reclamations--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-reclamations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-reclamations--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-reclamations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-reclamations--id-" data-method="GET"
      data-path="api/v1/reclamations/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reclamations--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-reclamations--id-"
                    onclick="tryItOut('GETapi-v1-reclamations--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-reclamations--id-"
                    onclick="cancelTryOut('GETapi-v1-reclamations--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-reclamations--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/reclamations/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-reclamations--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-reclamations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-reclamations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-reclamations--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the reclamation. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reclamation</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reclamation"                data-endpoint="GETapi-v1-reclamations--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la réclamation. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="reclamations-PUTapi-v1-reclamations--id-">Traitement par le syndic ou l&#039;admin : évolution du statut.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-reclamations--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/reclamations/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"statut\": \"under_review\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reclamations/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "statut": "under_review"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-reclamations--id-">
            <blockquote>
            <p>Example response (200, Réclamation traitée (statut mis à jour).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;clamation mise &agrave; jour avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;reclamation&quot;: {
            &quot;id&quot;: 1,
            &quot;resident_id&quot;: 3,
            &quot;appartement_id&quot;: 1,
            &quot;titre&quot;: &quot;Fuite d&#039;eau&quot;,
            &quot;description&quot;: &quot;Fuite au niveau de la salle de bain.&quot;,
            &quot;statut&quot;: &quot;under_review&quot;,
            &quot;priorite&quot;: &quot;high&quot;,
            &quot;created_at&quot;: &quot;2026-08-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-08-02T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-reclamations--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-reclamations--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-reclamations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-reclamations--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-reclamations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-reclamations--id-" data-method="PUT"
      data-path="api/v1/reclamations/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-reclamations--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-reclamations--id-"
                    onclick="tryItOut('PUTapi-v1-reclamations--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-reclamations--id-"
                    onclick="cancelTryOut('PUTapi-v1-reclamations--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-reclamations--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/reclamations/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/reclamations/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-reclamations--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-reclamations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-reclamations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-reclamations--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the reclamation. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reclamation</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reclamation"                data-endpoint="PUTapi-v1-reclamations--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la réclamation. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>statut</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="statut"                data-endpoint="PUTapi-v1-reclamations--id-"
               value="under_review"
               data-component="body">
    <br>
<p>Nouveau statut de la réclamation. Example: <code>under_review</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>submitted</code></li> <li><code>under_review</code></li> <li><code>accepted</code></li> <li><code>rejected</code></li> <li><code>resolved</code></li> <li><code>closed</code></li></ul>
        </div>
        </form>

                    <h2 id="reclamations-DELETEapi-v1-reclamations--id-">Suppression réservée à l&#039;admin.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-reclamations--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/reclamations/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reclamations/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-reclamations--id-">
            <blockquote>
            <p>Example response (200, Réclamation supprimée (soft delete). Réservé à l&#039;administrateur.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;R&eacute;clamation supprim&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-reclamations--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-reclamations--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-reclamations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-reclamations--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-reclamations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-reclamations--id-" data-method="DELETE"
      data-path="api/v1/reclamations/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-reclamations--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-reclamations--id-"
                    onclick="tryItOut('DELETEapi-v1-reclamations--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-reclamations--id-"
                    onclick="cancelTryOut('DELETEapi-v1-reclamations--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-reclamations--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/reclamations/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-reclamations--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-reclamations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-reclamations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-v1-reclamations--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the reclamation. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reclamation</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reclamation"                data-endpoint="DELETEapi-v1-reclamations--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la réclamation. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="audits-ia">Audits IA</h1>

    

                                <h2 id="audits-ia-POSTapi-v1-reclamations--reclamation_id--analyser">Déclenche l&#039;analyse IA asynchrone d&#039;une réclamation.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-reclamations--reclamation_id--analyser">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/reclamations/1/analyser" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reclamations/1/analyser"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-reclamations--reclamation_id--analyser">
            <blockquote>
            <p>Example response (202, Analyse lancée (traitement asynchrone via la queue).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Analyse de la r&eacute;clamation lanc&eacute;e avec succ&egrave;s.&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-reclamations--reclamation_id--analyser" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-reclamations--reclamation_id--analyser"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-reclamations--reclamation_id--analyser"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-reclamations--reclamation_id--analyser" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-reclamations--reclamation_id--analyser">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-reclamations--reclamation_id--analyser" data-method="POST"
      data-path="api/v1/reclamations/{reclamation_id}/analyser"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-reclamations--reclamation_id--analyser', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-reclamations--reclamation_id--analyser"
                    onclick="tryItOut('POSTapi-v1-reclamations--reclamation_id--analyser');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-reclamations--reclamation_id--analyser"
                    onclick="cancelTryOut('POSTapi-v1-reclamations--reclamation_id--analyser');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-reclamations--reclamation_id--analyser"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/reclamations/{reclamation_id}/analyser</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-reclamations--reclamation_id--analyser"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-reclamations--reclamation_id--analyser"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-reclamations--reclamation_id--analyser"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reclamation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reclamation_id"                data-endpoint="POSTapi-v1-reclamations--reclamation_id--analyser"
               value="1"
               data-component="url">
    <br>
<p>The ID of the reclamation. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reclamation</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reclamation"                data-endpoint="POSTapi-v1-reclamations--reclamation_id--analyser"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la réclamation à analyser. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="audits-ia-GETapi-v1-reclamations--reclamation_id--audits">Liste des audits d&#039;une réclamation précisée (syndic propriétaire
ou admin uniquement).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-reclamations--reclamation_id--audits">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/reclamations/1/audits" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/reclamations/1/audits"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-reclamations--reclamation_id--audits">
            <blockquote>
            <p>Example response (200, Liste paginée des audits d&#039;une réclamation (une réclamation peut être analysée plusieurs fois).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Audits de la r&eacute;clamation r&eacute;cup&eacute;r&eacute;s avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;audits&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;reclamation_id&quot;: 1,
                &quot;charges_snapshot&quot;: {
                    &quot;total&quot;: &quot;120.50&quot;,
                    &quot;statut&quot;: &quot;paid&quot;
                },
                &quot;resultat&quot;: {
                    &quot;resume&quot;: &quot;Fuite signal&eacute;e&quot;,
                    &quot;categorie&quot;: &quot;plomberie&quot;,
                    &quot;priorite&quot;: &quot;haute&quot;
                },
                &quot;decision&quot;: &quot;review&quot;,
                &quot;statut&quot;: &quot;completed&quot;,
                &quot;modele_ia&quot;: &quot;llama-3.1-8b-instant&quot;,
                &quot;traite_at&quot;: &quot;2026-08-02T10:00:00.000000Z&quot;,
                &quot;conversation&quot;: {
                    &quot;id&quot;: 1
                },
                &quot;created_at&quot;: &quot;2026-08-02T10:00:00.000000Z&quot;
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-reclamations--reclamation_id--audits" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-reclamations--reclamation_id--audits"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-reclamations--reclamation_id--audits"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-reclamations--reclamation_id--audits" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-reclamations--reclamation_id--audits">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-reclamations--reclamation_id--audits" data-method="GET"
      data-path="api/v1/reclamations/{reclamation_id}/audits"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reclamations--reclamation_id--audits', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-reclamations--reclamation_id--audits"
                    onclick="tryItOut('GETapi-v1-reclamations--reclamation_id--audits');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-reclamations--reclamation_id--audits"
                    onclick="cancelTryOut('GETapi-v1-reclamations--reclamation_id--audits');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-reclamations--reclamation_id--audits"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/reclamations/{reclamation_id}/audits</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-reclamations--reclamation_id--audits"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-reclamations--reclamation_id--audits"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-reclamations--reclamation_id--audits"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reclamation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reclamation_id"                data-endpoint="GETapi-v1-reclamations--reclamation_id--audits"
               value="1"
               data-component="url">
    <br>
<p>The ID of the reclamation. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reclamation</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reclamation"                data-endpoint="GETapi-v1-reclamations--reclamation_id--audits"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de la réclamation. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="audits-ia-GETapi-v1-audits">Liste des audits visible par le syndic propriétaire ou l&#039;admin.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-audits">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/audits" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/audits"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-audits">
            <blockquote>
            <p>Example response (200, Liste paginée des audits (syndic propriétaire ou admin uniquement).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Audits r&eacute;cup&eacute;r&eacute;s avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;audits&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;reclamation_id&quot;: 1,
                &quot;charges_snapshot&quot;: {
                    &quot;total&quot;: &quot;120.50&quot;,
                    &quot;statut&quot;: &quot;paid&quot;
                },
                &quot;resultat&quot;: {
                    &quot;resume&quot;: &quot;Fuite signal&eacute;e&quot;,
                    &quot;categorie&quot;: &quot;plomberie&quot;,
                    &quot;priorite&quot;: &quot;haute&quot;
                },
                &quot;decision&quot;: &quot;review&quot;,
                &quot;statut&quot;: &quot;completed&quot;,
                &quot;modele_ia&quot;: &quot;llama-3.1-8b-instant&quot;,
                &quot;traite_at&quot;: &quot;2026-08-02T10:00:00.000000Z&quot;,
                &quot;conversation&quot;: {
                    &quot;id&quot;: 1
                },
                &quot;created_at&quot;: &quot;2026-08-02T10:00:00.000000Z&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;/api/v1/audits?page=1&quot;,
            &quot;last&quot;: &quot;/api/v1/audits?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;path&quot;: &quot;/api/v1/audits&quot;,
            &quot;per_page&quot;: 15,
            &quot;to&quot;: 1,
            &quot;total&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-audits" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-audits"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-audits"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-audits" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-audits">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-audits" data-method="GET"
      data-path="api/v1/audits"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-audits', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-audits"
                    onclick="tryItOut('GETapi-v1-audits');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-audits"
                    onclick="cancelTryOut('GETapi-v1-audits');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-audits"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/audits</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-audits"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-audits"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-audits"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="audits-ia-GETapi-v1-audits--id-">Détail d&#039;un audit (syndic propriétaire ou admin uniquement).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-audits--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/audits/1" \
    --header "Authorization: Bearer {VOTRE_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/audits/1"
);

const headers = {
    "Authorization": "Bearer {VOTRE_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-audits--id-">
            <blockquote>
            <p>Example response (200, Détail d&#039;un audit, avec sa conversation IA.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Audit r&eacute;cup&eacute;r&eacute; avec succ&egrave;s.&quot;,
    &quot;data&quot;: {
        &quot;audit&quot;: {
            &quot;id&quot;: 1,
            &quot;reclamation_id&quot;: 1,
            &quot;charges_snapshot&quot;: {
                &quot;total&quot;: &quot;120.50&quot;,
                &quot;statut&quot;: &quot;paid&quot;
            },
            &quot;resultat&quot;: {
                &quot;resume&quot;: &quot;Fuite signal&eacute;e&quot;,
                &quot;categorie&quot;: &quot;plomberie&quot;,
                &quot;priorite&quot;: &quot;haute&quot;
            },
            &quot;decision&quot;: &quot;review&quot;,
            &quot;statut&quot;: &quot;completed&quot;,
            &quot;modele_ia&quot;: &quot;llama-3.1-8b-instant&quot;,
            &quot;traite_at&quot;: &quot;2026-08-02T10:00:00.000000Z&quot;,
            &quot;conversation&quot;: {
                &quot;id&quot;: 1
            },
            &quot;created_at&quot;: &quot;2026-08-02T10:00:00.000000Z&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-audits--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-audits--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-audits--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-audits--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-audits--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-audits--id-" data-method="GET"
      data-path="api/v1/audits/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-audits--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-audits--id-"
                    onclick="tryItOut('GETapi-v1-audits--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-audits--id-"
                    onclick="cancelTryOut('GETapi-v1-audits--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-audits--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/audits/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-audits--id-"
               value="Bearer {VOTRE_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {VOTRE_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-audits--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-audits--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-audits--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the audit. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>audit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="audit"                data-endpoint="GETapi-v1-audits--id-"
               value="1"
               data-component="url">
    <br>
<p>Identifiant de l'audit. Example: <code>1</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
