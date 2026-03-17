<?php

declare(strict_types=1);

namespace Test\Request;

use PHPUnit\Framework\Attributes\DataProvider;
use Test\TestCase;
use Webstract\Request\RequestMethod;

class RequestMethodTest extends TestCase
{
	public static function provideAllCases(): iterable
	{
		yield [[
			RequestMethod::GET,
			RequestMethod::POST,
			RequestMethod::PUT,
			RequestMethod::DELETE,
			RequestMethod::PATCH,
			RequestMethod::HEAD,
			RequestMethod::OPTIONS,
			RequestMethod::CONNECT,
			RequestMethod::TRACE,
		]];
	}

	#[DataProvider('provideAllCases')]
	public function test_AssertAllMethods(array $allCases): void
	{
		$cases = RequestMethod::cases();

		$this->assertEquals($allCases, $cases);
	}


	public static function provideAllCasesAndValues(): iterable
	{
		yield [RequestMethod::GET, 'GET'];
		yield [RequestMethod::POST, 'POST'];
		yield [RequestMethod::PUT, 'PUT'];
		yield [RequestMethod::DELETE, 'DELETE'];
		yield [RequestMethod::PATCH, 'PATCH'];
		yield [RequestMethod::HEAD, 'HEAD'];
		yield [RequestMethod::OPTIONS, 'OPTIONS'];
		yield [RequestMethod::CONNECT, 'CONNECT'];
		yield [RequestMethod::TRACE, 'TRACE'];
	}

	#[DataProvider('provideAllCasesAndValues')]
	public function test_AssertAllValues(RequestMethod $requestMethod, string $expectedValue): void
	{
		$this->assertEquals($expectedValue, $requestMethod->value);
	}
}
