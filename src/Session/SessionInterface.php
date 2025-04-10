<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Session;

use RoneiKunkel\Webstract\Session\Exception\SessionProviderUnreachableException;

interface SessionInterface
{
	/**
	 * @throws SessionProviderUnreachableException
	 * @return void
	 */
	public function create(): void;

	/**
	 * @return void
	 */
	public function destroy(): void;
}
