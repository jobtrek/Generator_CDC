<?php

namespace App\Services;

use App\Models\Cdc;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table;

class CdcPhpWordGenerator
{
    private $phpWord;

    private $section;

    private $markdownConverter;

    public function __construct()
    {
        $this->markdownConverter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    private function getStringValue($value): string
    {
        if (is_array($value)) {
            return json_encode($value);
        }
        if (is_null($value)) {
            return '';
        }

        return (string) $value;
    }
    public function generate(Cdc $cdc): string
    {
        try {
            $this->phpWord = new PhpWord;

            $this->phpWord->setDefaultParagraphStyle([
                'spaceBefore' => 0,
                'spaceAfter' => 0,
                'lineHeight' => 1.0,
            ]);

            $sectionStyle = [
                'marginTop' => 1134,
                'marginBottom' => 1134,
                'marginLeft' => 1134,
                'marginRight' => 1134,
                'headerHeight' => 720,
                'footerHeight' => 720,
            ];

            $this->section = $this->phpWord->addSection($sectionStyle);

            $this->addHeaderFooter();

            $this->addDocumentHeader();
            $this->addInformationsGenerales($cdc);
            $this->addProcedure($cdc);
            $this->addTitre($cdc);
            $this->addMaterielLogiciel($cdc);
            $this->addPrerequis($cdc);
            $this->addDescriptifProjet($cdc);
            $this->addLivrables($cdc);
            $this->addValidation();

            $timestamp = time();
            $docxFileName = 'cdc-'.$cdc->id.'-'.$timestamp.'.docx';
            $docxPath = storage_path('app/public/cdc/'.$docxFileName);

            $cdcDir = storage_path('app/public/cdc');
            if (! File::exists($cdcDir)) {
                File::makeDirectory($cdcDir, 0755, true);
            }

            $objWriter = IOFactory::createWriter($this->phpWord, 'Word2007');
            $objWriter->save($docxPath);

            return 'cdc/'.$docxFileName;

        } catch (\Exception $e) {
            Log::error('Erreur génération CDC avec PhpWord', [
                'cdc_id' => $cdc->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            throw $e;
        }
    }

    private function addHeaderFooter()
    {
        $header = $this->section->addHeader();
        $header->addText(
            'Centre de formation - DEV - Brief projet',
            ['name' => 'Calibri', 'size' => 9],
            ['alignment' => Jc::END]
        );

        $footer = $this->section->addFooter();

        $footerTable = $footer->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'width' => 100 * 50,
            'unit' => TblWidth::PERCENT,
            'cellMargin' => 0,
            'layout' => Table::LAYOUT_FIXED,
        ]);

        $footerTable->addRow();

        $cellLeft = $footerTable->addCell(4500, [
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
        ]);
        $cellLeft->addPreserveText(
            'Page {PAGE} sur {NUMPAGES}',
            ['name' => 'Calibri', 'size' => 9],
            ['alignment' => Jc::START]
        );

        $cellRight = $footerTable->addCell(4500, [
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
        ]);
        $cellRight->addText(
            'Version 1.1-ordo2k104-21 (18.01.2025)',
            ['name' => 'Calibri', 'size' => 9],
            ['alignment' => Jc::END, 'spaceAfter' => 0]
        );
        $cellRight->addText(
            '© I-CQ VD 2017/25',
            ['name' => 'Calibri', 'size' => 9],
            ['alignment' => Jc::END, 'spaceAfter' => 0]
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
            'Version 1.1 - '.now()->format('d.m.Y'),
            ['name' => 'Calibri', 'size' => 10],
            ['alignment' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 240]
        );
    }

    private function addSectionTitle($title)
    {
        $this->section->addText(
            $title,
            ['name' => 'Calibri', 'size' => 15, 'color' => '2E74B5'],
            [
                'alignment' => Jc::START,
                'spaceBefore' => 200,
                'spaceAfter' => 120,
                'borderTopSize' => 6,
                'borderTopColor' => 'A6A6A6',
            ]
        );
    }

    /**
     * Ajoute une ligne horizontale avant chaque section
     */
    private function addSectionSeparator()
    {
        $this->section->addTextBreak(1);
    }

    /**
     * Calcule le nombre total d'heures à partir du planning
     */
    private function calculateTotalHeures(Cdc $cdc): string
    {
        $planning = [
            'analyse' => $cdc->data['planning_analyse'] ?? '0H',
            'implementation' => $cdc->data['planning_implementation'] ?? '0H',
            'tests' => $cdc->data['planning_tests'] ?? '0H',
            'documentation' => $cdc->data['planning_documentation'] ?? '0H',
        ];

        $total = 0;
        $isPercentage = false;

        foreach ($planning as $value) {
            $cleaned = preg_replace('/[^0-9.]/', '', $value);

            if (empty($cleaned)) {
                continue;
            }

            if (stripos($value, '%') !== false) {
                $isPercentage = true;
            }

            $total += (float) $cleaned;
        }

        if ($isPercentage) {
            return round($total).'%';
        }

        return round($total).'H';
    }

    private function buildHoraireDetails(Cdc $cdc): array
    {
        $lines = [];

        $joursEcole = $this->normalizeJours($cdc->data['jours_ecole'] ?? []);
        $labelTravail = $this->formatJoursTravail($joursEcole);
        if ($labelTravail !== '') {
            $lines[] = $labelTravail.' :';
        }

        $matinDebut = $cdc->data['heure_matin_debut'] ?? '';
        $matinFin = $cdc->data['heure_matin_fin'] ?? '';
        $apremDebut = $cdc->data['heure_aprem_debut'] ?? '';
        $apremFin = $cdc->data['heure_aprem_fin'] ?? '';

        if ($matinDebut && $matinFin) {
            $lines[] = $matinDebut.' – '.$matinFin
                .$this->dureePauseLabel($cdc->data['pause_matin_debut'] ?? '', $cdc->data['pause_matin_fin'] ?? '');
        }
        if ($apremDebut && $apremFin) {
            $lines[] = $apremDebut.' – '.$apremFin
                .$this->dureePauseLabel($cdc->data['pause_aprem_debut'] ?? '', $cdc->data['pause_aprem_fin'] ?? '');
        }

        if (! empty($joursEcole)) {
            $lines[] = $this->abbrevJours($joursEcole).' : cours';
        }
        return $lines ?: [$cdc->data['horaire_travail'] ?? ''];
    }

    private function normalizeJours($jours): array
    {
        if (is_string($jours)) {
            $jours = json_decode($jours, true) ?: ($jours !== '' ? [$jours] : []);
        }
        if (! is_array($jours)) {
            $jours = [];
        }

        return array_values(array_filter(array_map(fn ($j) => strtolower(trim((string) $j)), $jours)));
    }

    private function abbrevJours(array $jours): string
    {
        $map = [
            'lundi' => 'Lu', 'mardi' => 'Ma', 'mercredi' => 'Me',
            'jeudi' => 'Je', 'vendredi' => 'Ve', 'samedi' => 'Sa', 'dimanche' => 'Di',
        ];

        return implode(', ', array_map(fn ($j) => $map[$j] ?? ucfirst($j), $jours));
    }

    private function formatJoursTravail(array $joursEcole): string
    {
        $semaine = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        $travail = array_values(array_diff($semaine, $joursEcole));

        if (empty($travail)) {
            return '';
        }

        $indices = array_map(fn ($j) => array_search($j, $semaine), $travail);
        $contigu = (max($indices) - min($indices) + 1) === count($indices);

        if (count($travail) === 1) {
            return $this->abbrevJours($travail);
        }

        if ($contigu) {
            return $this->abbrevJours([$travail[0]]).'-'.$this->abbrevJours([end($travail)]);
        }

        return $this->abbrevJours($travail);
    }

    private function dureePauseLabel(string $debut, string $fin): string
    {
        if ($debut === '' || $fin === '') {
            return '';
        }

        $minutes = (int) round((strtotime($fin) - strtotime($debut)) / 60);

        return $minutes > 0 ? ' ('.$minutes.'min pause)' : '';
    }

    private function addPersonneRows($table, string $label, string $nom, string $prenom, string $email, string $telephone, array $fontStyle, array $cellBgColor): void
    {
        $boldFont = array_merge($fontStyle, ['bold' => true]);
        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText($label, $boldFont);

        $nomRun = $table->addCell(3000)->addTextRun();
        $nomRun->addText('Nom : ', $fontStyle);
        $nomRun->addText($nom, $boldFont);

        $prenomRun = $table->addCell(3000)->addTextRun();
        $prenomRun->addText('Prénom : ', $fontStyle);
        $prenomRun->addText($prenom, $fontStyle);

        // Ligne 2 : email + téléphone
        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);

        $emailRun = $table->addCell(3000)->addTextRun();
        $emailRun->addText('✉ ', $fontStyle);
        $emailRun->addText($email, $fontStyle);

        $telRun = $table->addCell(3000)->addTextRun();
        $telRun->addText('☎ ', $fontStyle);
        $telRun->addText($telephone, $fontStyle);
    }

