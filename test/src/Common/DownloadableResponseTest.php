<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Test\Support\RoneiKunkel\Webstract\Controller\FakeApiController;

test('createDownloadResponse works properly', function (string $expectedFilepath, string $expectedContentType, string $expectedBody) {
	$class = new FakeApiController($this->response, $this->stream);
	$filename = basename($expectedFilepath);
	$expectedContentDispositionHeaderValue = sprintf('attachment; filename="%s"', $filename);

	$result = $class->testCreateDownloadResponse($expectedFilepath);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect($result->getHeaderLine('Content-Type'))->toBe($expectedContentType);
	expect($result->getHeaderLine('Content-Disposition'))->toBe($expectedContentDispositionHeaderValue);
	expect($result->getHeaderLine('Content-Length'))->toBe((string) filesize($expectedFilepath));
	expect($result->getHeaderLine('X-Content-Type-Options'))->toBe('nosniff');
	expect($result->getStatusCode())->toBe(200);
	expect((string) $result->getBody())->toBe($expectedBody);
})->with([
	[
		__FILE__,
		'application/x-httpd-php',
		getExpectedBodyFromFilePath(__FILE__),
	],
	[
		__DIR__ . '/../../support/Download/downloadable-empty-file.txt',
		'text/plain',
		'',
	],
	[
		__DIR__ . '/../../support/Download/downloadable-file.txt',
		'text/plain',
		'content inside downloadable file',
	],
]);

test('createDownloadResponse should replace invalid charactere in filename', function () {
	$class = new FakeApiController($this->response, $this->stream);
	$filepath = __DIR__ . '/../../support/Download/downloadable-empty-file-with-special@caractere.txt';
	$expectedContentDispositionHeaderValue = sprintf('attachment; filename="%s"', 'downloadable-empty-file-with-special_caractere.txt');
	$expectedBody = getExpectedBodyFromFilePath($filepath);

	$result = $class->testCreateDownloadResponse($filepath);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect($result->getHeaderLine('Content-Type'))->toBe('text/plain');
	expect($result->getHeaderLine('Content-Disposition'))->toBe($expectedContentDispositionHeaderValue);
	expect($result->getHeaderLine('Content-Length'))->toBe((string) filesize($filepath));
	expect($result->getHeaderLine('X-Content-Type-Options'))->toBe('nosniff');
	expect($result->getStatusCode())->toBe(200);
	expect((string) $result->getBody())->toBe($expectedBody);
});

test('createDownloadResponse should close stream after use', function () {
	// $filepath = 
})->todo('will be implemented using a filewrapper that have oppen read and close responsibility and validation that was closed', 'ronei-kunkel');

test('createDownloadResponse should return 500 when cannot provide attatched file', function (string $filePath) {
	$class = new FakeApiController($this->response, $this->stream);

	$result = $class->testCreateDownloadResponse($filePath);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect($result->getHeaders())->toBe([]);
	expect((string) $result->getBody())->toBe('');
	expect($result->getStatusCode())->toBe(500);
})->with([
	'/missing/path' . __FILE__,
	__DIR__ . '/../../support/Download/undownloadable.file',
]);
