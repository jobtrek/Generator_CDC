<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\File;

trait CleansCdcFiles
{
    private static function deleteCdcFilesFor(int $cdcId): void
    {
        $cdcDir = storage_path('app/public/cdc');

        if (! File::isDirectory($cdcDir)) {
            return;
        }

        $files = File::glob($cdcDir.'/cdc-'.$cdcId.'-*.docx');

        foreach ($files as $file) {
            File::delete($file);
        }

        $file = $cdcDir.'/cdc-'.$cdcId.'.docx';
        if (File::exists($file)) {
            File::delete($file);
        }
    }
}
