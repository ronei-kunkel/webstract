<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Psr\Http\Message\ServerRequestInterface;
use Webstract\Route\RouteResolver;
use Webstract\Route\RouterOutput;

final class FakeRouteResolver implements RouteResolver
{
	public ?ServerRequestInterface $receivedRequest = null;

	public function __construct(private readonly RouterOutput $output)
	{
	}

	public function resolve(ServerRequestInterface $serverRequest): RouterOutput
	{
		$this->receivedRequest = $serverRequest;

		return $this->output;
	}
}
