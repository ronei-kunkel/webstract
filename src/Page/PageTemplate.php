<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Page;

abstract class PageTemplate
{
	abstract public function tabTitle(): string;
	abstract public function cssPath(): string;
	abstract public function jsPath(): string;
	abstract public function htmlPath(): string;
	protected function contextKey(): string
	{
		return 'template';
	}

	public function getContext(): array
	{
		return [
			$this->contextKey() => $this
		];
	}
}
