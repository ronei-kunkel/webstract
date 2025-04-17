<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Controller\PageController;
use Test\Support\Session\FakeSessionHandler;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;
use Test\Support\Web\FakeContent\FakeContent;
use Test\Support\Web\FakePage\FakePage;

test('Page controller should works properly', function () {
	$pageController = new class($this->response, $this->stream, new FakeSessionHandler(), new TwigTemplateEngineRenderer()) extends PageController {
		public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
		{
			$content = new FakeContent(
				['test1', 'test2'],
				'paragraph',
			);
			$page = new FakePage($content);
			return $this->createHtmlResponse($page);
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
				<style>* {
			background-color: blue;
		}
		</style>
			</head>
		<body>
			<h1>Content Title</h1>
		<p>paragraph</p>
		<ul>
				<li>test1</li>
				<li>test2</li>
			</ul>	<script>var fakeDate = new Date();
		</script>
				<script>var fakeContentDate = new Date();
		</script>
				<script defer src="https://unpkg.com/htmx.org@2.0.4" integrity="sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+" crossorigin="anonymous"></script>
		</body>
		</html>
		HTML
	);
	expect($response->getHeaders())->toBe(['Content-Type' => ['text/html']]);
	expect($response->getStatusCode())->toBe(200);
})->only();
