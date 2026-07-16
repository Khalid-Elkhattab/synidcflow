# User Stories — Application de gestion de syndic

## Stack retenue

* PHP
* Laravel
* Laravel Breeze avec Blade
* MySQL
* Tailwind CSS
* Laravel AI SDK
* Laravel DomPDF
* Laravel Debugbar
* Laravel Telescope
* Pest

## Rôles

* Administrateur
* Syndic
* Résident

Les rôles sont définis dans un Enum Laravel.

---

# Epic 0 — Setup et architecture Laravel

## ST-01 — Installer Laravel Breeze

**En tant que développeur,**
je veux installer Laravel Breeze avec Blade,
afin de disposer d’un système d’authentification simple.

### Critères d’acceptation

* L’utilisateur peut se connecter.
* L’utilisateur peut se déconnecter.
* L’utilisateur peut demander la réinitialisation de son mot de passe.
* Les routes privées utilisent le middleware `auth`.
* Les vues d’authentification utilisent Blade.

---

## ST-02 — Configurer les rôles avec un Enum

**En tant que développeur,**
je veux définir les rôles dans un Enum,
afin d’éviter les valeurs incorrectes dans le code.

### Rôles

* `ADMIN`
* `SYNDIC`
* `RESIDENT`

### Critères d’acceptation

* La table `users` contient une colonne `role`.
* Le modèle `User` utilise un cast vers l’Enum.
* Aucun rôle non prévu ne peut être enregistré.

---

## ST-03 — Créer un middleware de rôle

**En tant que développeur,**
je veux créer un middleware de rôle,
afin de protéger les espaces de l’application.

### Critères d’acceptation

* L’espace administrateur est réservé à l’administrateur.
* L’espace syndic est réservé au syndic.
* L’espace résident est réservé au résident.
* Un accès interdit retourne une erreur `403`.

---

## ST-04 — Mettre en place les Policies

**En tant que développeur,**
je veux créer des Policies pour les modèles sensibles,
afin de protéger chaque ressource.

### Policies prévues

* `BuildingPolicy`
* `ApartmentPolicy`
* `ChargePolicy`
* `PaymentPolicy`
* `ReceiptPolicy`
* `ComplaintPolicy`
* `GeneralMeetingPolicy`

### Critères d’acceptation

* Un syndic accède uniquement aux données de ses immeubles.
* Un résident accède uniquement à ses propres données.
* L’administrateur peut superviser les données autorisées.
* La modification directe d’une URL ne permet pas d’accéder aux données d’un autre utilisateur.

---

## ST-05 — Utiliser les Form Requests

**En tant que développeur,**
je veux utiliser des Form Requests,
afin de centraliser la validation et l’autorisation.

### Critères d’acceptation

* Chaque formulaire important possède un Form Request.
* La méthode `authorize()` appelle la Policy correspondante.
* La méthode `rules()` valide les données.
* Les erreurs de validation sont affichées clairement.

---

## ST-06 — Installer les outils de développement

**En tant que développeur,**
je veux installer les outils nécessaires,
afin de surveiller et tester l’application.

### Dépendances prévues

* Laravel Debugbar ;
* Laravel Telescope ;
* Laravel AI SDK ;
* Laravel DomPDF.

### Critères d’acceptation

* Debugbar fonctionne uniquement en développement.
* Telescope permet de consulter les requêtes, erreurs, Jobs et notifications.
* Laravel AI SDK peut analyser une réclamation.
* DomPDF peut générer les reçus et procès-verbaux.

---

# Epic 1 — Authentification

## US-01 — Se connecter

**En tant qu’utilisateur,**
je veux me connecter avec mon e-mail et mon mot de passe,
afin d’accéder à mon espace.

### Critères d’acceptation

* L’utilisateur saisit son e-mail et son mot de passe.
* Le système vérifie les identifiants.
* Un message d’erreur est affiché si les identifiants sont incorrects.
* L’utilisateur est redirigé selon son rôle.
* Un compte désactivé ne peut pas se connecter.

---

## US-02 — Se déconnecter

**En tant qu’utilisateur connecté,**
je veux me déconnecter,
afin de protéger mon compte.

### Critères d’acceptation

* La session est supprimée.
* L’utilisateur revient à la page de connexion.

---

## US-03 — Réinitialiser son mot de passe

**En tant qu’utilisateur,**
je veux réinitialiser mon mot de passe,
afin de récupérer l’accès à mon compte.

### Critères d’acceptation

