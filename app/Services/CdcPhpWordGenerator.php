<?php

namespace App\Services;

use App\Models\Cdc;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\IOFactory;
use League\CommonMark\CommonMarkConverter;

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

    public function generate(Cdc $cdc): string
    {
        try {
            $this->phpWord = new PhpWord();

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
            $this->addPointsTechniques($cdc);
            $this->addValidation();

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
        $header = $this->section->addHeader();
        $header->addText(
            'Centre de formation - DEV - Brief projet',
            ['name' => 'Calibri', 'size' => 9],
            ['alignment' => Jc::END]
        );

        $footer = $this->section->addFooter();
        $lineStyle = ['weight' => 1.5, 'width' => 450, 'height' => 0, 'color' => '000000'];
        $footer->addLine($lineStyle);
        $paragraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $footer->addPreserveText('Page {PAGE} sur {NUMPAGES}', null, $paragraphStyle);
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
            ['name' => 'Calibri','size' => 15, 'color' => '17365D'],
            ['alignment' => Jc::START, 'spaceBefore' => 240, 'spaceAfter' => 120]
        );
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
            // Nettoyer la valeur (enlever H, %, espaces)
            $cleaned = preg_replace('/[^0-9.]/', '', $value);

            if (empty($cleaned)) {
                continue;
            }

            // Détecter si c'est en pourcentage
            if (stripos($value, '%') !== false) {
                $isPercentage = true;
            }

            $total += (float) $cleaned;
        }

        // Retourner le total avec la bonne unité
        if ($isPercentage) {
            return round($total) . '%';
        }

        return round($total) . 'H';
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

        $table = $this->section->addTable($tableStyle);

        // --- CANDIDAT ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Candidat:', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(3000)
            ->addText('Nom:', $fontStyle);
        $table->addCell(3000)
            ->addText('Prénom:', $fontStyle);

        $table->addRow();
        $table->addCell(3000); // Cellule vide
        $table->addCell(3000)
            ->addText($cdc->data['candidat_nom'] ?? '', $fontStyle);
        $table->addCell(3000)
            ->addText($cdc->data['candidat_prenom'] ?? '', $fontStyle);

        // --- LIEU DE TRAVAIL ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Lieu de travail:', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($cdc->data['lieu_travail'] ?? '', $fontStyle);

        // --- ORIENTATION ---
        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Orientation :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($cdc->data['orientation'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Chef de projet:', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(3000)
            ->addText('Nom:', $fontStyle);
        $table->addCell(3000)
            ->addText('Prénom:', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText($cdc->data['chef_projet_nom'] ?? '', $fontStyle);
        $table->addCell(3000)
            ->addText($cdc->data['chef_projet_prenom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText('Email :', $fontStyle);
        $table->addCell(3000)
            ->addText('☎ :', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText($cdc->data['chef_projet_email'] ?? '', $fontStyle);
        $table->addCell(3000)
            ->addText($cdc->data['chef_projet_telephone'] ?? '', $fontStyle);

        // --- EXPERT 1 ---
        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Expert 1:', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(3000)
            ->addText('Nom:', $fontStyle);
        $table->addCell(3000)
            ->addText('Prénom:', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText($cdc->data['expert1_nom'] ?? '', $fontStyle);
        $table->addCell(3000)
            ->addText($cdc->data['expert1_prenom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText('Email :', $fontStyle);
        $table->addCell(3000)
            ->addText('☎ :', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText($cdc->data['expert1_email'] ?? '', $fontStyle);
        $table->addCell(3000)
            ->addText($cdc->data['expert1_telephone'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Expert 2:', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(3000)
            ->addText('Nom:', $fontStyle);
        $table->addCell(3000)
            ->addText('Prénom:', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText($cdc->data['expert2_nom'] ?? '', $fontStyle);
        $table->addCell(3000)
            ->addText($cdc->data['expert2_prenom'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText('Email :', $fontStyle);
        $table->addCell(3000)
            ->addText('☎ :', $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(3000)
            ->addText($cdc->data['expert2_email'] ?? '', $fontStyle);
        $table->addCell(3000)
            ->addText($cdc->data['expert2_telephone'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Période de réalisation :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($cdc->data['periode_realisation'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Horaire de travail :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($cdc->data['horaire_travail'] ?? '', $fontStyle);

        $table->addRow();
        $table->addCell(3000, $cellBgColor)
            ->addText('Nombre d\'heures :', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText($this->calculateTotalHeures($cdc), $fontStyle);

        $table->addRow();
        $table->addCell(3000, array_merge(['vMerge' => 'restart'], $cellBgColor))
            ->addText('Planning (en H ou %)', array_merge($fontStyle, ['bold' => true]));
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Analyse : ' . ($cdc->data['planning_analyse'] ?? '0H'), $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Implémentation : ' . ($cdc->data['planning_implementation'] ?? '0H'), $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Tests : ' . ($cdc->data['planning_tests'] ?? '0H'), $fontStyle);

        $table->addRow();
        $table->addCell(3000, ['vMerge' => 'continue']);
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('Documentations : ' . ($cdc->data['planning_documentation'] ?? '0H'), $fontStyle);
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

    /**
     * ✅ MÉTHODE AVEC SUPPORT MARKDOWN COMPLET
     */
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
    }

    /**
     * ✅ Parse le HTML (issu du Markdown) et l'ajoute au document Word
     */
    private function parseMarkdownToWord(string $html)
    {
        $fontStyle = ['name' => 'Calibri', 'size' => 10];

        $html = strip_tags($html, '<p><strong><em><ul><ol><li><h1><h2><h3><h4><br><code><pre><blockquote>');

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="UTF-8"><body>' . $html . '</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        if ($dom->documentElement) {
            $this->parseNode($dom->documentElement, $fontStyle);
        }
    }

    /**
     * ✅ Parse récursivement les nœuds DOM
     */
    private function parseNode($node, $fontStyle, $depth = 0)
    {
        if (!$node || !$node->childNodes) return;

        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                    $level = (int)substr($child->nodeName, 1);
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

    /**
     * ✅ Ajoute un paragraphe avec formatage inline (gras, italique, code)
     */
    private function addParagraphWithFormatting($node, $baseFontStyle)
    {
        $textRun = $this->section->addTextRun(['spaceAfter' => 120]);
        $this->addFormattedText($node, $textRun, $baseFontStyle);
    }

    /**
     * ✅ Ajoute du texte formaté (gras, italique, code inline)
     */
    private function addFormattedText($node, $textRun, $baseFontStyle)
    {
        if (!$node->hasChildNodes()) {
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
            }
            elseif ($child->nodeName === 'strong' || $child->nodeName === 'b') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($baseFontStyle, ['bold' => true])
                );
            }
            elseif ($child->nodeName === 'em' || $child->nodeName === 'i') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($baseFontStyle, ['italic' => true])
                );
            }
            elseif ($child->nodeName === 'code') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($baseFontStyle, [
                        'name' => 'Courier New',
                        'size' => 9,
                        'color' => 'D32F2F'
                    ])
                );
            }
            elseif ($child->nodeName === 'br') {
                $textRun->addTextBreak();
            }
            else {
                $this->addFormattedText($child, $textRun, $baseFontStyle);
            }
        }
    }

    /**
     * ✅ Parse les listes (ul/ol)
     */
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

    /**
     * ✅ Extrait le texte formaté d'un nœud (pour les listes)
     */
    private function extractFormattedText($node, $fontStyle)
    {
        $text = '';

        foreach ($node->childNodes as $child) {
            if ($child->nodeName === '#text') {
                $text .= $child->textContent;
            }
            elseif ($child->nodeName === 'strong' || $child->nodeName === 'b') {
                $text .= $child->textContent;
            }
            elseif ($child->nodeName === 'em' || $child->nodeName === 'i') {
                $text .= $child->textContent;
            }
            elseif ($child->nodeName === 'code') {
                $text .= $child->textContent;
            }
            elseif ($child->nodeName === 'br') {
                $text .= ' ';
            }
            elseif ($child->nodeName !== 'ul' && $child->nodeName !== 'ol') {
                $text .= $this->extractFormattedText($child, $fontStyle);
            }
        }

        return trim($text);
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
