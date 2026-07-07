<?php

namespace App\Jobs;

use App\Models\Cdc;
use App\Models\User;
use App\Notifications\CdcDocxReadyNotification;
use App\Services\CdcPhpWordGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateCdcDocxJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public readonly Cdc $cdc,
        public readonly User $user,
    ) {}

    public function handle(CdcPhpWordGenerator $generator): void
    {
        $filePath = $generator->generate($this->cdc);

        $this->cdc->update(['docx_path' => $filePath]);

        $this->user->notify(new CdcDocxReadyNotification($this->cdc));
    }

    public function failed(\Throwable $e): void
    {
        Log::error('Échec génération DOCX en arrière-plan', [
            'cdc_id'  => $this->cdc->id,
            'user_id' => $this->user->id,
            'error'   => $e->getMessage(),
        ]);
    }
}
