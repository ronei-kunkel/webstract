<?php

declare(strict_types=1);

use Webstract\Route\RoutePathTemplate;

test('should works properly with params', function () {
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


test('should works properly without params', function () {
	$routePathTempalte = new class extends RoutePathTemplate {
		public function getPathFormat(): string
		{
			return '/test';
		}
	};

	expect($routePathTempalte->getPathFormat())->toBe('/test');
	expect($routePathTempalte->renderPath())->toBe('/test');
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
