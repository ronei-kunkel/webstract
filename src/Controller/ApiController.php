<?php

declare(strict_types=1);

namespace Webstract\Controller;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Webstract\Common\HttpContentType;
use Webstract\Controller\DownloadableResponse;

abstract class ApiController extends Controller
{
	use DownloadableResponse;

	protected function createJsonResponse(
		array|JsonSerializable|null $content = null
	): ResponseInterface {
		if ($content) {
			$this->streamInterface->write(json_encode($content));
		}

		return $this->responseInterface
			->withBody($this->streamInterface)
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::JSON->value)
			->withStatus(200);
	}
}
