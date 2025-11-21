<?php

namespace App\Services;

use App\Models\Cdc;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CdcPandocGenerator
{
    /**
     * Génère un fichier .docx à partir d'un CDC
     */
    public function generate(Cdc $cdc): string
    {
        try {
            $html = view('cdcs.word-template', ['cdc' => $cdc])->render();

            $tempDir = storage_path('app/temp');
            $timestamp = time();
            $htmlFile = $tempDir . '/cdc-' . $cdc->id . '-' . $timestamp . '.html';
            $docxFileName = 'cdc-' . $cdc->id . '-' . $timestamp . '.docx';
            $docxPath = storage_path('app/public/cdcs/' . $docxFileName);

            $cdcsDir = storage_path('app/public/cdcs');
            if (!File::exists($cdcsDir)) {
                File::makeDirectory($cdcsDir, 0755, true);
            }

            File::put($htmlFile, $html);

            $command = sprintf(
                'pandoc "%s" -o "%s" --from html --to docx 2>&1',
                $htmlFile,
                $docxPath
            );

            exec($command, $output, $returnCode);
            if (File::exists($htmlFile)) {
                File::delete($htmlFile);
            }

            if ($returnCode !== 0 || !File::exists($docxPath)) {
                Log::error('Erreur Pandoc', [
                    'command' => $command,
                    'output' => $output,
                    'return_code' => $returnCode
                ]);
                throw new \Exception('Erreur lors de la génération du document Word avec Pandoc');
            }

            return 'cdcs/' . $docxFileName;

        } catch (\Exception $e) {
            Log::error('Erreur génération CDC avec Pandoc', [
                'cdc_id' => $cdc->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}
