<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Controller\DownloadableResponse;
use Webstract\Route\RoutePathTemplate;
use Webstract\Session\SessionHandler;

abstract class ActionController extends Controller
{
	use DownloadableResponse;

	public function __construct(
		ResponseInterface $responseInterface,
		StreamInterface $streamInterface,
		protected readonly SessionHandler $sessionHandlerInterface,
	) {
		parent::__construct($responseInterface, $streamInterface);
	}

	protected function createRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->responseInterface->withHeader('Location', $route->renderPath())->withStatus(303);
	}
}
