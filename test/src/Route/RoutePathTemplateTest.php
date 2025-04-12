<?php

declare(strict_types=1);

use RoneiKunkel\Webstract\Route\RoutePathTemplate;

test('should works properly', function () {
	$routePathTempalte = new class extends RoutePathTemplate {
		public function getPathFormat(): string
		{
			return '/test/%s/test';
		}
	};

	$routePathTempalte->withPathParams('1');

	expect($routePathTempalte->getPathFormat())->toBe('/test/%s/test');
	expect($routePathTempalte->renderPath())->toBe('/test/1/test');
});


test('should throw exception when pass less parameters than was needed', function () {
	$routePathTempalte = new class extends RoutePathTemplate {
		public function getPathFormat(): string
		{
			return '/test/%s/test/%s';
		}
	};

	$routePathTempalte->withPathParams('1');

	$routePathTempalte->renderPath();
})->throws(RuntimeException::class, 'Route has unfilled resources. Output: /test/1/test/%s');

test('should throw exception when pass route without params when it needed', function () {
	$routePathTempalte = new class extends RoutePathTemplate {
		public function getPathFormat(): string
		{
			return '/test/%s/test/%s';
		}
	};

	$routePathTempalte->renderPath();
})->throws(RuntimeException::class, 'Try render route with format "/test/%s/test/%s" before define params with $this->withParams(...$params) method');
