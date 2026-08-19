<?php

namespace Tests\Unit\Services;

use App\Services\MarkdownToWordConverter;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use Tests\TestCase;

class MarkdownToWordConverterTest extends TestCase
{
    private MarkdownToWordConverter $converter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->converter = new MarkdownToWordConverter();
    }

    public function test_convert_creates_paragraphs_from_markdown(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $elementCount = $section->getElements() ? count($section->getElements()) : 0;

        $this->converter->convert($section, "Hello world\n\nSecond paragraph");

        $newCount = count($section->getElements());
        $this->assertGreaterThan($elementCount, $newCount);
    }

    public function test_convert_handles_empty_content(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $this->converter->convert($section, '');

        $this->assertEmpty($section->getElements());
    }

    public function test_convert_handles_bold_text(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $this->converter->convert($section, 'Text with **bold** inside');

        $this->assertNotEmpty($section->getElements());
    }

    public function test_convert_handles_bullet_list(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $this->converter->convert($section, "- Item 1\n- Item 2\n- Item 3");

        $this->assertNotEmpty($section->getElements());
    }
}
