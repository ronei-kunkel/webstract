<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use RoneiKunkel\Webstract\Common\HttpContentType;

trait DownloadableResponse
{
	protected function createDownloadResponse(string $filePath): ResponseInterface
	{
		if (!is_readable($filePath)) {
			return $this->responseInterface->withStatus(500);
		}

		$stream = fopen($filePath, 'r');
		$filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', basename($filePath));
		$contentType = HttpContentType::fromFilename($filename);

		if ($contentType === null) {
			return $this->responseInterface->withStatus(500);
		}

		$this->streamInterface->write(stream_get_contents($stream));
		fclose($stream);

		return $this->responseInterface
			->withHeader($contentType::getHeaderName(), $contentType->value)
			->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $filename))
			->withHeader('Content-Length', (string) filesize($filePath))
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withBody($this->streamInterface)
			->withStatus(200);
	}
}
