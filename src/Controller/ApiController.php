<?php

declare(strict_types=1);

namespace Webstract\Controller;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Common\HttpContentType;
use Webstract\Controller\DownloadableResponse;
use Webstract\Pdf\PdfGenerator;

abstract class ApiController extends Controller
{
	use DownloadableResponse;

	public function __construct(
		protected readonly ResponseInterface $response,
		protected readonly StreamInterface $stream,
		protected readonly PdfGenerator $pdfGenerator,
	) {}

	protected function createJsonResponse(
		array|JsonSerializable|null $content = null
	): ResponseInterface {
		if ($content) {
			$this->stream->write(json_encode($content));
		}

		return $this->response
			->withBody($this->stream)
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::JSON->value)
			->withStatus(200);
	}
}