* L’utilisateur reçoit un lien par e-mail.
* Le lien possède une durée limitée.
* Le nouveau mot de passe respecte les règles de sécurité.

---

# Epic 2 — Gestion des utilisateurs

## US-04 — Créer un compte syndic

**En tant qu’administrateur,**
je veux créer un compte syndic,
afin de lui permettre de gérer ses immeubles.

### Critères d’acceptation

* Le nom, l’e-mail et le mot de passe sont obligatoires.
* L’e-mail doit être unique.
* Le rôle `SYNDIC` est attribué.
* Le compte peut être activé ou désactivé.
* Seul l’administrateur peut réaliser cette action.

---

## US-05 — Ajouter un résident

**En tant que syndic,**
je veux ajouter un résident,
afin de l’associer à un appartement.

### Critères d’acceptation

* Le syndic saisit le nom, l’e-mail et le téléphone.
* Le rôle `RESIDENT` est attribué.
* Le résident est associé à un appartement.
* Le syndic ne peut utiliser que ses propres immeubles.
* Les données sont validées par un Form Request.

---

## US-06 — Consulter les résidents

**En tant que syndic,**
je veux consulter la liste des résidents,
afin de retrouver leurs informations.

### Critères d’acceptation

* La liste affiche le nom, l’e-mail, le téléphone et l’appartement.
* Une recherche par nom est disponible.
* Un filtre par immeuble est disponible.
* Le syndic ne voit que les résidents liés à ses immeubles.

---

# Epic 3 — Gestion des immeubles

## US-07 — Ajouter un immeuble

**En tant que syndic,**
je veux ajouter un immeuble,
afin de gérer sa copropriété.

### Critères d’acceptation

* Le nom, l’adresse et la ville sont obligatoires.
* L’immeuble est automatiquement associé au syndic connecté.
* Seul un utilisateur ayant le rôle Syndic peut créer un immeuble.
* La `BuildingPolicy` autorise l’action.

---

## US-08 — Consulter ses immeubles

**En tant que syndic,**
je veux consulter mes immeubles,
afin d’accéder rapidement à leurs informations.

### Critères d’acceptation

* Le syndic voit uniquement ses immeubles.
* La liste affiche le nom, l’adresse et le nombre d’appartements.
* Une recherche par nom ou ville est disponible.

---

## US-09 — Modifier un immeuble

**En tant que syndic,**
je veux modifier un immeuble,
afin de garder ses informations à jour.

### Critères d’acceptation

* La `BuildingPolicy` vérifie que l’immeuble appartient au syndic.
* Le nom, l’adresse et la ville peuvent être modifiés.
* Un syndic ne peut pas modifier l’immeuble d’un autre syndic.
* Les données sont validées par `UpdateBuildingRequest`.

---

## US-10 — Archiver un immeuble

**En tant que syndic,**
je veux archiver un immeuble,
afin de ne plus l’afficher dans les données actives.

### Critères d’acceptation

* L’immeuble n’est pas supprimé définitivement.
* La `BuildingPolicy` vérifie l’autorisation.
* Les anciennes charges, paiements et réclamations sont conservés.

---

# Epic 4 — Gestion des appartements

## US-11 — Ajouter un appartement

**En tant que syndic,**
je veux ajouter un appartement à un immeuble,
afin de gérer ses résidents et ses charges.

### Critères d’acceptation

* Le numéro et l’étage sont obligatoires.
* L’appartement appartient à un immeuble du syndic.
* Le numéro est unique dans le même immeuble.
* Le statut est `OCCUPE` ou `VACANT`.
* La `ApartmentPolicy` vérifie l’autorisation.

---

## US-12 — Consulter les appartements

**En tant que syndic,**
je veux consulter les appartements d’un immeuble,
afin de connaître leur situation.

### Critères d’acceptation

* La liste affiche le numéro, l’étage et le statut.
* Le résident associé est affiché.
* Le syndic ne voit que les appartements de ses immeubles.

---

## US-13 — Modifier un appartement

**En tant que syndic,**
je veux modifier un appartement,
afin de corriger ses informations.

### Critères d’acceptation

* La `ApartmentPolicy` vérifie que l’appartement appartient à un immeuble du syndic.
* Le numéro, l’étage et le statut peuvent être modifiés.
* Les données sont validées par un Form Request.

---

# Epic 5 — Gestion des copropriétaires

## US-14 — Ajouter un copropriétaire

**En tant que syndic,**
je veux ajouter un copropriétaire,
afin de l’associer à un appartement.

