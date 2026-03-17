<?php

declare(strict_types=1);

namespace Test\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use Test\Support\Controller\FakeAsyncComponentControllerThatCreateAsyncRedirectResponse;
use Test\Support\Controller\FakeAsyncComponentControllerThatCreateEmptyResponse;
use Test\Support\Controller\FakeAsyncComponentControllerThatCreateNotRenderedHtmlResponse;
use Test\Support\Controller\FakeAsyncComponentControllerThatCreateRenderedHtmlResponse;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;
use Test\TestCase;
use Webstract\Controller\AsyncComponentController;
use Webstract\Controller\Traits\AsyncRedirectableResponse;
use Webstract\Session\SessionHandler;
use Webstract\TemplateEngine\TemplateEngineRenderer;

class AsyncComponentControllerTest extends TestCase
{
	private static Psr17Factory $psr17Factory;

	public static function setUpBeforeClass(): void
	{
		self::$psr17Factory = new Psr17Factory();
	}

	public function test_AssertThatUseExpectedTraits(): void
	{
		$expectedTraits = [AsyncRedirectableResponse::class];
		$reflection = new ReflectionClass(AsyncComponentController::class);
		$traits = $reflection->getTraitNames();
		$this->assertSame($expectedTraits, $traits);
	}

	public function test_AssertMethod_createRedirectResponse_HaveExpectedBehavior(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$templateEngine = $this->createStub(TemplateEngineRenderer::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(303)->withHeader('hx-redirect', '/');

		$controllerResponse = new FakeAsyncComponentControllerThatCreateAsyncRedirectResponse($response, $stream, $session, $templateEngine)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('', (string) $controllerResponse->getBody());
	}

	public function test_AssertMethod_createEmptyResponse_HaveExpectedBehavior(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$templateEngine = $this->createStub(TemplateEngineRenderer::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(200)->withHeader('Content-Type', 'text/html; charset=utf-8')->withBody($stream);

		$controllerResponse = new FakeAsyncComponentControllerThatCreateEmptyResponse($response, $stream, $session, $templateEngine)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('', (string) $controllerResponse->getBody());
	}

	public function test_AssertMethod_createNotRenderedHtmlResponse_HaveExpectedBehavior(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$templateEngine = new TwigTemplateEngineRenderer();
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(200)->withHeader('Content-Type', 'text/html; charset=utf-8')->withBody($stream);

		$controllerResponse = new FakeAsyncComponentControllerThatCreateNotRenderedHtmlResponse($response, $stream, $session, $templateEngine)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('<h1>{{ component.title }}</h1>', (string) $controllerResponse->getBody());
	}

	public function test_AssertMethod_createRenderedHtmlResponse_HaveExpectedBehavior(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$templateEngine = new TwigTemplateEngineRenderer();
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(200)->withHeader('Content-Type', 'text/html; charset=utf-8')->withBody($stream);

		$controllerResponse = new FakeAsyncComponentControllerThatCreateRenderedHtmlResponse($response, $stream, $session, $templateEngine)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('<h1>RENDERABLE</h1>', (string) $controllerResponse->getBody());
	}
}
