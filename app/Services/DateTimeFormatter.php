<?php

namespace App\Services;

use Carbon\Carbon;

class DateTimeFormatter
{
    public function buildPeriodeRealisation(string $dateDebut, string $dateFin): string
    {
        $locale = config('app.locale');

        $start = Carbon::parse($dateDebut)->locale($locale)->isoFormat('D MMMM YYYY');
        $end = Carbon::parse($dateFin)->locale($locale)->isoFormat('D MMMM YYYY');

        return "Du {$start} au {$end}";
    }

    public function buildHoraireTravail(
        string $heureMatinDebut,
        string $heureMatinFin,
        string $heureApremDebut,
        string $heureApremFin
    ): string
    {
        return "{$heureMatinDebut} — {$heureMatinFin}, {$heureApremDebut} — {$heureApremFin}";
    }
}
