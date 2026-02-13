<?php

declare(strict_types=1);

namespace Webstract\PdfGenerator;

use Dompdf\Dompdf;
use Dompdf\Options;
use Webstract\Pdf\PdfContent;
use Webstract\Pdf\PdfGenerator;
use Webstract\TemplateEngine\TemplateEngineRenderer;

final class DompdfPdfGenerator implements PdfGenerator
{
	private Dompdf $dompdf;

	public function __construct(
		private TemplateEngineRenderer $templateEngineRenderer
	) {
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$this->dompdf = new Dompdf($options);
	}

	public function generateContent(PdfContent $pdfContent): string
	{
		$content = $this->templateEngineRenderer->render(
			$pdfContent->getHtmlPath(),
			$pdfContent->getContext()
		);

		$this->dompdf->loadHtml($content);
		$this->dompdf->setPaper('A4', 'landscape');

		$this->dompdf->render();

		return $this->dompdf->output();
	}
}
