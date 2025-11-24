<?php

namespace App\Services;

use App\Models\Cdc;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\IOFactory;

class CdcPhpWordGenerator
{
    private $phpWord;
    private $section;

    public function generate(Cdc $cdc): string
    {
        try {
            $this->phpWord = new PhpWord();

            // Configuration de la page
            $sectionStyle = [
                'marginTop' => 1134,
                'marginBottom' => 1134,
                'marginLeft' => 1134,
                'marginRight' => 1134,
                'headerHeight' => 720,
                'footerHeight' => 720,
            ];

            $this->section = $this->phpWord->addSection($sectionStyle);

            // Ajouter en-tête et pied de page
            $this->addHeaderFooter();

            // Générer le contenu
            $this->addDocumentHeader();
            $this->addInformationsGenerales($cdc);
            $this->addProcedure($cdc);
            $this->addTitre($cdc);
            $this->addMaterielLogiciel($cdc);
            $this->addPrerequis($cdc);
            $this->addDescriptifProjet($cdc);
            $this->addLivrables($cdc);
            $this->addPointsTechniques($cdc);
            $this->addValidation();

            // Sauvegarder le fichier
            $timestamp = time();
            $docxFileName = 'cdc-' . $cdc->id . '-' . $timestamp . '.docx';
            $docxPath = storage_path('app/public/cdcs/' . $docxFileName);

            $cdcsDir = storage_path('app/public/cdcs');
            if (!File::exists($cdcsDir)) {
                File::makeDirectory($cdcsDir, 0755, true);
            }

            $objWriter = IOFactory::createWriter($this->phpWord, 'Word2007');
            $objWriter->save($docxPath);

            return 'cdcs/' . $docxFileName;

        } catch (\Exception $e) {
            Log::error('Erreur génération CDC avec PhpWord', [
                'cdc_id' => $cdc->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            throw $e;
        }
    }

    private function addHeaderFooter()
    {
        // En-tête
        $header = $this->section->addHeader();
        $header->addText(
            'Centre de formation - DEV - Brief projet',
            ['name' => 'Calibri', 'size' => 9],
            ['alignment' => Jc::END]
        );

        // Pied de page
        $footer = $this->section->addFooter();
        $footer->addText(
            'FONDATION JOBTREK',
            ['name' => 'Calibri', 'size' => 8, 'color' => '666666']
        );
    }

    private function addDocumentHeader()
    {
        $this->section->addText(
            'Procédure de qualification : 88600/1/2/3 - 88614 Informaticien/ne CFC',
            ['name' => 'Calibri', 'size' => 10],
            ['alignment' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 0]
        );

        $this->section->addText(
            '(Ordo 2014/21)',
            ['name' => 'Calibri', 'size' => 10],
            ['alignment' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 120]
        );

        $this->section->addText(
            'Cahier des charges',
            ['name' => 'Calibri', 'size' => 18, 'bold' => true],
            ['alignment' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 120]
        );

        $this->section->addText(
            'Version 1.1 - ' . now()->format('d.m.Y'),
            ['name' => 'Calibri', 'size' => 10],
            ['alignment' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 240]
        );
    }

    private function addSectionTitle($title)
    {
        $this->section->addText(
            $title,
            ['name' => 'Calibri', 'size' => 15, 'color' => '17365D'],
            ['alignment' => Jc::START, 'spaceBefore' => 240, 'spaceAfter' => 120]
        );
    }

    private function addInformationsGenerales(Cdc $cdc)
    {
        $this->addSectionTitle('1 INFORMATIONS GÉNÉRALES');

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 100 * 50,
            'unit' => TblWidth::PERCENT
        ];

        $cellBgColor = ['bgColor' => 'f0f0f0'];
        $fontStyle = ['name' => 'Calibri', 'size' => 10];

        // Tableau Candidat
        $table = $this->section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Candidat', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(1500, $cellBgColor)->addText('Nom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['candidat_nom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('Prénom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['candidat_prenom'] ?? '', $fontStyle);

        // Tableau Lieu de travail
        $table = $this->section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(3000, $cellBgColor)->addText('Lieu de travail :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000)->addText($cdc->data['lieu_travail'] ?? '', $fontStyle);

        // Tableau Orientation
        $table = $this->section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(3000, $cellBgColor)->addText('Orientation :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000)->addText($cdc->data['orientation'] ?? '', $fontStyle);

        // Tableau Chef de projet
        $table = $this->section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Chef de projet', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(1500, $cellBgColor)->addText('Nom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['chef_projet_nom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('Prénom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['chef_projet_prenom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('📧 :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['chef_projet_email'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('☎ :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['chef_projet_telephone'] ?? '', $fontStyle);

        // Tableau Expert 1
        $table = $this->section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Expert 1', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(1500, $cellBgColor)->addText('Nom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert1_nom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('Prénom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert1_prenom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('📧 :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert1_email'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('☎ :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert1_telephone'] ?? '', $fontStyle);

        // Tableau Expert 2
        $table = $this->section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Expert 2', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(1500, $cellBgColor)->addText('Nom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert2_nom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('Prénom :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert2_prenom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('📧 :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert2_email'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(null, ['vMerge' => 'continue']);
        $table->addCell(1500, $cellBgColor)->addText('☎ :', $fontStyle);
        $table->addCell(4500)->addText($cdc->data['expert2_telephone'] ?? '', $fontStyle);

        // Tableau Période/Horaire/Heures
        $table = $this->section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(3600, $cellBgColor)->addText('Période de réalisation :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(5400)->addText($cdc->data['periode_realisation'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3600, $cellBgColor)->addText('Horaire de travail :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(5400)->addText($cdc->data['horaire_travail'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3600, $cellBgColor)->addText('Nombre d\'heures :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(5400)->addText($cdc->data['nombre_heures'] ?? '', $fontStyle);

        // Tableau Planning
        if (!empty($cdc->data['planning_analyse']) || !empty($cdc->data['planning_implementation'])) {
            $table = $this->section->addTable($tableStyle);
            $table->addRow();
            $table->addCell(9000, ['gridSpan' => 2, 'bgColor' => 'd0d0d0'])
                ->addText('Planning (en H ou %)', array_merge($fontStyle, ['bold' => true]), ['alignment' => Jc::CENTER]);

            if (!empty($cdc->data['planning_analyse'])) {
                $table->addRow();
                $table->addCell(3600, $cellBgColor)->addText('Analyse :', array_merge($fontStyle, ['bold' => true]));
                $table->addCell(5400)->addText($cdc->data['planning_analyse'], $fontStyle);
            }

            if (!empty($cdc->data['planning_implementation'])) {
                $table->addRow();
                $table->addCell(3600, $cellBgColor)->addText('Implémentation :', array_merge($fontStyle, ['bold' => true]));
                $table->addCell(5400)->addText($cdc->data['planning_implementation'], $fontStyle);
            }

            if (!empty($cdc->data['planning_tests'])) {
                $table->addRow();
                $table->addCell(3600, $cellBgColor)->addText('Tests :', array_merge($fontStyle, ['bold' => true]));
                $table->addCell(5400)->addText($cdc->data['planning_tests'], $fontStyle);
            }

            if (!empty($cdc->data['planning_documentation'])) {
                $table->addRow();
                $table->addCell(3600, $cellBgColor)->addText('Documentations :', array_merge($fontStyle, ['bold' => true]));
                $table->addCell(5400)->addText($cdc->data['planning_documentation'], $fontStyle);
            }
        }
    }

    private function addProcedure(Cdc $cdc)
    {
        $this->section->addPageBreak();
        $this->addSectionTitle('2 PROCÉDURE');

        $procedure = $cdc->data['procedure'] ?? '';
        $procedureItems = $procedure ? explode("\n", $procedure) : [];

        if (count($procedureItems) > 0) {
            foreach ($procedureItems as $item) {
                if (trim($item)) {
                    $this->section->addListItem(
                        trim($item),
                        0,
                        ['name' => 'Calibri', 'size' => 10],
                        ['spaceAfter' => 80]
                    );
                }
            }
        }
    }

    private function addTitre(Cdc $cdc)
    {
        $this->addSectionTitle('3 TITRE');
        $this->section->addText($cdc->title, ['name' => 'Calibri', 'size' => 10]);
    }

    private function addMaterielLogiciel(Cdc $cdc)
    {
        if (!empty($cdc->data['materiel_logiciel'])) {
            $this->addSectionTitle('4 MATÉRIEL ET LOGICIEL À DISPOSITION');

            $materiel = $cdc->data['materiel_logiciel'];
            $materielItems = explode("\n", $materiel);

            foreach ($materielItems as $item) {
                if (trim($item)) {
                    $this->section->addListItem(
                        trim($item),
                        0,
                        ['name' => 'Calibri', 'size' => 10],
                        ['spaceAfter' => 80]
                    );
                }
            }
        }
    }

    private function addPrerequis(Cdc $cdc)
    {
        if (!empty($cdc->data['prerequis'])) {
            $this->addSectionTitle('5 PRÉREQUIS');

            $prerequis = $cdc->data['prerequis'];
            $prerequisItems = explode("\n", $prerequis);

            foreach ($prerequisItems as $item) {
                if (trim($item)) {
                    $this->section->addListItem(
                        trim($item),
                        0,
                        ['name' => 'Calibri', 'size' => 10],
                        ['spaceAfter' => 80]
                    );
                }
            }
        }
    }

    private function addDescriptifProjet(Cdc $cdc)
    {
        $this->addSectionTitle('6 DESCRIPTIF DU PROJET');
        $this->section->addText(
            $cdc->data['descriptif_projet'] ?? 'Non renseigné',
            ['name' => 'Calibri', 'size' => 10]
        );
    }

    private function addLivrables(Cdc $cdc)
    {
        if (!empty($cdc->data['livrables'])) {
            $this->addSectionTitle('7 LIVRABLES');

            $this->section->addText(
                'Le candidat est responsable de livrer à son chef de projet et aux deux experts :',
                ['name' => 'Calibri', 'size' => 10],
                ['spaceAfter' => 120]
            );

            $livrables = $cdc->data['livrables'];
            $livrablesItems = explode("\n", $livrables);

            foreach ($livrablesItems as $item) {
                if (trim($item)) {
                    $this->section->addListItem(
                        trim($item),
                        0,
                        ['name' => 'Calibri', 'size' => 10],
                        ['spaceAfter' => 80]
                    );
                }
            }
        }
    }

    private function addPointsTechniques(Cdc $cdc)
    {
        $customFields = collect($cdc->data)->filter(function($value, $key) {
            return !in_array($key, [
                    'candidat_nom', 'candidat_prenom', 'lieu_travail', 'orientation',
                    'chef_projet_nom', 'chef_projet_prenom', 'chef_projet_email', 'chef_projet_telephone',
                    'expert1_nom', 'expert1_prenom', 'expert1_email', 'expert1_telephone',
                    'expert2_nom', 'expert2_prenom', 'expert2_email', 'expert2_telephone',
                    'periode_realisation', 'horaire_travail', 'nombre_heures',
                    'planning_analyse', 'planning_implementation', 'planning_tests', 'planning_documentation',
                    'procedure', 'titre_projet', 'materiel_logiciel', 'prerequis', 'descriptif_projet', 'livrables',
                    'date_debut', 'date_fin', 'heure_matin_debut', 'heure_matin_fin', 'heure_aprem_debut', 'heure_aprem_fin'
                ]) && !empty($value);
        });

        if ($customFields->count() > 0) {
            $this->section->addPageBreak();
            $this->addSectionTitle('8 POINTS TECHNIQUES ÉVALUÉS SPÉCIFIQUES AU PROJET');

            $this->section->addText(
                'La grille d\'évaluation définit les critères généraux selon lesquels le travail du candidat sera évalué (documentation, journal de travail, respect des normes, qualité, …).',
                ['name' => 'Calibri', 'size' => 10],
                ['spaceAfter' => 120]
            );

            $this->section->addText(
                'En plus de cela, le travail sera évalué sur les points spécifiques suivants :',
                ['name' => 'Calibri', 'size' => 10],
                ['spaceAfter' => 120]
            );

            $counter = 1;
            foreach ($customFields as $key => $value) {
                $this->section->addText(
                    $counter . '. ' . ucfirst(str_replace('_', ' ', $key)),
                    ['name' => 'Calibri', 'size' => 10, 'bold' => true],
                    ['spaceAfter' => 60]
                );
                $this->section->addText(
                    $value,
                    ['name' => 'Calibri', 'size' => 10],
                    ['spaceAfter' => 120]
                );
                $counter++;
            }
        }
    }

    private function addValidation()
    {
        $this->section->addPageBreak();
        $this->addSectionTitle('9 VALIDATION');

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 100 * 50,
            'unit' => TblWidth::PERCENT
        ];

        $cellBgColor = ['bgColor' => 'f0f0f0'];
        $fontStyle = ['name' => 'Calibri', 'size' => 10];

        $table = $this->section->addTable($tableStyle);

        $table->addRow();
        $table->addCell(3500, $cellBgColor)->addText('Lu et approuvé le :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(5500, $cellBgColor)->addText('Signature :', array_merge($fontStyle, ['bold' => true]));

        $table->addRow(800);
        $table->addCell(3500)->addText('Candidat :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);

        $table->addRow(800);
        $table->addCell(3500)->addText('Expert n°1 :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);

        $table->addRow(800);
        $table->addCell(3500)->addText('Expert n°2 :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);

        $table->addRow(800);
        $table->addCell(3500)->addText('Chef de projet :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);
    }
}
