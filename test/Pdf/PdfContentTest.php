<?php

declare(strict_types=1);

namespace Test\Pdf;

use Test\Support\Pdf\FakePdfContent;
use Test\TestCase;

class PdfContentTest extends TestCase
{
	public function test_ContextShouldReturnExpectedValue(): void
	{
		$expectedContext = [
			'content' => new FakePdfContent('text')
		];
		$content = new FakePdfContent('text');

		$this->assertEquals($expectedContext, $content->getContext());
	}
}
