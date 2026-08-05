<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AssignResidentRequest;
use App\Http\Requests\Api\V1\StoreAppartementRequest;
use App\Http\Requests\Api\V1\UpdateAppartementRequest;
use App\Http\Resources\AppartementResource;
use App\Models\Appartement;
use App\Models\Immeuble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppartementController extends Controller
{
    /**
     * Liste des appartements d'un immeuble, filtrée par rôle.
     */
    public function index(Request $request, Immeuble $immeuble): JsonResponse
    {
        $this->authorize('view', $immeuble);

        $user = $request->user();

        $appartements = Appartement::query()
            ->where('immeuble_id', $immeuble->id)
            ->when(
                $user->role === UserRole::Resident,
                fn ($query) => $query->where('resident_id', $user->id)
            )
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Appartements récupérés avec succès.',
            'data' => [
                'appartements' => AppartementResource::collection($appartements),
            ],
        ]);
    }

    /**
     * Crée un appartement dans l'immeuble courant.
     */
    public function store(StoreAppartementRequest $request, Immeuble $immeuble): JsonResponse
    {
        $appartement = Appartement::create([
            ...$request->validated(),
            'immeuble_id' => $immeuble->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appartement créé avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement),
            ],
        ], 201);
    }

    /**
     * Affiche un appartement précis.
     */
    public function show(Immeuble $immeuble, Appartement $appartement): JsonResponse
    {
        $this->authorize('view', $appartement);

        return response()->json([
            'success' => true,
            'message' => 'Appartement récupéré avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement),
            ],
        ]);
    }

    /**
     * Met à jour un appartement.
     */
    public function update(UpdateAppartementRequest $request, Immeuble $immeuble, Appartement $appartement): JsonResponse
    {
        $appartement->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Appartement mis à jour avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement),
            ],
        ]);
    }

    /**
     * Supprime (doucement) un appartement.
     */
    public function destroy(Immeuble $immeuble, Appartement $appartement): JsonResponse
    {
        $this->authorize('delete', $appartement);

        $appartement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appartement supprimé avec succès.',
            'data' => null,
        ]);
    }

    /**
     * Affecte un résident existant à un appartement vacant (transaction).
     */
    public function assign(AssignResidentRequest $request, Appartement $appartement): JsonResponse
    {
        DB::transaction(function () use ($request, $appartement): void {
            $appartement->update([
                'resident_id' => $request->validated('resident_id'),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Résident affecté avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement->fresh()),
            ],
        ]);
    }

    /**
     * Désaffecte le résident courant de l'appartement.
     */
    public function deassign(Request $request, Appartement $appartement): JsonResponse
    {
        $this->authorize('assign', $appartement);

        $appartement->update([
            'resident_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Résident désaffecté avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement->fresh()),
            ],
        ]);
    }
}
