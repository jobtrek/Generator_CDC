<?php

namespace App\Services;

use Carbon\Carbon;

class DateTimeFormatter
{
    public function buildPeriodeRealisation(string $dateDebut, string $dateFin): string
    {
        $start = Carbon::parse($dateDebut)->locale('fr')->isoFormat('D MMMM YYYY');
        $end = Carbon::parse($dateFin)->locale('fr')->isoFormat('D MMMM YYYY');

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
