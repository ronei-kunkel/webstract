<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Webstract\Common\HttpContentType;
use Webstract\Pdf\PdfContent;

trait DownloadableResponse
{
	protected function createDownloadResponse(string $filePath): ResponseInterface
	{
		if (!is_readable($filePath)) {
			return $this->response->withStatus(500);
		}

		$stream = fopen($filePath, 'r');
		$filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', basename($filePath));
		$contentType = HttpContentType::fromFilename($filename);

		if ($contentType === null) {
			return $this->response->withStatus(500);
		}

		$this->stream->write(stream_get_contents($stream));
		fclose($stream);

		return $this->response
			->withHeader($contentType::getHeaderName(), $contentType->value)
			->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $filename))
			->withHeader('Content-Length', (string) filesize($filePath))
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withBody($this->stream)
			->withStatus(200);
	}

	private function createPdfContentDownloadableResponse(PdfContent $pdfContent): ResponseInterface
	{
		$filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $pdfContent->getName());
		$contentType = HttpContentType::PDF;

		$renderedPdfContent = $this->pdfGenerator->generateContent($pdfContent);

		$this->streamInterface->write($renderedPdfContent);
		return $this->responseInterface
			->withHeader($contentType::getHeaderName(), $contentType->value)
			->withHeader('Content-Disposition', sprintf('attachment; filename="%s.pdf"', $filename))
			->withHeader('Content-Length', (string) strlen($renderedPdfContent))
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withBody($this->streamInterface)
			->withStatus(200);
	}
}
