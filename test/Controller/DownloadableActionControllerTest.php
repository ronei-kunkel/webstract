<?php

declare(strict_types=1);

namespace Test\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use Test\Support\Controller\FakeDownloadableActionControllerThatCreate500ResponseCausedByFileWithoutExtension;
use Test\Support\Controller\FakeDownloadableActionControllerThatCreate500ResponseCausedByUnableToOpenFile;
use Test\Support\Controller\FakeDownloadableActionControllerWithDownloadableAttachment;
use Test\Support\Controller\FakeDownloadableActionControllerWithDownloadablePdfAttachment;
use Test\TestCase;
use Webstract\Controller\DownloadableActionController;
use Webstract\Controller\Traits\DownloadableResponse;
use Webstract\Pdf\PdfGenerator;
use Webstract\Session\SessionHandler;

use function PHPUnit\Framework\once;

class DownloadableActionControllerTest extends TestCase
{
	private static Psr17Factory $psr17Factory;

	public static function setUpBeforeClass(): void
	{
		self::$psr17Factory = new Psr17Factory();
	}

	public function test_AssertThatUseExpectedTraits(): void
	{
		$expectedTraits = [DownloadableResponse::class];
		$reflection = new ReflectionClass(DownloadableActionController::class);
		$traits = $reflection->getTraitNames();
		$this->assertSame($expectedTraits, $traits);
	}

	public function test_AssertMethod_createDownloadResponse_Return500WhenFileIsNotOpened(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$pdfGenerator = $this->createStub(PdfGenerator::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(500);

		$controllerResponse = new FakeDownloadableActionControllerThatCreate500ResponseCausedByUnableToOpenFile($response, $stream, $session, $pdfGenerator)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('', (string) $controllerResponse->getBody());
	}

	public function test_AssertMethod_createDownloadResponse_Return500WhenFileHaveNoExtension(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$pdfGenerator = $this->createStub(PdfGenerator::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(500);

		$controllerResponse = new FakeDownloadableActionControllerThatCreate500ResponseCausedByFileWithoutExtension($response, $stream, $session, $pdfGenerator)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('', (string) $controllerResponse->getBody());
	}

	public function test_AssertMethod_createDownloadResponse_ReturnDownloadableAttachment(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$pdfGenerator = $this->createStub(PdfGenerator::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$controllerResponse = new FakeDownloadableActionControllerWithDownloadableAttachment($response, $stream, $session, $pdfGenerator)->handle($serverRequest);

		$expectedResponse = self::$psr17Factory->createResponse(200)
			->withHeader('Content-Type', 'text/plain')
			->withHeader('Content-Disposition', 'attachment; filename="file-with-content.txt"')
			->withHeader('Content-Length', '28')
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withBody($stream);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('content of file with content', (string) $controllerResponse->getBody());
	}

	public function test_AssertMethod_createPdfContentDownloadableResponse_ReturnDownloadablePdfAttachment(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);
		$pdfGenerator = $this->createMock(PdfGenerator::class);
		$pdfGenerator->expects(once())->method('generateContent')->willReturn(
			<<<PDF
			%PDF-1.7
			%%EOF

			PDF
		);

		$controllerResponse = new FakeDownloadableActionControllerWithDownloadablePdfAttachment($response, $stream, $session, $pdfGenerator)->handle($serverRequest);

		$expectedResponse = self::$psr17Factory->createResponse(200)
			->withHeader('Content-Type', 'application/pdf')
			->withHeader('Content-Disposition', 'attachment; filename="Fake_Pdf_Name.pdf"')
			->withHeader('Content-Length', '15')
			->withHeader('X-Content-Type-Options', 'nosniff')
			->withBody($stream);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals(
			<<<PDF
			%PDF-1.7
			%%EOF

			PDF, (string) $controllerResponse->getBody());
	}
}
