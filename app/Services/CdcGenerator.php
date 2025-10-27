<?php

namespace App\Services;

use App\Models\Cdc;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Shared\Converter;

class CdcGenerator
{
    private PhpWord $phpWord;
    private $section;

    public function __construct()
    {
        $this->phpWord = new PhpWord();
        $this->phpWord->setDefaultFontName('Arial');
        $this->phpWord->setDefaultFontSize(11);
        $this->defineStyles();
    }

    public function generate(Cdc $cdc): string
    {
        $this->section = $this->phpWord->addSection([
            'marginTop' => 1440,
            'marginBottom' => 1440,
            'marginLeft' => 1440,
            'marginRight' => 1440,
        ]);

        $this->addOfficialHeader();
        $this->addOfficialFooter();

        $this->addSection1InformationsGenerales($cdc);
        $this->addSection2Procedure();
        $this->addSection3Titre($cdc);
        $this->addSection4MaterielLogiciel($cdc);
        $this->addSection5Prerequis($cdc);
        $this->addSection6DescriptifProjet($cdc);
        $this->addSection7Livrables($cdc);
        $this->addSection8PointsTechniques($cdc);
        $this->addSection9Validation($cdc);

        return $this->save($cdc);
    }

    private function defineStyles(): void
    {
        $this->phpWord->addTitleStyle(1, [
            'name' => 'Arial',
            'size' => 14,
            'bold' => true,
        ], [
            'spaceBefore' => 240,
            'spaceAfter' => 120,
        ]);
    }

    private function addOfficialHeader(): void
    {
        $header = $this->section->addHeader();
        $header->addText(
            'Procédure de qualification : 88600/1/2/3 - 88614 Informaticien/ne CFC (Ordo 2014/21)',
            ['size' => 9]
        );
        $header->addText(
            'Cahier des charges',
            ['size' => 9, 'bold' => true]
        );
    }

    private function addOfficialFooter(): void
    {
        $footer = $this->section->addFooter();

        $table = $footer->addTable([
            'borderSize' => 0,
            'width' => 100 * 50,
            'unit' => 'pct'
        ]);

        $table->addRow();
        $cell1 = $table->addCell(3000);
        $cell1->addPreserveText('Page {PAGE} sur {NUMPAGES}', ['size' => 8]);

        $cell2 = $table->addCell(6500);
        $cell2->addText(
            'Version 1.1-ordo2k104-21 (' . now()->format('d.m.Y') . ')',
            ['size' => 8],
            ['alignment' => Jc::END]
        );

        $table->addRow();
        $table->addCell(9500)->addText(
            '© I-CQ VD 2017/' . now()->format('y'),
            ['size' => 8],
            ['alignment' => Jc::END]
        );
    }

    private function addSection1InformationsGenerales(Cdc $cdc): void
    {
        $this->section->addTitle('1 INFORMATIONS GENERALES', 1);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 100 * 50,
            'unit' => 'pct'
        ];

        $table = $this->section->addTable($tableStyle);

        $table->addRow();
        $table->addCell(2500)->addText('Candidat:', ['bold' => true]);
        $table->addCell(3500)->addText('Nom:');
        $table->addCell(3000)->addText($this->getValue($cdc, 'candidat_nom'));
        $table->addCell(1500)->addText('Prénom:');
        $table->addCell(2000)->addText($this->getValue($cdc, 'candidat_prenom'));

        $table->addRow();
        $table->addCell(12500, ['gridSpan' => 5])->addText('');

        $table->addRow();
        $table->addCell(3000)->addText('Lieu de travail:', ['bold' => true]);
        $table->addCell(9500, ['gridSpan' => 4])->addText($this->getValue($cdc, 'lieu_travail'));

        if ($orientation = $this->getValue($cdc, 'orientation')) {
            $table->addRow();
            $table->addCell(3000)->addText('Orientation:', ['bold' => true]);
            $table->addCell(9500, ['gridSpan' => 4])->addText($orientation);
        }

        $table->addRow();
        $table->addCell(2500)->addText('Chef de projet:', ['bold' => true]);
        $table->addCell(3500)->addText('Nom:');
        $table->addCell(3000)->addText($this->getValue($cdc, 'chef_projet_nom'));
        $table->addCell(1500)->addText('Prénom:');
        $table->addCell(2000)->addText($this->getValue($cdc, 'chef_projet_prenom'));

