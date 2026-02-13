<?php

declare(strict_types=1);

namespace Webstract\Controller\Traits;

use Psr\Http\Message\ResponseInterface;
use Webstract\Route\RoutePathTemplate;

trait AsyncRedirectableResponse
{
	protected function createRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->response->withHeader('hx-redirect', $route->renderPath())->withStatus(303);
	}
}