### Critères d’acceptation

* Le nom, l’e-mail et le téléphone sont enregistrés.
* Le copropriétaire est associé à un appartement.
* Un copropriétaire peut posséder plusieurs appartements.
* Le syndic ne peut sélectionner que ses propres immeubles.

---

## US-15 — Consulter les copropriétaires

**En tant que syndic,**
je veux consulter les copropriétaires,
afin de connaître les propriétaires des appartements.

### Critères d’acceptation

* La liste affiche les coordonnées du copropriétaire.
* Les appartements associés sont affichés.
* Une recherche par nom ou appartement est disponible.
* Le syndic ne voit que les copropriétaires de ses immeubles.

---

# Epic 6 — Gestion des charges

## US-16 — Créer une charge mensuelle

**En tant que syndic,**
je veux créer une charge mensuelle,
afin d’indiquer le montant à payer.

### Critères d’acceptation

* Le syndic sélectionne un appartement.
* Il renseigne le mois, l’année, le montant et la date limite.
* Le montant est supérieur à zéro.
* Le statut initial est `A_PAYER`.
* La `ChargePolicy` vérifie l’autorisation.
* Les données sont validées par `StoreChargeRequest`.

---

## US-17 — Consulter ses charges

**En tant que résident,**
je veux consulter mes charges,
afin de connaître les montants à payer.

### Critères d’acceptation

* Le résident voit uniquement les charges de son appartement.
* La liste affiche le mois, le montant, la date limite et le statut.
* Les statuts sont `A_PAYER`, `PAYEE` et `EN_RETARD`.
* La `ChargePolicy` contrôle l’accès.

---

## US-18 — Consulter les impayés

**En tant que syndic,**
je veux consulter les charges impayées,
afin d’identifier les résidents en retard.

### Critères d’acceptation

* La liste affiche le résident, l’appartement et le montant.
* Un filtre par mois et immeuble est disponible.
* Le total des impayés est affiché.
* Le syndic ne voit que les impayés de ses immeubles.

---

## US-19 — Mettre à jour les charges en retard

**En tant que système,**
je veux détecter les charges dépassant leur date limite,
afin de mettre à jour leur statut.

### Critères d’acceptation

* Une tâche planifiée s’exécute chaque jour.
* Une charge non payée après la date limite devient `EN_RETARD`.
* Une charge payée ne peut pas devenir en retard.

---

# Epic 7 — Gestion des paiements et reçus

## US-20 — Enregistrer un paiement

**En tant que syndic,**
je veux enregistrer un paiement,
afin de mettre à jour la charge du résident.

### Critères d’acceptation

* Le syndic sélectionne une charge non payée.
* Il renseigne la date et le mode de paiement.
* Le montant correspond au montant de la charge dans le MVP.
* La charge devient `PAYEE`.
* La `PaymentPolicy` vérifie l’autorisation.
* Une charge ne peut pas être payée deux fois.

---

## US-21 — Générer un reçu PDF

**En tant que syndic,**
je veux générer un reçu après le paiement,
afin de fournir une preuve au résident.

### Critères d’acceptation

* Le reçu possède un numéro unique.
* Il contient le nom du résident.
* Il contient l’immeuble et l’appartement.
* Il contient le montant, la date et le mode de paiement.
* Le reçu est généré avec DomPDF.
* La `ReceiptPolicy` protège le téléchargement.

---

## US-22 — Consulter ses paiements et reçus

**En tant que résident,**
je veux consulter mes paiements et télécharger mes reçus,
afin de garder une preuve des charges réglées.

### Critères d’acceptation

* Le résident voit uniquement ses paiements.
* Il peut télécharger ses propres reçus.
* Il ne peut pas modifier un paiement.
* La Policy retourne `403` pour un reçu qui ne lui appartient pas.

---

## US-23 — Annuler un paiement incorrect

**En tant que syndic,**
je veux annuler un paiement incorrect,
afin de corriger une erreur.

### Critères d’acceptation

* Le syndic saisit une justification.
* Le paiement n’est pas supprimé définitivement.
* Le reçu est marqué comme annulé.
* La charge revient au statut `A_PAYER` ou `EN_RETARD`.
* L’action est enregistrée dans un historique.

---

# Epic 8 — Gestion des réclamations

## US-24 — Créer une réclamation

**En tant que résident,**
je veux créer une réclamation,
afin de signaler un problème dans l’immeuble.

### Critères d’acceptation

