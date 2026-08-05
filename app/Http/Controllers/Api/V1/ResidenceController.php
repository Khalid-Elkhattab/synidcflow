<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreResidenceRequest;
use App\Http\Requests\Api\V1\UpdateResidenceRequest;
use App\Http\Resources\ResidenceResource;
use App\Models\Residence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResidenceController extends Controller
{
    /**
     * Liste filtrée par rôle : admin = toutes, syndic = les siennes,
     * résident = déduites de ses appartements.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $residences = (match ($user->role) {
            UserRole::Admin => Residence::query()->latest(),
            UserRole::Syndic => Residence::query()
                ->where('syndic_id', $user->id)
                ->latest(),
            UserRole::Resident => Residence::query()
                ->whereHas(
                    'immeubles.appartements',
                    fn ($query) => $query->where('resident_id', $user->id)
                )
                ->latest(),
        })
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Résidences récupérées avec succès.',
            'data' => [
                'residences' => ResidenceResource::collection($residences),
            ],
        ]);
    }

    /**
     * Syndic : création pour lui-même. Admin : création pour un syndic choisi.
     */
    public function store(StoreResidenceRequest $request): JsonResponse
    {
        $user = $request->user();

        $residence = Residence::create([
            ...$request->validated(),
            'syndic_id' => $user->role === UserRole::Admin
                ? $request->validated('syndic_id')
                : $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Résidence créée avec succès.',
            'data' => [
                'residence' => new ResidenceResource($residence),
            ],
        ], 201);
    }

    /**
     * Affiche une résidence précise.
     */
    public function show(Residence $residence): JsonResponse
    {
        $this->authorize('view', $residence);

        return response()->json([
            'success' => true,
            'message' => 'Résidence récupérée avec succès.',
            'data' => [
                'residence' => new ResidenceResource($residence),
            ],
        ]);
    }

    /**
     * Met à jour une résidence.
     */
    public function update(UpdateResidenceRequest $request, Residence $residence): JsonResponse
    {
        $residence->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Résidence mise à jour avec succès.',
            'data' => [
                'residence' => new ResidenceResource($residence),
            ],
        ]);
    }

    /**
     * Supprime (doucement) une résidence.
     */
    public function destroy(Residence $residence): JsonResponse
    {
        $this->authorize('delete', $residence);

        $residence->delete();

        return response()->json([
            'success' => true,
            'message' => 'Résidence supprimée avec succès.',
            'data' => null,
        ]);
    }
}
