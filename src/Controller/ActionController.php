<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Controller\Traits\RedirectableResponse;
use Webstract\Session\SessionHandler;

abstract class ActionController implements Controller
{
	use RedirectableResponse;

	public function __construct(
		protected readonly ResponseInterface $response,
		protected readonly StreamInterface $stream,
		protected readonly SessionHandler $session,
	) {}
}