    private function addInformationsGenerales(Cdc $cdc)
    {
        $this->addSectionTitle('1 INFORMATIONS GÉNÉRALES');

        $tableStyle = [
            'borderSize' => 1,
            'borderColor' => '000000',
            'cellMarginLeft' => 60,
            'cellMarginRight' => 20,
            'cellMarginTop' => 70,
            'cellMarginBottom' => 70,
            'width' => 100 * 50,
            'unit' => TblWidth::PERCENT,
        ];

        $cellBgColor = [];
        $fontStyle = ['name' => 'Calibri', 'size' => 10];

        $table = $this->section->addTable($tableStyle);

        // --- CANDIDAT ---
        $this->addPersonneRows(
            $table,
            'Candidat',
            $cdc->data['candidat_nom'] ?? '',
            $cdc->data['candidat_prenom'] ?? '',
            $cdc->data['candidat_email'] ?? '',
            $cdc->data['candidat_telephone'] ?? '',
            $fontStyle,
            $cellBgColor
        );

        // --- LIEU DE TRAVAIL ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Lieu de travail :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($cdc->data['lieu_travail'] ?? '', $fontStyle);

        // --- ORIENTATION ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Orientation :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($cdc->data['orientation'] ?? '', $fontStyle);

        // --- CHEF DE PROJET ---
        $this->addPersonneRows(
            $table,
            'Chef de projet',
            $cdc->data['chef_projet_nom'] ?? '',
            $cdc->data['chef_projet_prenom'] ?? '',
            $cdc->data['chef_projet_email'] ?? '',
            $cdc->data['chef_projet_telephone'] ?? '',
            $fontStyle,
            $cellBgColor
        );

        // --- EXPERT 1 ---
        $this->addPersonneRows(
            $table,
            'Expert 1',
            $cdc->data['expert1_nom'] ?? '',
            $cdc->data['expert1_prenom'] ?? '',
            $cdc->data['expert1_email'] ?? '',
            $cdc->data['expert1_telephone'] ?? '',
            $fontStyle,
            $cellBgColor
        );

        // --- EXPERT 2 ---
        $this->addPersonneRows(
            $table,
            'Expert 2',
            $cdc->data['expert2_nom'] ?? '',
            $cdc->data['expert2_prenom'] ?? '',
            $cdc->data['expert2_email'] ?? '',
            $cdc->data['expert2_telephone'] ?? '',
            $fontStyle,
            $cellBgColor
        );

        // --- PÉRIODE DE RÉALISATION ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Période de réalisation :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($this->formatPeriodeRealisation($cdc), $fontStyle);

        // --- HORAIRE DE TRAVAIL (jours d'école + pauses regroupés) ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Horaire de travail :', array_merge($fontStyle, ['bold' => true]));
        $horaireCell = $table->addCell(6000, ['gridSpan' => 2]);
        foreach ($this->buildHoraireDetails($cdc) as $ligne) {
            $horaireCell->addText($ligne, $fontStyle);
        }

        // --- NOMBRE D'HEURES ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Nombre d\'heures :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($this->calculateTotalHeures($cdc), $fontStyle);

        // --- PLANNING ---
        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Planning (en H ou %) :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Analyse : '.($cdc->data['planning_analyse'] ?? '0H'), $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Implémentation : '.($cdc->data['planning_implementation'] ?? '0H'), $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Tests : '.($cdc->data['planning_tests'] ?? '0H'), $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Documentations : '.($cdc->data['planning_documentation'] ?? '0H'), $fontStyle);

