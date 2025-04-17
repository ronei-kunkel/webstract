<?php

declare(strict_types=1);

namespace Webstract\Request;

use Psr\Http\Message\ServerRequestInterface;

interface ServerRequestFactory
{
	public function create(): ServerRequestInterface;
}
