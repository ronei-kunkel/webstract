<?php

declare(strict_types=1);

use RoneiKunkel\Webstract\Page\PageTemplate;

test('should work properly', function () {
	$template = new class extends PageTemplate {
		public function tabTitle(): string
		{
			return 'tab title';
		}
		public function cssPath(): string
		{
			return __DIR__ . '/style.css';
		}
		public function jsPath(): string
		{
			return __DIR__ . '/script.js';
		}
		public function htmlPath(): string
		{
			return __DIR__ . '/page.html';
		}
	};

	expect($template->getContext())->toHaveKeys(['template']);
	expect($template->getContext()['template'])->toBeObject();
	expect($template->tabTitle())->toBe('tab title');
	expect($template->cssPath())->toBe(__DIR__ . '/style.css');
	expect($template->jsPath())->toBe(__DIR__ . '/script.js');
	expect($template->htmlPath())->toBe(__DIR__ . '/page.html');
});


test('should work properly with values', function () {
	$template = new class('value') extends PageTemplate {

		public function __construct(
			public readonly string $key,
		) {}

		public function tabTitle(): string
		{
			return 'tab title';
		}
		public function cssPath(): string
		{
			return __DIR__ . '/style.css';
		}
		public function jsPath(): string
		{
			return __DIR__ . '/script.js';
		}
		public function htmlPath(): string
		{
			return __DIR__ . '/page.html';
		}
	};

	expect($template->getContext())->toHaveKeys(['template']);
	expect($template->getContext()['template'])->toBeObject();
	expect($template->getContext()['template'])->toHaveProperties(['key']);
	expect($template->getContext()['template']->key)->toBe('value');
	expect($template->tabTitle())->toBe('tab title');
	expect($template->cssPath())->toBe(__DIR__ . '/style.css');
	expect($template->jsPath())->toBe(__DIR__ . '/script.js');
	expect($template->htmlPath())->toBe(__DIR__ . '/page.html');
});
