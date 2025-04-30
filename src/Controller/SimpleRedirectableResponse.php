<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Webstract\Route\RoutePathTemplate;

trait SimpleRedirectableResponse
{
	protected function createRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->response->withHeader('Location', $route->renderPath())->withStatus(303);
	}
}
