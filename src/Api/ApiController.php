<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Api;

use RoneiKunkel\Webstract\Controller;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

abstract class ApiController extends Controller
{
	protected function createJsonResponse(
		array|JsonSerializable|null $content = null
	): ResponseInterface {
		if ($content) {
			$this->streamInterface->write(json_encode($content));
		}

		return $this->responseInterface->withBody($this->streamInterface)->withHeader('content-type', 'application/json');
	}
}
