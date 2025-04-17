<?php

declare(strict_types=1);

namespace Webstract\Session;

interface SessionKeyInterface
{
	/**
	 * @return string
	 */
	public function getName(): string;
}
