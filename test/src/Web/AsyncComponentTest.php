<?php

declare(strict_types=1);

use Test\Support\RoneiKunkel\Webstract\Web\FakeAsyncComponent\FakeNonRenderableAsyncComponent;
use Test\Support\RoneiKunkel\Webstract\Web\FakeAsyncComponent\FakeRenderableAsyncComponent;

test('should work properly when renderable', function () {
	$component = new FakeRenderableAsyncComponent('title');

	expect($component->getContext())->toBe(['component' => $component]);
	expect($component->cssPath())->toBeNull();
	expect($component->jsPath())->toBeNull();
	expect($component->jsPath())->toBeNull();
	expect($component->htmlPath())->toContain('/FakeAsyncComponent/FakeRenderableAsyncComponent.html');
	expect($component->shouldRendered())->toBeTrue();
});

test('should work properly when non renderable', function () {
	$component = new FakeNonRenderableAsyncComponent('title');

	expect($component->getContext())->toBe(['component' => $component]);
	expect($component->cssPath())->toBeNull();
	expect($component->jsPath())->toBeNull();
	expect($component->jsPath())->toBeNull();
	expect($component->htmlPath())->toContain('/FakeAsyncComponent/FakeNonRenderableAsyncComponent.html');
	expect($component->shouldRendered())->toBeFalse();
});