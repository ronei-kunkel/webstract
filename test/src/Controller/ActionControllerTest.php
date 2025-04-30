<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Test\Support\Controller\FakeActionController;
use Test\Support\Pdf\DompdfPdfGenerator;
use Test\Support\Route\FakeApiControllerRoute;
use Test\Support\Session\FakeSessionHandler;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;

test('should works properly', function () {
	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());

	$controller = new FakeActionController($this->response, $this->stream, new FakeSessionHandler(), $pdfGenerator);

	$result = $controller->handle($this->serverRequest);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect($result->getBody()->getContents())->toBe('');
	expect($result->getHeaders())->toBe([]);
	expect($result->getStatusCode())->toBe(200);
});

test('should throw exception when pass less parameters than was needed', function () {
	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());

	$controller = new FakeActionController($this->response, $this->stream, new FakeSessionHandler(), $pdfGenerator);

	$controller->testCreateRedirectResponse((new FakeApiControllerRoute())->withPathParams('1234'));
})->throws(RuntimeException::class, 'Route has unfilled resources. Output: /fake/1234/opa/%s');
