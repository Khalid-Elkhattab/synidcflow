# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {VOTRE_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Récupérez votre jeton via <code>POST /api/v1/register</code> ou <code>POST /api/v1/login</code>, puis transmettez-le dans l'en-tête <code>Authorization: Bearer &lt;jeton&gt;</code>.
