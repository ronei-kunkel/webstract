<?php

declare(strict_types=1);

namespace Webstract\Web;

abstract class Page
{
	public function __construct(
		public readonly Content $content,
	) {}

	abstract public function tabTitle(): string;
	abstract public function cssPath(): string;
	abstract public function jsPath(): string;
	abstract public function htmlPath(): string;
	protected function contextKey(): string
	{
		return 'page';
	}

	public function context(): array
	{
		return [
			$this->contextKey() => $this,
			...$this->content->context()
		];
	}
}
