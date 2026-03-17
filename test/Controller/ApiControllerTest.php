<?php

declare(strict_types=1);

namespace Test\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Test\Support\Controller\FakeApiControllerWithContent;
use Test\Support\Controller\FakeApiControllerWithEmptyContent;
use Test\Support\Controller\FakeApiControllerWithoutContent;
use Test\TestCase;

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

	
}

// test('should works properly', function () {
// 	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());
	
// 	$controller = new FakeApiController($this->response, $this->stream, $pdfGenerator);

// 	$result = $controller->handle($this->serverRequest);

// 	expect($result)->toBeInstanceOf(ResponseInterface::class);
// 	expect((string) $result->getBody())->toBe('{"message":"Hello, World!"}');
// 	expect($result->getHeaders())->toBe(['Content-Type' => ['application/json']]);
// 	expect($result->getStatusCode())->toBe(200);
// });

// test('createJsonResponse handles expected entries gracefully', function (mixed $expectedContent, string $expectedBodyResult) {
// 	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());
	
// 	$controller = new FakeApiController($this->response, $this->stream, $pdfGenerator);

// 	$result = $controller->testCreateJsonResponse($expectedContent);

// 	expect($result)->toBeInstanceOf(ResponseInterface::class);
// 	expect((string) $result->getBody())->toBe($expectedBodyResult);
// 	expect($result->getHeaders())->toBe(['Content-Type' => ['application/json']]);
// 	expect($result->getStatusCode())->toBe(200);
// })->with([
// 	[null, ''],
// 	[['number' => 1], '{"number":1}'],
// 	[new JsonSerializableClass(), '{"status":"success"}']
// ]);

// test('createJsonResponse throw exception when value not handable', function (mixed $expectedContent) {
// 	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());
	
// 	$controller = new FakeApiController($this->response, $this->stream, $pdfGenerator);

// 	$controller->testCreateJsonResponse($expectedContent);
// })->with([1, '', '1', new stdClass()])->throws(TypeError::class);
