<?php

declare(strict_types=1);

namespace Webstract\Web;

abstract class AsyncComponent extends Component
{
	// @todo implement a way to handle css and js content with htmx hooks after receive the response of async component
	abstract public function shouldRendered(): bool;

	public function contextKey(): string
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
