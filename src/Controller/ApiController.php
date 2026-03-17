<?php

declare(strict_types=1);

namespace Webstract\Controller;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Common\HttpContentType;

abstract class ApiController implements Controller
{
	public function __construct(
		protected readonly ResponseInterface $response,
		protected readonly StreamInterface $stream,
	) {}

	protected function createJsonResponse(
		array|JsonSerializable|null $content = null,
		int $statusCode = 200,
	): ResponseInterface {
		if ($content) {
			$this->stream->write(json_encode($content));
		}

		return $this->response
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::JSON->value)
			->withBody($this->stream)
			->withStatus($statusCode);
	}
}
