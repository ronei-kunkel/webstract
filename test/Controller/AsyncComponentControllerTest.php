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
use Webstract\Web\AsyncComponent;

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

	public function test_createHtmlResponse_ShouldRenderWhenComponentAllowsRendering(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);
		$templateEngine = $this->createMock(TemplateEngineRenderer::class);
		$templateEngine->expects($this->once())->method('render')->with('/fake/path.html', ['name' => 'John'])->willReturn('<h1>rendered</h1>');

		$component = $this->createMock(AsyncComponent::class);
		$component->method('shouldRendered')->willReturn(true);
		$component->method('htmlPath')->willReturn('/fake/path.html');
		$component->method('context')->willReturn(['name' => 'John']);

		$controller = new class ($response, $stream, $session, $templateEngine, $component) extends AsyncComponentController {
			public function __construct($response, $stream, $session, $templateEngine, private readonly AsyncComponent $component) { parent::__construct($response, $stream, $session, $templateEngine); }
			public function middlewares(): array { return []; }
			public function handle(ServerRequestInterface $serverRequest): ResponseInterface { return $this->createHtmlResponse($this->component); }
		};

		$controllerResponse = $controller->handle($serverRequest);
		$this->assertSame(200, $controllerResponse->getStatusCode());
		$this->assertSame('text/html; charset=utf-8', $controllerResponse->getHeaderLine('Content-Type'));
		$this->assertSame('<h1>rendered</h1>', (string) $controllerResponse->getBody());
	}

	public function test_createHtmlResponse_ShouldNotRenderWhenComponentDisablesRendering(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);
		$templateEngine = $this->createMock(TemplateEngineRenderer::class);
		$templateEngine->expects($this->never())->method('render');
		$tempFile = tempnam(sys_get_temp_dir(), 'async-component');
		file_put_contents($tempFile, '<h1>raw</h1>');

		$component = $this->createMock(AsyncComponent::class);
		$component->method('shouldRendered')->willReturn(false);
		$component->method('htmlPath')->willReturn($tempFile);
		$component->method('context')->willReturn([]);

		$controller = new class ($response, $stream, $session, $templateEngine, $component) extends AsyncComponentController {
			public function __construct($response, $stream, $session, $templateEngine, private readonly AsyncComponent $component) { parent::__construct($response, $stream, $session, $templateEngine); }
			public function middlewares(): array { return []; }
			public function handle(ServerRequestInterface $serverRequest): ResponseInterface { return $this->createHtmlResponse($this->component); }
		};

		$controllerResponse = $controller->handle($serverRequest);
		$this->assertSame(200, $controllerResponse->getStatusCode());
		$this->assertSame('<h1>raw</h1>', (string) $controllerResponse->getBody());
		unlink($tempFile);
	}
}