        $table->addRow();
        $table->addCell(2500)->addText('');
        $table->addCell(3500)->addText('📧:');
        $table->addCell(3000)->addText($this->getValue($cdc, 'chef_projet_email'));
        $table->addCell(1500)->addText('☎:');
        $table->addCell(2000)->addText($this->getValue($cdc, 'chef_projet_telephone'));

        $table->addRow();
        $table->addCell(2500)->addText('Expert 1:', ['bold' => true]);
        $table->addCell(3500)->addText('Nom:');
        $table->addCell(3000)->addText($this->getValue($cdc, 'expert1_nom'));
        $table->addCell(1500)->addText('Prénom:');
        $table->addCell(2000)->addText($this->getValue($cdc, 'expert1_prenom'));

        $table->addRow();
        $table->addCell(2500)->addText('');
        $table->addCell(3500)->addText('📧:');
        $table->addCell(3000)->addText($this->getValue($cdc, 'expert1_email'));
        $table->addCell(1500)->addText('☎:');
        $table->addCell(2000)->addText($this->getValue($cdc, 'expert1_telephone'));

        $table->addRow();
        $table->addCell(2500)->addText('Expert 2:', ['bold' => true]);
        $table->addCell(3500)->addText('Nom:');
        $table->addCell(3000)->addText($this->getValue($cdc, 'expert2_nom'));
        $table->addCell(1500)->addText('Prénom:');
        $table->addCell(2000)->addText($this->getValue($cdc, 'expert2_prenom'));

        $table->addRow();
        $table->addCell(2500)->addText('');
        $table->addCell(3500)->addText('📧:');
        $table->addCell(3000)->addText($this->getValue($cdc, 'expert2_email'));
        $table->addCell(1500)->addText('☎:');
        $table->addCell(2000)->addText($this->getValue($cdc, 'expert2_telephone'));

        $table->addRow();
        $table->addCell(4000)->addText('Période de réalisation :', ['bold' => true]);
        $table->addCell(8500, ['gridSpan' => 4])->addText($this->getValue($cdc, 'periode_realisation'));

        $table->addRow();
        $table->addCell(4000)->addText('Horaire de travail :', ['bold' => true]);
        $table->addCell(8500, ['gridSpan' => 4])->addText($this->getValue($cdc, 'horaire_travail'));

        $table->addRow();
        $table->addCell(4000)->addText('Nombre d\'heures :', ['bold' => true]);
        $table->addCell(8500, ['gridSpan' => 4])->addText($this->getValue($cdc, 'nombre_heures'));

        if ($this->getValue($cdc, 'planning_analyse') || $this->getValue($cdc, 'planning_implementation')) {
            $table->addRow();
            $table->addCell(4000, ['gridSpan' => 1, 'vMerge' => 'restart'])->addText('Planning (en H ou %):', ['bold' => true]);
            $table->addCell(4000)->addText('Analyse:');
            $table->addCell(4500, ['gridSpan' => 3])->addText($this->getValue($cdc, 'planning_analyse'));

            $table->addRow();
            $table->addCell(4000, ['vMerge' => 'continue']);
            $table->addCell(4000)->addText('Implémentation:');
            $table->addCell(4500, ['gridSpan' => 3])->addText($this->getValue($cdc, 'planning_implementation'));

            $table->addRow();
            $table->addCell(4000, ['vMerge' => 'continue']);
            $table->addCell(4000)->addText('Tests:');
            $table->addCell(4500, ['gridSpan' => 3])->addText($this->getValue($cdc, 'planning_tests'));

            $table->addRow();
            $table->addCell(4000, ['vMerge' => 'continue']);
            $table->addCell(4000)->addText('Documentations:');
            $table->addCell(4500, ['gridSpan' => 3])->addText($this->getValue($cdc, 'planning_documentation'));
        }

