<?php

declare(strict_types=1);

use Test\Support\Pdf\FakePdfContent;
use Test\Support\Web\FakeContent\FakeContent;

test('should work properly', function () {
	$content = new FakePdfContent('a');

	expect($content->getName())->toBe('Fake Pdf Name');
	expect($content->getContext())->toBe(['content' => $content]);
	expect($content->getCssPath())->toBeNull();
	expect($content->getHtmlPath())->toContain('/Pdf/FakePdfContent.html');
});
