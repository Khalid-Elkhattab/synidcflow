<?php

namespace App\Jobs;

use App\Models\Reclamation;
use App\Services\AnalyseReclamationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class AnalyserReclamationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Le nombre de tentatives avant abandon du job.
     */
    public int $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(public Reclamation $reclamation)
    {
        //
    }

    /**
     * Exécute l'analyse IA de la réclamation.
     */
    public function handle(AnalyseReclamationService $service): void
    {
        $service->analyser($this->reclamation);
    }

    /**
     * Une panne IA ne doit jamais faire disparaître la réclamation.
     */
    public function failed(?Throwable $e): void
    {
        logger()->error('Échec de l\'analyse IA de la réclamation '.$this->reclamation->id, [
            'exception' => $e,
        ]);
    }
}
