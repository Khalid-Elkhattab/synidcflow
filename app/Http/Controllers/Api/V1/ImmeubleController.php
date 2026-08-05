<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreImmeubleRequest;
use App\Http\Requests\Api\V1\UpdateImmeubleRequest;
use App\Http\Resources\ImmeubleResource;
use App\Models\Immeuble;
use App\Models\Residence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImmeubleController extends Controller
{
    /**
     * Liste des immeubles d'une résidence, filtrée par rôle.
     */
    public function index(Request $request, Residence $residence): JsonResponse
    {
        $this->authorize('view', $residence);

        $user = $request->user();

        $immeubles = Immeuble::query()
            ->where('residence_id', $residence->id)
            ->when(
                $user->role === UserRole::Resident,
                fn ($query) => $query->whereHas(
                    'appartements',
                    fn ($query) => $query->where('resident_id', $user->id)
                )
            )
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Immeubles récupérés avec succès.',
            'data' => [
                'immeubles' => ImmeubleResource::collection($immeubles),
            ],
        ]);
    }

    /**
     * Crée un immeuble dans la résidence courante.
     */
    public function store(StoreImmeubleRequest $request, Residence $residence): JsonResponse
    {
        $immeuble = Immeuble::create([
            ...$request->validated(),
            'residence_id' => $residence->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Immeuble créé avec succès.',
            'data' => [
                'immeuble' => new ImmeubleResource($immeuble),
            ],
        ], 201);
    }

    /**
     * Affiche un immeuble précis.
     */
    public function show(Residence $residence, Immeuble $immeuble): JsonResponse
    {
        $this->authorize('view', $immeuble);

        return response()->json([
            'success' => true,
            'message' => 'Immeuble récupéré avec succès.',
            'data' => [
                'immeuble' => new ImmeubleResource($immeuble),
            ],
        ]);
    }

    /**
     * Met à jour un immeuble.
     */
    public function update(UpdateImmeubleRequest $request, Residence $residence, Immeuble $immeuble): JsonResponse
    {
        $immeuble->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Immeuble mis à jour avec succès.',
            'data' => [
                'immeuble' => new ImmeubleResource($immeuble),
            ],
        ]);
    }

    /**
     * Supprime (doucement) un immeuble.
     */
    public function destroy(Residence $residence, Immeuble $immeuble): JsonResponse
    {
        $this->authorize('delete', $immeuble);

        $immeuble->delete();

        return response()->json([
            'success' => true,
            'message' => 'Immeuble supprimé avec succès.',
            'data' => null,
        ]);
    }
}
