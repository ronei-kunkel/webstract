<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RoneiKunkel\Webstract\Controller\DownloadableResponse;
use RoneiKunkel\Webstract\Route\RoutePathTemplate;
use RoneiKunkel\Webstract\Session\SessionHandlerInterface;

abstract class ActionController extends Controller
{
	use DownloadableResponse;

	public function __construct(
		ResponseInterface $responseInterface,
		StreamInterface $streamInterface,
		protected readonly SessionHandlerInterface $sessionHandlerInterface,
	) {
		parent::__construct($responseInterface, $streamInterface);
	}

	protected function createRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->responseInterface->withHeader('Location', $route->renderPath())->withStatus(303);
	}
}
