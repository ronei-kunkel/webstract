<?php

declare(strict_types=1);

use Test\Support\RoneiKunkel\Webstract\Controller\FakeAsyncComponentController;
use Test\Support\RoneiKunkel\Webstract\Session\FakeSessionManager;
use Test\Support\RoneiKunkel\Webstract\TemplateEngine\TwigTemplateEngineRenderer;
use Test\Support\RoneiKunkel\Webstract\Web\FakeAsyncComponent\FakeNonRenderableAsyncComponent;

test('should works properly with renderable component', function () {
	$controller = new FakeAsyncComponentController(
		$this->response,
		$this->stream,
		new FakeSessionManager(),
		new TwigTemplateEngineRenderer()
	);

	$response = $controller($this->serverRequest);

	expect((string) $response->getBody())->toBe(
		<<<HTML
		<h1>FAKEE</h1>
		HTML
	);
	expect($response->getHeaders())->toBe(['Content-Type' => ['text/html; charset=utf-8']]);
	expect($response->getStatusCode())->toBe(200);
});

test('should works properly with non renderable component', function () {
	$controller = new FakeAsyncComponentController(
		$this->response,
		$this->stream,
		new FakeSessionManager(),
		new TwigTemplateEngineRenderer()
	);

	$component = new FakeNonRenderableAsyncComponent('foo');

	$response = $controller->testCreateHtmlResponse($component);

	expect((string) $response->getBody())->toBe(
		<<<HTML
		<h1>{{ component.title }}</h1>
		HTML
	);
	expect($response->getHeaders())->toBe(['Content-Type' => ['text/html; charset=utf-8']]);
	expect($response->getStatusCode())->toBe(200);
});

test('should return empty response', function () {
	$controller = new FakeAsyncComponentController(
		$this->response,
		$this->stream,
		new FakeSessionManager(),
		new TwigTemplateEngineRenderer()
	);

	$response = $controller->testCreateEmptyResponse();

	expect((string) $response->getBody())->toBe('');
	expect($response->getHeaders())->toBe(['Content-Type' => ['text/html; charset=utf-8']]);
	expect($response->getStatusCode())->toBe(200);
});

test('should return redirect response', function () {
	$controller = new FakeAsyncComponentController(
		$this->response,
		$this->stream,
		new FakeSessionManager(),
		new TwigTemplateEngineRenderer()
	);

	$response = $controller->testCreateRedirectResponse();

	expect((string) $response->getBody())->toBe('');
	expect($response->getHeaders())->toBe(['hx-redirect' => ['/fake/123/opa/123']]);
	expect($response->getStatusCode())->toBe(303);
});
