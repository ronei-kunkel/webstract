<?php

declare(strict_types=1);

namespace Webstract\Pdf;

abstract class PdfContent
{
	abstract public function getName(): string;
	abstract public function getHtmlPath(): string;
	abstract public function getCssPath(): ?string;

	public function getContext(): array
	{
		return [
			'content' => $this
		];
	}
}
