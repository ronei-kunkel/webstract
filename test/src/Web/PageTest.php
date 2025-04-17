<?php

declare(strict_types=1);

use Test\Support\Web\FakeContent\FakeContent;
use Test\Support\Web\FakePage\FakePage;

test('should work properly', function () {
	$content = new FakeContent(['test1', 'test2'], 'paragraph');
	$page = new FakePage($content);
	expect($page->getContext())->toBe(['template' => $page, 'content' => $content]);
	expect($page->tabTitle())->toBe('Tab title page template');
	expect($page->cssPath())->toContain('/FakePage/FakePage.css');
	expect($page->jsPath())->toContain('/FakePage/FakePage.js');
	expect($page->htmlPath())->toContain('/FakePage/FakePage.html');
});
