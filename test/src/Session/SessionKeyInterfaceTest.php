<?php

declare(strict_types=1);

use RoneiKunkel\Webstract\Session\SessionKeyInterface;
use Test\Support\RoneiKunkel\Webstract\Session\FakeSessionKey;

test('getName should implemented and returned expected value', function (SessionKeyInterface $key, string $expectedName) {
	$name = $key->getName();

	expect($name)->toBe($expectedName);
})->with([
	[FakeSessionKey::TEST, 'test'],
	[FakeSessionKey::ANOTHER_TEST, 'another_test'],
]);
