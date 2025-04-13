<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RoneiKunkel\Webstract\Controller\PageController;
use Test\Support\RoneiKunkel\Webstract\Page\FakePageTemplate;
use Test\Support\RoneiKunkel\Webstract\Session\FakeSessionManager;
use Test\Support\RoneiKunkel\Webstract\TemplateEngine\TwigTemplateEngineRenderer;

test('Page controller should works properly', function () {
	$pageController = new class($this->response, $this->stream, new FakeSessionManager(), new TwigTemplateEngineRenderer()) extends PageController {
		public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
		{
			$template = new FakePageTemplate('the variable template.test should placed here');
			return $this->createHtmlResponse($template);
		}
	};

	$response = $pageController($this->serverRequest);

	expect((string) $response->getBody())->toBe(
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
		</head>
		<body>
			<h1>the variable template.test should placed here</h1>
			<script>var fakeDate = new Date();
		</script>
		</body>
		</html>

		HTML
	);
	expect($response->getHeaders())->toBe(['Content-Type' => ['text/html']]);
	expect($response->getStatusCode())->toBe(200);
});