* Le titre et la description sont obligatoires.
* Une image peut être ajoutée.
* La réclamation appartient au résident connecté.
* Elle est associée à son immeuble.
* Son statut initial est `NOUVELLE`.
* La `ComplaintPolicy` autorise la création.

---

## US-25 — Consulter ses réclamations

**En tant que résident,**
je veux consulter mes réclamations,
afin de suivre leur traitement.

### Critères d’acceptation

* Le résident voit uniquement ses réclamations.
* Il voit le statut, la catégorie et la réponse du syndic.
* Les statuts sont `NOUVELLE`, `EN_COURS` et `RESOLUE`.
* La Policy protège l’accès.

---

## US-26 — Traiter une réclamation

**En tant que syndic,**
je veux traiter une réclamation,
afin de résoudre le problème signalé.

### Critères d’acceptation

* Le syndic ne voit que les réclamations de ses immeubles.
* Il peut changer le statut.
* Il peut ajouter une réponse.
* Il peut modifier la catégorie et la priorité.
* Le résident reçoit une notification.
* La `ComplaintPolicy` contrôle l’accès.

---

# Epic 9 — Analyse IA des réclamations

## US-27 — Analyser une réclamation avec l’IA

**En tant que syndic,**
je veux que l’IA analyse une réclamation,
afin de faciliter son classement.

### Résultat attendu

L’IA propose :

* un résumé ;
* une catégorie ;
* une priorité.

### Catégories

* Plomberie ;
* Électricité ;
* Ascenseur ;
* Nettoyage ;
* Sécurité ;
* Parties communes ;
* Autre.

### Priorités

* Faible ;
* Normale ;
* Urgente.

### Critères d’acceptation

* L’analyse est exécutée dans un Job Laravel.
* Laravel AI SDK retourne une réponse structurée.
* Le résultat est enregistré en base de données.
* Le syndic peut modifier la proposition.
* La décision finale appartient au syndic.
* Une erreur de l’IA ne bloque pas la réclamation.
* Telescope permet de suivre l’exécution du Job.

---

# Epic 10 — Assemblées générales

## US-28 — Créer une assemblée générale

**En tant que syndic,**
je veux créer une assemblée générale,
afin d’organiser une réunion de copropriété.

### Critères d’acceptation

* Le syndic sélectionne un immeuble.
* Il renseigne la date, l’heure et le lieu.
* Il ajoute un ordre du jour.
* La `GeneralMeetingPolicy` vérifie que l’immeuble lui appartient.
* Les copropriétaires sont notifiés.

---

## US-29 — Enregistrer les participants

**En tant que syndic,**
je veux enregistrer les participants,
afin de garder une trace de leur présence.

### Critères d’acceptation

* Le syndic peut sélectionner les copropriétaires présents.
* Les absents sont identifiés.
* Les participants appartiennent à l’immeuble concerné.

---

## US-30 — Enregistrer les décisions

**En tant que syndic,**
je veux enregistrer les décisions prises,
afin de conserver l’historique de l’assemblée.

### Critères d’acceptation

* Chaque décision possède un titre.
* Une description peut être ajoutée.
* Le résultat est enregistré.
* Les décisions appartiennent à l’assemblée générale.

---

## US-31 — Générer le procès-verbal

**En tant que syndic,**
je veux générer un procès-verbal PDF,
afin de publier les décisions prises.

### Critères d’acceptation

* Le procès-verbal contient les informations de l’assemblée.
* Il contient les participants.
* Il contient les décisions.
* Il est généré avec DomPDF.
* Les résidents de l’immeuble peuvent le télécharger.
* La Policy protège le document.

---

# Epic 11 — Notifications

## US-32 — Recevoir une notification de charge

**En tant que résident,**
je veux recevoir une notification lorsqu’une charge est créée,
afin de connaître le montant à payer.

### Critères d’acceptation

* La notification contient le montant.
* Elle contient la date limite.
* Elle est enregistrée dans la base de données.
* Elle peut être envoyée par e-mail.

---

## US-33 — Recevoir un rappel de paiement

**En tant que résident,**
je veux recevoir un rappel de paiement,
afin d’éviter ou de régulariser un retard.

### Critères d’acceptation

* Le rappel concerne uniquement une charge non payée.
* Aucun rappel n’est envoyé après le paiement.
* Le Scheduler exécute automatiquement l’envoi.
* Le rappel contient le montant et la date limite.

---

## US-34 — Recevoir une notification de réclamation

**En tant que résident,**
je veux être notifié lorsque ma réclamation est mise à jour,
afin de suivre son traitement.

