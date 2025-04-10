<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Common\Controller;

use Psr\Http\Message\ResponseInterface;

trait DownloadableResponse
{
	protected function createDownloadResponse(string $filePath): ResponseInterface
	{
		if (!is_readable($filePath)) {
			return $this->responseInterface->withStatus(500);
		}

		$stream = fopen($filePath, 'r');
		$filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', basename($filePath));
		$contentType = ContentType::fromFilename($filename);

		if ($contentType === null) {
			return $this->responseInterface->withStatus(500);
		}

		$this->streamInterface->write(stream_get_contents($stream));
		fclose($stream);

		return $this->responseInterface
			->withHeader('Content-Type', $contentType->value)
			->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $filename))
			->withHeader('Content-Length', (string) filesize($filePath))
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withStatus(200)
			->withBody($this->streamInterface);
	}
}
