<?php

declare(strict_types=1);

use Webstract\Request\RequestMethod;

test('should map all methods', function (array $expectedCases) {
	expect(RequestMethod::cases())->toBe($expectedCases);
})->with([
	[
		[
			RequestMethod::GET,
			RequestMethod::POST,
			RequestMethod::PUT,
			RequestMethod::DELETE,
			RequestMethod::PATCH,
			RequestMethod::HEAD,
			RequestMethod::OPTIONS,
			RequestMethod::CONNECT,
			RequestMethod::TRACE,
		]
	]
]);

it('cases have expected values', function (RequestMethod $case, string $expectedValue) {
	expect($case->value)->toBe($expectedValue);
})->with([
	[RequestMethod::GET, 'GET'],
	[RequestMethod::POST, 'POST'],
	[RequestMethod::PUT, 'PUT'],
	[RequestMethod::DELETE, 'DELETE'],
	[RequestMethod::PATCH, 'PATCH'],
	[RequestMethod::HEAD, 'HEAD'],
	[RequestMethod::OPTIONS, 'OPTIONS'],
	[RequestMethod::CONNECT, 'CONNECT'],
	[RequestMethod::TRACE, 'TRACE'],
]);
