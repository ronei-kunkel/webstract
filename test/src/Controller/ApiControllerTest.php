<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Test\Support\Controller\FakeApiController;
use Test\Support\JsonSerializableClass;
use Test\Support\Pdf\DompdfPdfGenerator;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;

test('should works properly', function () {
	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());
	
	$controller = new FakeApiController($this->response, $this->stream, $pdfGenerator);

	$result = $controller->handle($this->serverRequest);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $result->getBody())->toBe('{"message":"Hello, World!"}');
	expect($result->getHeaders())->toBe(['Content-Type' => ['application/json']]);
	expect($result->getStatusCode())->toBe(200);
});

test('createJsonResponse handles expected entries gracefully', function (mixed $expectedContent, string $expectedBodyResult) {
	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());
	
	$controller = new FakeApiController($this->response, $this->stream, $pdfGenerator);

	$result = $controller->testCreateJsonResponse($expectedContent);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $result->getBody())->toBe($expectedBodyResult);
	expect($result->getHeaders())->toBe(['Content-Type' => ['application/json']]);
	expect($result->getStatusCode())->toBe(200);
})->with([
	[null, ''],
	[['number' => 1], '{"number":1}'],
	[new JsonSerializableClass(), '{"status":"success"}']
]);

test('createJsonResponse throw exception when value not handable', function (mixed $expectedContent) {
	$pdfGenerator = new DompdfPdfGenerator(new TwigTemplateEngineRenderer());
	
	$controller = new FakeApiController($this->response, $this->stream, $pdfGenerator);

	$controller->testCreateJsonResponse($expectedContent);
})->with([1, '', '1', new stdClass()])->throws(TypeError::class);
