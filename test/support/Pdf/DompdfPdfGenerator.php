<?php

declare(strict_types=1);

namespace Test\Support\Pdf;

use Dompdf\Dompdf;
use Webstract\Pdf\PdfContent;
use Webstract\Pdf\PdfGenerator;
use Webstract\TemplateEngine\TemplateEngineRenderer;

final class DompdfPdfGenerator implements PdfGenerator
{
	private Dompdf $dompdf;

	public function __construct(
		private TemplateEngineRenderer $templateEngineRenderer
	) {
		$this->dompdf = new Dompdf();
	}

	public function generateContent(PdfContent $pdfContent): string
	{
		$content = $this->templateEngineRenderer->render(
			$pdfContent->getHtmlPath(),
			$pdfContent->getContext()
		);

		$this->dompdf->loadHtml($content);
		$this->dompdf->render();

		return $this->dompdf->output();
	}
}
