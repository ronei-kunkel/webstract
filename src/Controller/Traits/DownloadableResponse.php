<?php

declare(strict_types=1);

namespace Webstract\Controller\Traits;

use Psr\Http\Message\ResponseInterface;
use Webstract\Http\ContentType;
use Webstract\Pdf\PdfContent;

trait DownloadableResponse
{
	protected function createDownloadResponse(string $filePath): ResponseInterface
	{
		$fileStream = @fopen($filePath, 'r');
		if ($fileStream === false) {
			return $this->response->withStatus(500);
		}

		$filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', basename($filePath));

		$contentType = ContentType::fromFilename($filename);
		if ($contentType === null) {
			return $this->response->withStatus(500);
		}

		$fileContent = stream_get_contents($fileStream);
		$this->stream->write($fileContent);
		fclose($fileStream);

		return $this->response
			->withHeader(ContentType::getHeaderName(), $contentType->value)
			->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $filename))
			->withHeader('Content-Length', (string) filesize($filePath))
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withBody($this->stream)
			->withStatus(200);
	}

	protected function createPdfContentDownloadableResponse(PdfContent $pdfContent): ResponseInterface
	{
		$filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $pdfContent->getName());
		$contentType = ContentType::PDF;

		$renderedPdfContent = $this->pdfGenerator->generateContent($pdfContent);

		$this->stream->write($renderedPdfContent);
		return $this->response
			->withHeader($contentType::getHeaderName(), $contentType->value)
			->withHeader('Content-Disposition', sprintf('attachment; filename="%s.pdf"', $filename))
			->withHeader('Content-Length', (string) strlen($renderedPdfContent))
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withBody($this->stream)
			->withStatus(200);
	}
}
