<?php

declare(strict_types=1);

namespace Webstract\Pdf;

use Webstract\TemplateEngine\TemplateEngineRenderer;

interface PdfGenerator
{
	public function __construct(TemplateEngineRenderer $templateEngine);

	public function generateContent(PdfContent $pdfContent): string;
}
