<?php

namespace App\Services;

use App\Models\Cdc;
use App\Models\Form;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class CdcGenerator
{
    private PhpWord $phpWord;
    private $section;

    public function __construct()
    {
        $this->phpWord = new PhpWord();
        $this->phpWord->setDefaultFontName('Arial');
        $this->phpWord->setDefaultFontSize(11);
    }

    public function generate(Cdc $cdc): string
    {
        $this->section = $this->phpWord->addSection($this->getSectionStyle());

        $this->addHeader($cdc);
        $this->addInformations($cdc);
        $this->addFields($cdc);
        $this->addFooter();

        return $this->save($cdc);
    }

    private function getSectionStyle(): array
    {
        return [
            'marginTop' => 1440,
            'marginBottom' => 1440,
            'marginLeft' => 1800,
            'marginRight' => 1800,
        ];
    }

    private function addHeader(Cdc $cdc): void
    {
        $this->section->addText(
            'CAHIER DES CHARGES',
            ['bold' => true, 'size' => 18, 'color' => '2E74B5'],
            ['alignment' => 'center', 'spaceAfter' => 240]
        );

        $this->section->addText(
            strtoupper($cdc->title),
            ['bold' => true, 'size' => 14],
            ['alignment' => 'center', 'spaceAfter' => 480]
        );
    }

    private function addInformations(Cdc $cdc): void
    {
        $this->addSectionTitle('1. INFORMATIONS GÉNÉRALES');

        $table = $this->section->addTable($this->getTableStyle());

        $infoRows = [
            ['Candidat', $cdc->user->name],
            ['Lieu de travail', config('app.name')],
            ['Période de réalisation', now()->format('d/m/Y')],
        ];

        foreach ($infoRows as $row) {
            $table->addRow();
            $table->addCell(4000)->addText($row[0], ['bold' => true]);
            $table->addCell(6000)->addText($row[1]);
        }
    }

    private function addFields(Cdc $cdc): void
    {
        $this->addSectionTitle('2. DESCRIPTIF DU PROJET');

        foreach ($cdc->form->fields()->orderBy('order_index')->get() as $field) {
            $value = $cdc->data[$field->name] ?? 'Non renseigné';

            $this->addField($field->label, $value, $field->is_required);
        }
    }

    private function addField(string $label, $value, bool $required): void
    {
        $labelText = $label . ($required ? ' *' : '');

        $this->section->addText(
            $labelText,
            ['bold' => true, 'size' => 11, 'color' => '333333']
        );

        if (is_array($value)) {
            foreach ($value as $item) {
                $this->section->addText(
                    '• ' . $item,
                    ['size' => 10],
                    ['indentation' => ['left' => 360]]
                );
            }
        } else {
            $this->section->addText(
                $value,
                ['size' => 10],
                ['indentation' => ['left' => 360], 'spaceAfter' => 200]
            );
        }
    }

    private function addSectionTitle(string $title): void
    {
        $this->section->addText(
            $title,
            ['bold' => true, 'size' => 12, 'color' => '2E74B5'],
            ['spaceAfter' => 240, 'spaceBefore' => 360]
        );
    }

    private function addFooter(): void
    {
        $footer = $this->section->addFooter();
        $footer->addText(
            'Généré automatiquement le ' . now()->format('d/m/Y à H:i'),
            ['size' => 8, 'italic' => true, 'color' => '666666'],
            ['alignment' => 'center']
        );
    }

    private function getTableStyle(): array
    {
        return [
            'borderSize' => 6,
            'borderColor' => 'CCCCCC',
            'cellMargin' => 80,
            'width' => 100 * 50,
            'unit' => 'pct'
        ];
    }

    private function save(Cdc $cdc): string
    {
        $filename = 'cdcs-' . $cdc->id . '-' . time() . '.docx';
        $path = storage_path('app/public/cdcs/');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filepath = $path . $filename;

        IOFactory::createWriter($this->phpWord, 'Word2007')->save($filepath);

        return 'cdcs/' . $filename;
    }
}
