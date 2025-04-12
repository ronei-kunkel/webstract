<?php

declare(strict_types=1);

use RoneiKunkel\Webstract\Request\RequestMethod;
use RoneiKunkel\Webstract\Route\RouteDefinition;
use Test\Support\RoneiKunkel\Webstract\Controller\FakeController;

test('Should works properly', function () {
	$definitions = new class implements RouteDefinition {

		public function getMethod(): RequestMethod
		{
			return RequestMethod::OPTIONS;
		}

		public function getPattern(): string
		{
			return '@/?@';
		}

		/**
		 * @return class-string
		 */
		public function getController(): string
		{
			return FakeController::class;
		}
	};

	expect($definitions->getMethod())->toEqual(RequestMethod::OPTIONS);
	expect($definitions->getPattern())->toBe('@/?@');
	expect($definitions->getController())->toBe(FakeController::class);
});
