<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RoneiKunkel\Webstract\Common\Controller\DownloadableResponse;
use RoneiKunkel\Webstract\Session\SessionInterface;

abstract class ActionController extends Controller
{
	use DownloadableResponse;

	public function __construct(
		ResponseInterface $responseInterface,
		StreamInterface $streamInterface,
		protected readonly SessionInterface $sessionInterface,
	) {
		parent::__construct($responseInterface, $streamInterface);
	}

	protected function createRedirectResponse(string $route): ResponseInterface
	{
		return $this->responseInterface->withHeader('location', $route)->withStatus(303);
	}
}
