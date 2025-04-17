<?php

declare(strict_types=1);

namespace Webstract\Web;

abstract class AsyncComponent
{
	// @todo implement a way to handle css and js content with htmx hooks after receive the response
	abstract public function cssPath(): ?string;
	abstract public function jsPath(): ?string;

	abstract public function htmlPath(): string;
	abstract public function shouldRendered(): bool;

	protected function contextKey(): string
	{
		return 'component';
	}

	public function getContext(): array
	{
		return [
			$this->contextKey() => $this
		];
	}
}
