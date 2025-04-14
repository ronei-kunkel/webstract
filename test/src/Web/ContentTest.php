<?php

declare(strict_types=1);

use Test\Support\RoneiKunkel\Webstract\Web\FakeContent\FakeContent;

test('should work properly', function () {
	$content = new FakeContent(['test1', 'test2'], 'paragraph');

	expect($content->contextKey())->toBe('content');
	expect($content->tabTitle())->toBe('Content Tab Title');
	expect($content->contentTitle())->toBe('Content Title');
	expect($content->cssPath())->toContain('/FakeContent/FakeContent.css');
	expect($content->jsPath())->toContain('/FakeContent/FakeContent.js');
	expect($content->htmlPath())->toContain('/FakeContent/FakeContent.html');
});
