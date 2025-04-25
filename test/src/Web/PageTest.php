<?php

declare(strict_types=1);

use Test\Support\Web\FakeComponent\FakeComponent;
use Test\Support\Web\FakeContent\FakeContent;
use Test\Support\Web\FakePage\FakePage;

test('should work properly', function () {
	$content = new FakeContent(['test1', 'test2'], 'paragraph');
	$fakeComponent = new FakeComponent('Component text');
	$page = new FakePage(
		$content,
		$fakeComponent,
	);
	expect($page->getContext())->toBe(['template' => $page, 'content' => $content, 'fakeComponent' => $fakeComponent]);
	expect($page->tabTitle())->toBe('Tab title page template');
	expect($page->cssPath())->toContain('/FakePage/FakePage.css');
	expect($page->jsPath())->toContain('/FakePage/FakePage.js');
	expect($page->htmlPath())->toContain('/FakePage/FakePage.html');
});
