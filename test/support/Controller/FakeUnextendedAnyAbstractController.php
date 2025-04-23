<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;

final class FakeUnextendedAnyAbstractController
{
	public function handle(ServerRequestInterface $serverRequest): void {}
}
