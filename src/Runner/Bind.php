<?php

declare(strict_types=1);

namespace Webstract\Runner;

final class Bind
{
	private ?array $properties = null;

	public function __construct(
		private readonly string $interface,
		private readonly string|object $implementation,
	) {}

	public function withProperties(string ...$property): self
	{
		$this->properties = [...$property];
		return $this;
	}

	public function getInterface(): string
	{
		return $this->interface;
	}

	public function implementationIsObject(): bool
	{
		return is_object($this->implementation);
	}

	public function getImplementation(): string|object
	{
		return $this->implementation;
	}

	public function hasProperties(): bool
	{
		return $this->properties !== null;
	}

	public function getProperties(): array
	{
		return $this->properties;
	}
}
