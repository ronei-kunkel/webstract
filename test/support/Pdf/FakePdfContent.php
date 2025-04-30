<?php

declare(strict_types=1);

namespace Test\Support\Pdf;

use Webstract\Pdf\PdfContent;

final class FakePdfContent extends PdfContent
{
	public function __construct(
		public readonly string $a
	) {}

	public function getName(): string
	{
		return 'Fake Pdf Name';
	}

	public function getHtmlPath(): string
	{
		return __DIR__ . '/FakePdfContent.html';
	}

	public function getCssPath(): ?string
	{
		return null;
	}
}