        $this->section->addTextBreak(1);
    }

    private function addSection2Procedure(): void
    {
        $this->section->addTitle('2 PROCÉDURE', 1);

        $points = [
            'Le candidat réalise un travail personnel sur la base d\'un cahier des charges reçu le 1er jour.',
            'Le cahier des charges est approuvé par les deux experts. Il est en outre présenté, commenté et discuté avec le candidat. Par sa signature, le candidat accepte le travail proposé.',
            'Le candidat a connaissance de la feuille d\'évaluation avant de débuter le travail.',
            'Le candidat est entièrement responsable de la sécurité de ses données.',
            'En cas de problèmes graves, le candidat avertit au plus vite les deux experts et son CdP.',
            'Le candidat a la possibilité d\'obtenir de l\'aide, mais doit le mentionner dans son dossier.',
            'A la fin du délai imparti pour la réalisation du TPI, le candidat doit transmettre par courrier électronique le dossier de projet aux deux experts et au chef de projet. En parallèle, une copie papier du rapport doit être fournie sans délai en trois exemplaires (L\'un des deux experts peut demander à ne recevoir que la version électronique du dossier). Cette dernière doit être en tout point identique à la version électronique.'
        ];

        foreach ($points as $point) {
            $this->section->addListItem($point, 0, ['size' => 11], ['indentation' => ['left' => 360]]);
        }

        $this->section->addTextBreak(1);
    }

    private function addSection3Titre(Cdc $cdc): void
    {
        $this->section->addTitle('3 TITRE', 1);
        $this->section->addText($this->getValue($cdc, 'titre_projet', $cdc->title));
        $this->section->addTextBreak(1);
    }

    private function addSection4MaterielLogiciel(Cdc $cdc): void
    {
        $this->section->addTitle('4 MATÉRIEL ET LOGICIEL À DISPOSITION', 1);

        $materiel = $this->getValue($cdc, 'materiel_logiciel', '');

        if (!empty($materiel)) {
            $items = explode("\n", $materiel);
            foreach ($items as $item) {
                $item = trim($item);
                if (!empty($item)) {
                    $this->section->addListItem($item, 0, ['size' => 11]);
                }
            }
        } else {
            $this->section->addText('À définir');
        }

        $this->section->addTextBreak(1);
    }

    private function addSection5Prerequis(Cdc $cdc): void
    {
        $this->section->addTitle('5 PRÉREQUIS', 1);

        $prerequis = $this->getValue($cdc, 'prerequis', '');

        if (!empty($prerequis)) {
            $items = explode("\n", $prerequis);
            foreach ($items as $item) {
                $item = trim($item);
                if (!empty($item)) {
                    $this->section->addListItem($item, 0, ['size' => 11]);
                }
            }
        } else {
            $this->section->addText('Aucun prérequis spécifique');
        }

        $this->section->addTextBreak(1);
    }

    private function addSection6DescriptifProjet(Cdc $cdc): void
    {
        $this->section->addTitle('6 DESCRIPTIF DU PROJET', 1);

        $descriptif = $this->getValue($cdc, 'descriptif_projet', '');

        if (!empty($descriptif)) {
            $this->parseMarkdown($descriptif);
        } else {
            $this->section->addText('Description du projet à définir.');
        }

        $this->section->addTextBreak(1);
    }

    /**
     * Parse simple Markdown et ajoute au document Word
     */
    private function parseMarkdown(string $text): void
    {
        $lines = explode("\n", $text);
        $inList = false;
        $listLevel = 0;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                if ($inList) {
                    $inList = false;
                    $listLevel = 0;
                }
                $this->section->addTextBreak();
                continue;
            }

            if (preg_match('/^(#{1,3})\s+(.+)$/', $line, $matches)) {
                if ($inList) {
                    $inList = false;
                    $listLevel = 0;
                }
                $level = strlen($matches[1]);
                $text = $matches[2];
                $this->section->addText($text, ['bold' => true, 'size' => 13 - $level]);
                continue;
            }

            if (preg_match('/^(\s*)([-*])\s+(.+)$/', $line, $matches)) {
                $indent = strlen($matches[1]);
                $text = $matches[3];
                $level = intdiv($indent, 2);

                $text = $this->removeInlineMarkdown($text);

                $this->section->addListItem(
                    $text,
                    $level,
                    ['size' => 11],
                    ['indentation' => ['left' => 360 + ($level * 360)]]
                );
                $inList = true;
                $listLevel = $level;
                continue;
            }

            if (preg_match('/^(\s*)(\d+)\.\s+(.+)$/', $line, $matches)) {
                $indent = strlen($matches[1]);
                $text = $matches[3];
                $level = intdiv($indent, 2);

                $text = $this->removeInlineMarkdown($text);

                $this->section->addListItem(
                    $text,
                    $level,
                    ['size' => 11],
                    [
                        'indentation' => ['left' => 360 + ($level * 360)],
                        'numStyle' => 'decimal'
                    ]
                );
                $inList = true;
                continue;
            }

            if ($inList) {
                $inList = false;
                $listLevel = 0;
            }

            $this->addTextWithInlineFormatting($line);
        }
    }

    /**
     * Ajoute du texte avec formatage inline (**gras**, *italique*)
     */
    private function addTextWithInlineFormatting(string $text): void
    {
        $text = $this->removeInlineMarkdown($text);
        $this->section->addText($text, ['size' => 11], ['spaceAfter' => 120]);
    }

    /**
     * Retire le markdown inline pour le texte simple
     */
    private function removeInlineMarkdown(string $text): string
    {
        // **gras** → gras
        $text = preg_replace('/\*\*(.+?)\*\*/', '$1', $text);
        // *italique* → italique
        $text = preg_replace('/\*(.+?)\*/', '$1', $text);
        // `code` → code
        $text = preg_replace('/`(.+?)`/', '$1', $text);
        return $text;
    }

    private function addSection7Livrables(Cdc $cdc): void
    {
        $this->section->addTitle('7 LIVRABLES', 1);

        $this->section->addText('Le candidat est responsable de livrer à son chef de projet et aux deux experts :');
        $this->section->addTextBreak();

        $livrables = $this->getValue($cdc, 'livrables', '');

        if (!empty($livrables)) {
            $items = explode("\n", $livrables);
            foreach ($items as $item) {
                $item = trim($item);
                if (!empty($item)) {
                    $this->section->addListItem($item, 0, ['size' => 11]);
                }
            }
        } else {
            $defaultLivrables = [
                'Une planification initiale',
                'Un rapport de projet',
                'Un journal de travail'
            ];

            foreach ($defaultLivrables as $livrable) {
                $this->section->addListItem($livrable, 0, ['size' => 11]);
            }
        }

        $this->section->addTextBreak(1);
    }

    private function addSection8PointsTechniques(Cdc $cdc): void
    {
        $this->section->addTitle('8 POINTS TECHNIQUES ÉVALUÉS SPÉCIFIQUES AU PROJET', 1);

        $this->section->addText(
            'La grille d\'évaluation définit les critères généraux selon lesquels le travail du candidat sera évalué (documentation, journal de travail, respect des normes, qualité, ...).'
        );

        $this->section->addTextBreak();

        $this->section->addText(
            'En plus de cela, le travail sera évalué sur les 7 points spécifiques suivants (Point A14 à A20) :'
        );

        $this->section->addTextBreak();

        for ($i = 1; $i <= 7; $i++) {
            $point = $this->getValue($cdc, 'point_technique_' . $i, '(à compléter par le chef de projet)');
            $this->section->addText("$i. $point", ['size' => 11]);
        }

        $this->section->addTextBreak(1);
    }

    private function addSection9Validation(Cdc $cdc): void
    {
        $this->section->addTitle('9 VALIDATION', 1);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 100 * 50,
            'unit' => 'pct'
        ];

        $table = $this->section->addTable($tableStyle);

        $table->addRow(400);
        $table->addCell(3000)->addText('');
        $table->addCell(4500)->addText('Lu et approuvé le :', ['bold' => true]);
        $table->addCell(4500)->addText('Signature :', ['bold' => true]);

        $table->addRow(800);
        $table->addCell(3000)->addText('Candidat :');
        $table->addCell(4500)->addText('');
        $table->addCell(4500)->addText('');

        $table->addRow(800);
        $table->addCell(3000)->addText('Expert n°1 :');
        $table->addCell(4500)->addText('');
        $table->addCell(4500)->addText('');

        $table->addRow(800);
        $table->addCell(3000)->addText('Expert n° 2 :');
        $table->addCell(4500)->addText('');
        $table->addCell(4500)->addText('');

        $table->addRow(800);
        $table->addCell(3000)->addText('Chef de projet :');
        $table->addCell(4500)->addText('');
        $table->addCell(4500)->addText('');
    }

    private function getValue(Cdc $cdc, string $key, string $default = ''): string
    {
        return $cdc->data[$key] ?? $default;
    }

    private function save(Cdc $cdc): string
    {
        $filename = 'cdc-' . $cdc->id . '-' . time() . '.docx';
        $path = storage_path('app/public/cdcs/');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filepath = $path . $filename;

        IOFactory::createWriter($this->phpWord, 'Word2007')->save($filepath);

        return 'cdcs/' . $filename;
    }
}
