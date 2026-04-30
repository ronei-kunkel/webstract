<?php

declare(strict_types=1);

namespace Test\Contracts;

use RuntimeException;
use Test\Support\Contracts\Pdf\FakePdfGenerator;
use Test\Support\Contracts\TemplateEngine\FakeTemplateEngineRenderer;
use Test\Support\Pdf\FakePdfContent;
use Test\TestCase;

class RenderingAndPdfContractsTest extends TestCase
{
	public function test_ShouldRenderTemplateOutput_WhenPathAndContextAreProvided(): void
	{
		$renderer = new FakeTemplateEngineRenderer();
		$this->assertStringContainsString('/template.html', $renderer->render('/template.html', ['a' => 1]));
	}

	public function test_ShouldThrowException_WhenTemplateRendererFails(): void
	{
		$renderer = new FakeTemplateEngineRenderer();
		$renderer->shouldThrow = true;
		$this->expectException(RuntimeException::class);
		$renderer->render('/template.html', []);
	}

	public function test_ShouldGeneratePdfContent_WhenPdfContentIsValid(): void
	{
		$generator = new FakePdfGenerator(new FakeTemplateEngineRenderer());
		$output = $generator->generateContent(new FakePdfContent('Hello'));
		$this->assertStringStartsWith('PDF:', $output);
	}

	public function test_ShouldThrowException_WhenPdfGenerationFails(): void
	{
		$generator = new FakePdfGenerator(new FakeTemplateEngineRenderer());
		$generator->shouldThrow = true;
		$this->expectException(RuntimeException::class);
		$generator->generateContent(new FakePdfContent('Hello'));
	}
}
