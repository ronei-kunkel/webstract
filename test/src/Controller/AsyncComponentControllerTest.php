<?php

declare(strict_types=1);

use Test\Support\Controller\FakeAsyncComponentController;
use Test\Support\Session\FakeSessionHandler;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;
use Test\Support\Web\FakeAsyncComponent\FakeNonRenderableAsyncComponent;

test('should works properly with renderable component', function () {
	$controller = new FakeAsyncComponentController(
		$this->response,
		$this->stream,
		new FakeSessionHandler(),
		new TwigTemplateEngineRenderer()
	);

	$response = $controller->handle($this->serverRequest);

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
		new FakeSessionHandler(),
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
		new FakeSessionHandler(),
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
		new FakeSessionHandler(),
		new TwigTemplateEngineRenderer()
	);

	$response = $controller->testCreateRedirectResponse();

	expect((string) $response->getBody())->toBe('');
	expect($response->getHeaders())->toBe(['hx-redirect' => ['/fake/123/opa/123']]);
	expect($response->getStatusCode())->toBe(303);
});
