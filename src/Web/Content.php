<?php

declare(strict_types=1);

namespace Webstract\Web;

abstract class Content
{
	abstract public function tabTitle(): string;
	abstract public function contentTitle(): string;
	abstract public function cssPath(): ?string;
	abstract public function jsPath(): ?string;
	abstract public function htmlPath(): string;

	public function contextKey(): string
	{
		return 'content';
	}

	public function context(): array
	{
		return [
			$this->contextKey() => $this
		];
	}
}