### Critères d’acceptation

* La notification indique le nouveau statut.
* Elle peut contenir la réponse du syndic.
* Elle appartient uniquement au résident concerné.

---

## US-35 — Recevoir une notification d’assemblée

**En tant que résident,**
je veux être informé d’une nouvelle assemblée générale,
afin de connaître la date et l’ordre du jour.

### Critères d’acceptation

* La notification contient la date, l’heure et le lieu.
* Elle est envoyée uniquement aux résidents de l’immeuble.
* Une notification est également envoyée lorsque le procès-verbal est disponible.

---

# Epic 12 — Tableaux de bord

## US-36 — Tableau de bord administrateur

**En tant qu’administrateur,**
je veux consulter les statistiques générales,
afin de suivre l’activité de la plateforme.

### Informations affichées

* nombre de syndics ;
* nombre de résidents ;
* nombre d’immeubles ;
* nombre de comptes actifs.

---

## US-37 — Tableau de bord syndic

**En tant que syndic,**
je veux consulter les statistiques de mes immeubles,
afin de connaître leur situation.

### Informations affichées

* nombre d’immeubles ;
* nombre d’appartements ;
* nombre de résidents ;
* total des charges ;
* total des paiements ;
* total des impayés ;
* nombre de réclamations ouvertes ;
* nombre de réclamations urgentes ;
* prochaines assemblées générales.

### Règle

Les statistiques concernent uniquement les immeubles du syndic connecté.

---

## US-38 — Tableau de bord résident

**En tant que résident,**
je veux consulter un résumé de ma situation,
afin d’accéder rapidement aux informations importantes.

### Informations affichées

* montant restant à payer ;
* dernières charges ;
* derniers paiements ;
* reçus disponibles ;
* réclamations en cours ;
* dernières notifications ;
* prochaine assemblée générale.

### Règle

Le résident voit uniquement ses propres informations.

---

# Epic 13 — Tests et sécurité

## ST-07 — Tester l’authentification

**En tant que développeur,**
je veux tester l’authentification avec Pest,
afin de vérifier la sécurité des comptes.

### Tests prévus

* connexion valide ;
* connexion invalide ;
* déconnexion ;
* compte désactivé ;
* réinitialisation du mot de passe.

---

## ST-08 — Tester les rôles et middlewares

**En tant que développeur,**
je veux tester les rôles,
afin d’empêcher les accès interdits.

### Tests prévus

* un résident ne peut pas accéder à l’espace syndic ;
* un syndic ne peut pas accéder à l’espace administrateur ;
* un utilisateur non connecté est redirigé vers la connexion.

---

## ST-09 — Tester les Policies

**En tant que développeur,**
je veux tester les Policies,
afin de protéger les ressources.

### Tests prévus

* un syndic peut modifier son immeuble ;
* un syndic ne peut pas modifier l’immeuble d’un autre syndic ;
* un résident peut consulter sa charge ;
* un résident ne peut pas consulter la charge d’un autre résident ;
* un résident peut télécharger son reçu ;
* un résident ne peut pas télécharger le reçu d’un autre résident ;
* un syndic peut traiter les réclamations de ses immeubles ;
* un syndic ne peut pas traiter une réclamation d’un autre immeuble.

---

## ST-10 — Tester l’intelligence artificielle

**En tant que développeur,**
je veux tester l’analyse IA sans appeler le vrai service,
afin d’obtenir des tests rapides et fiables.

### Critères d’acceptation

* Le service IA est simulé dans les tests.
* Le Job est dispatché après la création d’une réclamation.
* Le résumé, la catégorie et la priorité sont enregistrés.
* Une erreur du fournisseur est gérée.
* La réclamation reste disponible en cas d’échec.

---

# Ordre recommandé de développement

1. Installer Breeze.
2. Ajouter l’Enum des rôles.
3. Créer le middleware de rôle.
4. Créer les modèles et les migrations.
5. Créer les relations Eloquent.
6. Créer les Policies avec chaque CRUD.
7. Créer les Form Requests.
8. Développer les immeubles et appartements.
9. Développer les utilisateurs et copropriétaires.
10. Développer les charges.
11. Développer les paiements et reçus.
12. Développer les réclamations.
13. Ajouter Laravel AI SDK.
14. Développer les assemblées générales.
15. Ajouter les notifications et le Scheduler.
16. Créer les tableaux de bord.
17. Écrire les tests Pest.
18. Vérifier les Jobs et erreurs avec Telescope.
