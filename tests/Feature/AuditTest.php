<?php

use App\Agents\AnalyseReclamationAgent;
use App\Enums\AuditDecision;
use App\Enums\AuditStatut;
use App\Models\Audit;
use App\Models\Charge;
use App\Models\Conversation;
use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function createReclamationWithAudit(?User $resident = null, ?array $chain = null, bool $withAudit = true): array
{
    if ($withAudit === false) {
        return createReclamationSansAudit($resident, $chain);
    }

    $chain ??= createReclamationSansAudit($resident, $chain);

    $audit = Audit::create([
        'reclamation_id' => $chain['reclamation']->id,
        'charges_snapshot' => $chain['reclamation']->appartement->charges->map(fn ($charge) => [
            'id' => $charge->id,
            'libelle' => $charge->libelle,
            'montant' => $charge->montant,
            'date_echeance' => $charge->date_echeance?->toDateString(),
            'statut' => $charge->statut->value,
        ])->values()->all(),
        'decision' => AuditDecision::Accepted,
        'resultat' => ['justification' => 'Test', 'points' => []],
        'statut' => AuditStatut::Completed,
        'modele_ia' => 'groq/test',
        'traite_at' => now(),
    ]);

    return [...$chain, 'audit' => $audit];
}

function createReclamationSansAudit(?User $resident = null, ?array $chain = null): array
{
    $chain ??= createChain();
    $resident ??= User::factory()->create();
    $chain['appartement']->update(['resident_id' => $resident->id]);

    Charge::create([
        'appartement_id' => $chain['appartement']->id,
        'libelle' => 'Charges de copropriété',
        'montant' => 250.00,
        'date_echeance' => '2026-08-31',
    ]);

    $reclamation = Reclamation::create([
        'resident_id' => $resident->id,
        'appartement_id' => $chain['appartement']->id,
        'titre' => 'Charge payée à tort',
        'description' => 'J\'ai payé deux fois la charge de copropriété.',
    ]);

    return [...$chain, 'resident' => $resident, 'reclamation' => $reclamation];
}

function fakeAiReclamation(array $resultat): void
{
    config()->set('ai.conversations.generate_title', false);

    AnalyseReclamationAgent::fake([
        [
            'decision' => $resultat['decision'],
            'resultat' => [
                'justification' => $resultat['justification'],
                'points' => $resultat['points'] ?? [],
            ],
        ],
    ]);
}

describe('audits', function () {
    test('un invité n\'accède jamais aux audits (401)', function () {
        $chain = createReclamationWithAudit();

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")->assertUnauthorized();
        $this->getJson('/api/v1/audits')->assertUnauthorized();
        $this->getJson("/api/v1/audits/{$chain['audit']->id}")->assertUnauthorized();
        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}/audits")->assertUnauthorized();
    });

    test('un syndic déclenche une analyse et obtient un audit complété', function () {
        fakeAiReclamation(['decision' => 'accepted', 'justification' => 'Double paiement constaté']);
        $chain = createReclamationWithAudit(withAudit: false);

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")
            ->assertAccepted();

        $audit = $chain['reclamation']->audits()->latest('id')->first();

        expect($audit)->not->toBeNull()
            ->and($audit->statut)->toBe(AuditStatut::Completed)
            ->and($audit->decision)->toBe(AuditDecision::Accepted)
            ->and($audit->modele_ia)->not->toBeNull()
            ->and($audit->traite_at)->not->toBeNull();
    });

    test('un résident ne déclenche jamais d\'analyse (403)', function () {
        $chain = createReclamationWithAudit();

        Sanctum::actingAs($chain['resident']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")
            ->assertForbidden();
    });

    test('le snapshot des charges est figé et inchangé après modification', function () {
        fakeAiReclamation(['decision' => 'accepted', 'justification' => 'Double paiement constaté']);
        $chain = createReclamationSansAudit();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")
            ->assertAccepted();

        $audit = $chain['reclamation']->audits()->latest('id')->first();
        $charge = $chain['reclamation']->appartement->charges->first();
        $charge->update(['montant' => 999.00]);

        expect($audit->charges_snapshot)->toHaveCount(1)
            ->and($audit->charges_snapshot[0]['montant'])->toBe('250.00');
    });

    test('multi-analyses : plusieurs audits sont autorisés pour une même réclamation', function () {
        fakeAiReclamation(['decision' => 'accepted', 'justification' => 'Double paiement constaté']);
        $chain = createReclamationSansAudit();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")->assertAccepted();
        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")->assertAccepted();

        expect($chain['reclamation']->audits()->count())->toBe(2);
    });

    test('panne IA : statut failed, réclamation intacte, relance possible', function () {
        config()->set('ai.conversations.generate_title', false);
        $chain = createReclamationSansAudit();

        AnalyseReclamationAgent::fake(fn () => throw new RuntimeException('Panne fournisseur Groq.'));

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")
            ->assertAccepted();

        $audit = $chain['reclamation']->audits()->latest('id')->first();

        expect($audit->statut)->toBe(AuditStatut::Failed)
            ->and($chain['reclamation']->fresh())->not->toBeNull();

        fakeAiReclamation(['decision' => 'rejected', 'justification' => 'Réclamation non fondée']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")
            ->assertAccepted();

        $latest = $chain['reclamation']->audits()->latest('id')->first();

        expect($latest->statut)->toBe(AuditStatut::Completed)
            ->and($latest->decision)->toBe(AuditDecision::Rejected);
    });

    test('une conversation est créée et associée 1--1 à l\'audit', function () {
        fakeAiReclamation(['decision' => 'accepted', 'justification' => 'Double paiement constaté']);
        $chain = createReclamationSansAudit();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")
            ->assertAccepted();

        $audit = $chain['reclamation']->audits()->latest('id')->first();
        $conversation = Conversation::where('audit_id', $audit->id)->first();

        expect($conversation)->not->toBeNull()
            ->and(Conversation::where('audit_id', $audit->id)->count())->toBe(1)
            ->and($audit->conversation)->not->toBeNull();
    });

    test('le résident ne voit jamais un audit (403)', function () {
        $chain = createReclamationWithAudit();

        Sanctum::actingAs($chain['resident']);

        $this->getJson('/api/v1/audits')->assertForbidden();
        $this->getJson("/api/v1/audits/{$chain['audit']->id}")->assertForbidden();
        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}/audits")->assertForbidden();
    });

    test('le syndic ne voit pas les audits d\'un autre syndic (403)', function () {
        $chain = createReclamationWithAudit();
        $otherSyndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($otherSyndic);

        $this->getJson('/api/v1/audits')
            ->assertOk()
            ->assertJsonCount(0, 'data.audits');
        $this->getJson("/api/v1/audits/{$chain['audit']->id}")->assertForbidden();
        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}/audits")->assertForbidden();
    });

    test('une analyse réussie est exposée via l\'API au syndic', function () {
        fakeAiReclamation(['decision' => 'accepted', 'justification' => 'Double paiement constaté']);
        $chain = createReclamationSansAudit();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/reclamations/{$chain['reclamation']->id}/analyser")
            ->assertAccepted();

        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}/audits")
            ->assertOk()
            ->assertJsonCount(1, 'data.audits')
            ->assertJsonPath('data.audits.0.statut', 'completed');
    });
});
