<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Request;

use Psr\Http\Message\ServerRequestInterface;

interface ServerRequestFactory
{
	public function create(): ServerRequestInterface;
}
