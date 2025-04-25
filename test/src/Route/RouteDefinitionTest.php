<?php

declare(strict_types=1);

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Test\Support\Controller\FakeController;

test('Should works properly', function () {
	$definitions = new class implements RouteDefinition {

		public static function getMethod(): RequestMethod
		{
			return RequestMethod::OPTIONS;
		}

		public static function getPattern(): string
		{
			return '@/?@';
		}

		/**
		 * @return class-string
		 */
		public static function getController(): string
		{
			return FakeController::class;
		}
	};

	expect($definitions->getMethod())->toEqual(RequestMethod::OPTIONS);
	expect($definitions->getPattern())->toBe('@/?@');
	expect($definitions->getController())->toBe(FakeController::class);
});