        $this->addSectionSeparator();
    }

    private function formatPeriodeRealisation(Cdc $cdc): string
    {
        $dateDebut = $cdc->data['date_debut'] ?? null;
        $dateFin = $cdc->data['date_fin'] ?? null;

        if ($dateDebut && $dateFin) {
            return (new DateTimeFormatter)->buildPeriodeRealisation($dateDebut, $dateFin);
        }

        return $cdc->data['periode_realisation'] ?? '';
    }

    private function addProcedure(Cdc $cdc)
    {
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

        // ✅ Ligne de fin de section
        $this->addSectionSeparator();
    }

    private function addTitre(Cdc $cdc)
    {
        $this->addSectionTitle('3 TITRE');
        $this->section->addText($cdc->title, ['name' => 'Calibri', 'size' => 10]);

        // ✅ Ligne de fin de section
        $this->addSectionSeparator();
    }

    private function addMaterielLogiciel(Cdc $cdc)
    {
        if (! empty($cdc->data['materiel_logiciel'])) {
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

            // ✅ Ligne de fin de section
            $this->addSectionSeparator();
        }
    }

    private function addPrerequis(Cdc $cdc)
    {
        if (! empty($cdc->data['prerequis'])) {
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

            $this->addSectionSeparator();
        }
    }

    private function addDescriptifProjet(Cdc $cdc)
    {
        $this->addSectionTitle('6 DESCRIPTIF DU PROJET');

        $descriptif = $cdc->data['descriptif_projet'] ?? 'Non renseigné';

        try {
            $html = $this->markdownConverter->convert($descriptif);
            $this->parseMarkdownToWord($html->getContent());
        } catch (\Exception $e) {
            Log::warning('Erreur conversion Markdown', ['error' => $e->getMessage()]);
            $this->section->addText($descriptif, ['name' => 'Calibri', 'size' => 10]);
        }

        $this->addSectionSeparator();
    }

    private function parseMarkdownToWord(string $html)
    {
        $fontStyle = ['name' => 'Calibri', 'size' => 10];

        $html = strip_tags($html, '<p><strong><em><ul><ol><li><h1><h2><h3><h4><br><code><pre><blockquote>');

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        if ($dom->documentElement) {
            $this->parseNode($dom->documentElement, $fontStyle);
        }
    }

    private function parseNode($node, $fontStyle, $depth = 0)
    {
        if (! $node || ! $node->childNodes) {
            return;
        }

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                    $level = (int) substr($child->nodeName, 1);
                    $this->section->addText(
                        trim($child->textContent),
                        array_merge($fontStyle, ['bold' => true, 'size' => 16 - ($level * 2)]),
                        ['spaceAfter' => 120, 'spaceBefore' => 240]
                    );
                    break;

                case 'p':
                    if (trim($child->textContent)) {
                        $this->addParagraphWithFormatting($child, $fontStyle);
                    }
                    break;

                case 'ul':
                case 'ol':
                    $this->parseList($child, $fontStyle, $depth);
                    break;

                case 'blockquote':
                    $textRun = $this->section->addTextRun(['spaceAfter' => 120, 'spaceBefore' => 120]);
                    $textRun->addText(
                        trim($child->textContent),
                        array_merge($fontStyle, ['italic' => true, 'color' => '666666'])
                    );
                    break;

                case 'pre':
                    $this->section->addText(
                        trim($child->textContent),
                        array_merge($fontStyle, ['name' => 'Courier New', 'size' => 9, 'color' => '333333']),
                        ['spaceAfter' => 120, 'spaceBefore' => 120]
                    );
                    break;

                case 'code':
                    break;

                case 'body':
                    $this->parseNode($child, $fontStyle, $depth);
                    break;

                case '#text':
                    $text = trim($child->textContent);
                    if ($text && strlen($text) > 0) {
                        $this->section->addText($text, $fontStyle, ['spaceAfter' => 120]);
                    }
                    break;

                default:
                    if ($child->hasChildNodes()) {
                        $this->parseNode($child, $fontStyle, $depth);
                    }
                    break;
            }
        }
    }

    private function addParagraphWithFormatting($node, $baseFontStyle)
    {
        $textRun = $this->section->addTextRun(['spaceAfter' => 120]);
        $this->addFormattedText($node, $textRun, $baseFontStyle);
    }

    private function addFormattedText($node, $textRun, $baseFontStyle)
    {
        if (! $node->hasChildNodes()) {
            $text = trim($node->textContent);
            if ($text) {
                $textRun->addText($text, $baseFontStyle);
            }

            return;
        }

        foreach ($node->childNodes as $child) {
            if ($child->nodeName === '#text') {
                $text = $child->textContent;
                if ($text && $text !== "\n") {
                    $textRun->addText($text, $baseFontStyle);
                }
            } elseif ($child->nodeName === 'strong' || $child->nodeName === 'b') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($baseFontStyle, ['bold' => true])
                );
            } elseif ($child->nodeName === 'em' || $child->nodeName === 'i') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($baseFontStyle, ['italic' => true])
                );
            } elseif ($child->nodeName === 'code') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($baseFontStyle, [
                        'name' => 'Courier New',
                        'size' => 9,
                        'color' => 'D32F2F',
                    ])
                );
            } elseif ($child->nodeName === 'br') {
                $textRun->addTextBreak();
            } else {
                $this->addFormattedText($child, $textRun, $baseFontStyle);
            }
        }
    }

    private function parseList($listNode, $fontStyle, $depth = 0)
    {
        foreach ($listNode->childNodes as $li) {
            if ($li->nodeName === 'li') {
                $textContent = $this->extractFormattedText($li, $fontStyle);

                if ($textContent) {
                    $this->section->addListItem(
                        $textContent,
                        $depth,
                        $fontStyle,
                        ['spaceAfter' => 60]
                    );
                }

                foreach ($li->childNodes as $subNode) {
                    if ($subNode->nodeName === 'ul' || $subNode->nodeName === 'ol') {
                        $this->parseList($subNode, $fontStyle, $depth + 1);
                    }
                }
            }
        }
    }

    private function extractFormattedText($node, $fontStyle)
    {
        $text = '';

        foreach ($node->childNodes as $child) {
            if ($child->nodeName === '#text') {
                $text .= $child->textContent;
            } elseif ($child->nodeName === 'strong' || $child->nodeName === 'b') {
                $text .= $child->textContent;
            } elseif ($child->nodeName === 'em' || $child->nodeName === 'i') {
                $text .= $child->textContent;
            } elseif ($child->nodeName === 'code') {
                $text .= $child->textContent;
            } elseif ($child->nodeName === 'br') {
                $text .= ' ';
            } elseif ($child->nodeName !== 'ul' && $child->nodeName !== 'ol') {
                $text .= $this->extractFormattedText($child, $fontStyle);
            }
        }

        return trim($text);
    }

    private function addLivrables(Cdc $cdc)
    {
        if (! empty($cdc->data['livrables'])) {
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

            $this->addSectionSeparator();
        }
    }

    private function addValidation()
    {
        $this->section->addPageBreak();
        $this->addSectionTitle('8 VALIDATION');

        $tableStyle = [
            'borderSize' => 1,
            'borderColor' => '000000',
            'cellMarginLeft' => 60,
            'cellMarginRight' => 20,
            'cellMarginTop' => 100,
            'cellMarginBottom' => 100,
            'width' => 100 * 50,
            'unit' => TblWidth::PERCENT,
        ];

        $cellBgColor = ['bgColor' => 'f0f0f0'];
        $fontStyle = ['name' => 'Calibri', 'size' => 10];

        $table = $this->section->addTable($tableStyle);

        $table->addRow(500, ['cantSplit' => true, 'tblHeader' => true]);
        $table->addCell(3500, $cellBgColor)->addText('Lu et approuvé le :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(5500, $cellBgColor)->addText('Signature :', array_merge($fontStyle, ['bold' => true]));

        $table->addRow(1000, ['cantSplit' => true]);
        $table->addCell(3500)->addText('Candidat :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);

        $table->addRow(1000, ['cantSplit' => true]);
        $table->addCell(3500)->addText('Expert n°1 :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);

        $table->addRow(1000, ['cantSplit' => true]);
        $table->addCell(3500)->addText('Expert n°2 :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);

        $table->addRow(1000, ['cantSplit' => true]);
        $table->addCell(3500)->addText('Chef de projet :', $fontStyle);
        $table->addCell(5500)->addText('', $fontStyle);
    }
}
