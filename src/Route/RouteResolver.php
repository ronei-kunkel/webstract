<?php

declare(strict_types=1);

namespace Webstract\Route;

use Psr\Http\Message\ServerRequestInterface;

interface RouteResolver
{
	public function resolve(ServerRequestInterface $serverRequest): RouteResolverOutput;
}
