<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;
use PhpOffice\PhpWord\Element\Section;

class MarkdownToWordConverter
{
    private CommonMarkConverter $markdownConverter;

    private Section $section;

    private array $fontStyle;

    public function __construct()
    {
        $this->markdownConverter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public function convert(Section $section, string $markdown, array $fontStyle = ['name' => 'Calibri', 'size' => 10]): void
    {
        $this->section = $section;
        $this->fontStyle = $fontStyle;

        try {
            $html = $this->markdownConverter->convert($markdown);
            $this->parseHtmlToWord($html->getContent());
        } catch (\Exception $e) {
            Log::warning('Erreur conversion Markdown', ['error' => $e->getMessage()]);
            $this->section->addText($markdown, $this->fontStyle);
        }
    }

    private function parseHtmlToWord(string $html): void
    {
        $html = strip_tags($html, '<p><strong><em><ul><ol><li><h1><h2><h3><h4><br><code><pre><blockquote>');

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        if ($dom->documentElement) {
            $this->parseNode($dom->documentElement);
        }
    }

    private function parseNode($node, $depth = 0): void
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
                        array_merge($this->fontStyle, ['bold' => true, 'size' => 16 - ($level * 2)]),
                        ['spaceAfter' => 120, 'spaceBefore' => 240]
                    );
                    break;

                case 'p':
                    if (trim($child->textContent)) {
                        $this->addParagraphWithFormatting($child);
                    }
                    break;

                case 'ul':
                case 'ol'  :
                    $this->parseList($child, $depth);
                    break;

                case 'blockquote':
                    $textRun = $this->section->addTextRun(['spaceAfter' => 120, 'spaceBefore' => 120]);
                    $textRun->addText(
                        trim($child->textContent),
                        array_merge($this->fontStyle, ['italic' => true, 'color' => '666666'])
                    );
                    break;

                case 'pre':
                    $this->section->addText(
                        trim($child->textContent),
                        array_merge($this->fontStyle, ['name' => 'Courier New', 'size' => 9, 'color' => '333333']),
                        ['spaceAfter' => 120, 'spaceBefore' => 120]
                    );
                    break;

                case 'code':
                    break;

                case 'body':
                    $this->parseNode($child, $depth);
                    break;

                case '#text':
                    $text = trim($child->textContent);
                    if ($text && strlen($text) > 0) {
                        $this->section->addText($text, $this->fontStyle, ['spaceAfter' => 120]);
                    }
                    break;

                default:
                    if ($child->hasChildNodes()) {
                        $this->parseNode($child, $depth);
                    }
                    break;
            }
        }
    }

    private function addParagraphWithFormatting($node): void
    {
        $textRun = $this->section->addTextRun(['spaceAfter' => 120]);
        $this->addFormattedText($node, $textRun);
    }

    private function addFormattedText($node, $textRun): void
    {
        if (! $node->hasChildNodes()) {
            $text = trim($node->textContent);
            if ($text) {
                $textRun->addText($text, $this->fontStyle);
            }

            return;
        }

        foreach ($node->childNodes as $child) {
            if ($child->nodeName === '#text') {
                $text = $child->textContent;
                if ($text && $text !== "\n") {
                    $textRun->addText($text, $this->fontStyle);
                }
            } elseif ($child->nodeName === 'strong' || $child->nodeName === 'b') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($this->fontStyle, ['bold' => true])
                );
            } elseif ($child->nodeName === 'em' || $child->nodeName === 'i') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($this->fontStyle, ['italic' => true])
                );
            } elseif ($child->nodeName === 'code') {
                $textRun->addText(
                    $child->textContent,
                    array_merge($this->fontStyle, [
                        'name' => 'Courier New',
                        'size' => 9,
                        'color' => 'D32F2F',
                    ])
                );
            } elseif ($child->nodeName === 'br') {
                $textRun->addTextBreak();
            } else {
                $this->addFormattedText($child, $textRun);
            }
        }
    }

    private function parseList($listNode, $depth = 0): void
    {
        foreach ($listNode->childNodes as $li) {
            if ($li->nodeName === 'li') {
                $textContent = $this->extractFormattedText($li);

                if ($textContent) {
                    $this->section->addListItem(
                        $textContent,
                        $depth,
                        $this->fontStyle,
                        ['spaceAfter' => 60]
                    );
                }

                foreach ($li->childNodes as $subNode) {
                    if ($subNode->nodeName === 'ul' || $subNode->nodeName === 'ol') {
                        $this->parseList($subNode, $depth + 1);
                    }
                }
            }
        }
    }

    private function extractFormattedText($node): string
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
                $text .= $this->extractFormattedText($child);
            }
        }

        return trim($text);
    }
}
