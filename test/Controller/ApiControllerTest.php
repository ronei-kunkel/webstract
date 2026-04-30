<?php

declare(strict_types=1);

namespace Test\Controller;

use JsonSerializable;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Test\Support\Controller\FakeApiControllerWithContent;
use Test\Support\Controller\FakeApiControllerWithEmptyContent;
use Test\Support\Controller\FakeApiControllerWithoutContent;
use Test\TestCase;
use Webstract\Controller\ApiController;

class ApiControllerTest extends TestCase
{
	private static Psr17Factory $psr17Factory;

	public static function setUpBeforeClass(): void
	{
		self::$psr17Factory = new Psr17Factory();
	}

	public function test_ShouldReturnExpectedResponse_WhenHaveNoContent(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(200)->withHeader('Content-Type', 'application/json')->withBody($stream);

		$controllerResponse = new FakeApiControllerWithoutContent($response, $stream)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('', (string) $controllerResponse->getBody());
	}

	public function test_ShouldReturnExpectedResponse_WhenHaveEmptyContent(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(200)->withHeader('Content-Type', 'application/json')->withBody($stream);

		$controllerResponse = new FakeApiControllerWithEmptyContent($response, $stream)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('', (string) $controllerResponse->getBody());
	}

	public function test_ShouldReturnExpectedResponse_WhenHaveContent(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedStreamContent = json_encode(['message' => 'Hello, World!']);
		$expectedResponse = self::$psr17Factory->createResponse(200)->withHeader('Content-Type', 'application/json')->withBody($stream);

		$controllerResponse = new FakeApiControllerWithContent($response, $stream)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertSame($expectedStreamContent, (string) $stream);
		$this->assertEquals('{"message":"Hello, World!"}', (string) $controllerResponse->getBody());
	}

	public function test_middlewaresContract_ShouldKeepOrderTypeAndDefaultEmptyList(): void
	{
		$serverRequest = $this->createStub(ServerRequestInterface::class);
		$middlewareA = $this->createStub(MiddlewareInterface::class);
		$middlewareB = $this->createStub(MiddlewareInterface::class);
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();

		$controllerWithDefault = new class ($response, $stream) extends ApiController {
			public function middlewares(): array
			{
				return [];
			}

			public function handle(ServerRequestInterface $serverRequest): ResponseInterface
			{
				return $this->createJsonResponse();
			}
		};

		$controllerWithMiddlewares = new class ($response, $stream, $middlewareA, $middlewareB) extends ApiController {
			/** @var MiddlewareInterface[] */
			private array $middlewares;

			public function __construct(ResponseInterface $response, \Psr\Http\Message\StreamInterface $stream, MiddlewareInterface ...$middlewares)
			{
				parent::__construct($response, $stream);
				$this->middlewares = $middlewares;
			}

			public function middlewares(): array
			{
				return $this->middlewares;
			}

			public function handle(ServerRequestInterface $serverRequest): ResponseInterface
			{
				return $this->createJsonResponse();
			}
		};

		$this->assertSame([], $controllerWithDefault->middlewares());
		$this->assertSame([$middlewareA, $middlewareB], $controllerWithMiddlewares->middlewares());
		$this->assertContainsOnlyInstancesOf(MiddlewareInterface::class, $controllerWithMiddlewares->middlewares());
		$controllerWithDefault->handle($serverRequest);
	}

	public function test_createJsonResponse_ShouldHandleEdgeCasePayloads(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();

		$jsonSerializable = new class implements JsonSerializable {
			public function jsonSerialize(): mixed
			{
				return ['status' => 'success'];
			}
		};

		$controller = new class ($response, $stream) extends ApiController {
			public function middlewares(): array { return []; }
			public function handle(ServerRequestInterface $serverRequest): ResponseInterface { return $this->createJsonResponse(); }
			public function exposeCreateJsonResponse(array|JsonSerializable|null $content = null, int $statusCode = 200): ResponseInterface
			{
				return $this->createJsonResponse($content, $statusCode);
			}
		};

		$this->assertSame('', (string) $controller->exposeCreateJsonResponse([])->getBody());
		$this->assertSame('{"status":"success"}', (string) $controller->exposeCreateJsonResponse($jsonSerializable)->getBody());
	}

	public function test_createJsonResponse_ShouldThrowForInvalidTypes(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();

		$controller = new class ($response, $stream) extends ApiController {
			public function middlewares(): array { return []; }
			public function handle(ServerRequestInterface $serverRequest): ResponseInterface { return $this->createJsonResponse(); }
			public function exposeCreateJsonResponseUnsafe(mixed $content): ResponseInterface
			{
				return $this->createJsonResponse($content);
			}
		};

		$this->expectException(\TypeError::class);
		$controller->exposeCreateJsonResponseUnsafe('invalid');
	}
}
