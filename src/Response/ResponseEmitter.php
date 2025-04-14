<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Response;

use Psr\Http\Message\ResponseInterface;

interface ResponseEmitter
{
	public function emit(ResponseInterface $response): void;
}
