<?php

declare(strict_types=1);

use Webstract\Session\SessionKeyInterface;
use Test\Support\Session\FakeSessionKey;

test('getName should implemented and returned expected value', function (SessionKeyInterface $key, string $expectedName) {
	$name = $key->getName();

	expect($name)->toBe($expectedName);
})->with([
	[FakeSessionKey::TEST, 'test'],
	[FakeSessionKey::ANOTHER_TEST, 'another_test'],
]);
