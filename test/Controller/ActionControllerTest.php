<?php

declare(strict_types=1);

namespace Test\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionClass;
use Test\Support\Controller\FakeActionControllerThatCreateRedirectResponse;
use Test\TestCase;
use Webstract\Controller\ActionController;
use Webstract\Controller\Traits\RedirectableResponse;
use Webstract\Session\SessionHandler;

class ActionControllerTest extends TestCase
{
	private static Psr17Factory $psr17Factory;

	public static function setUpBeforeClass(): void
	{
		self::$psr17Factory = new Psr17Factory();
	}

	public function test_AssertThatUseExpectedTraits(): void
	{
		$expectedTraits = [RedirectableResponse::class];
		$reflection = new ReflectionClass(ActionController::class);
		$traits = $reflection->getTraitNames();
		$this->assertSame($expectedTraits, $traits);
	}

	public function test_AssertMethod_createRedirectResponse_HaveExpectedBehavior(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = $this->createStub(StreamInterface::class);
		$session = $this->createStub(SessionHandler::class);
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$expectedResponse = self::$psr17Factory->createResponse(303)->withHeader('Location', '/');

		$controllerResponse = new FakeActionControllerThatCreateRedirectResponse($response, $stream, $session)->handle($serverRequest);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals('', (string) $controllerResponse->getBody());
		$this->assertSame(303, $controllerResponse->getStatusCode());
		$this->assertSame('/', $controllerResponse->getHeaderLine('Location'));
	}
}
