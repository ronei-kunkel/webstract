<?php

declare(strict_types=1);

namespace Test\Support\Contracts\Pdf;

use RuntimeException;
use Webstract\Pdf\PdfContent;
use Webstract\Pdf\PdfGenerator;
use Webstract\TemplateEngine\TemplateEngineRenderer;

class FakePdfGenerator implements PdfGenerator
{
	public bool $shouldThrow = false;

	public function __construct(private readonly TemplateEngineRenderer $templateEngine)
	{
	}

	public function generateContent(PdfContent $pdfContent): string
	{
		if ($this->shouldThrow) {
			throw new RuntimeException('pdf generation failed');
		}

		return 'PDF:' . $this->templateEngine->render($pdfContent->getHtmlPath(), $pdfContent->getContext());
	}
}
