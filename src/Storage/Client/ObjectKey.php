<?php

declare(strict_types=1);

namespace Webstract\Storage\Client;

final class ObjectKey
{
	public function __construct(
		private string $value
	) {}

	public function getValue(): string
	{
		return $this->value;
	}
}
