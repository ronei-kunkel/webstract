<?php

declare(strict_types=1);

namespace Test\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Test\Support\Controller\FakePageController;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;
use Test\TestCase;
use Webstract\Session\SessionHandler;

class PageControllerTest extends TestCase
{
	private static Psr17Factory $psr17Factory;

	public static function setUpBeforeClass(): void
	{
		self::$psr17Factory = new Psr17Factory();
	}

	public function test_AssertMethod_createHtmlResponse_ReturnExpectedResponse(): void
	{
		$response = self::$psr17Factory->createResponse();
		$stream = self::$psr17Factory->createStream();
		$session = $this->createStub(SessionHandler::class);
		$templateEngine = new TwigTemplateEngineRenderer();
		$serverRequest = $this->createStub(ServerRequestInterface::class);

		$controllerResponse = new FakePageController($response, $stream, $session, $templateEngine)->handle($serverRequest);

		$expectedResponse = self::$psr17Factory->createResponse()
			->withHeader('Content-Type', 'text/html')
			->withBody($stream);

		$this->assertEquals($expectedResponse, $controllerResponse);
		$this->assertEquals(
			<<<HTML
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Tab title page template</title>
				<style>* {
				background-color: red;
			}
			</style>
					<style>* {
				background-color: blue;
			}
			</style>
				</head>
			<body>
				<h1>Content Title</h1>
			<p>paragraph</p>
			<ul>
					<li>a</li>
					<li>b</li>
				</ul>	<script>var fakeDate = new Date();
			</script>
					<script>var fakeContentDate = new Date();
			</script>
					<textarea>
				Component text
			</textarea>
				<script defer src="https://unpkg.com/htmx.org@2.0.4" integrity="sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+" crossorigin="anonymous"></script>
			</body>
			</html>
			HTML,
			(string) $controllerResponse->getBody());
	}
}
