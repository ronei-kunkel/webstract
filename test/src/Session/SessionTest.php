<?php

declare(strict_types=1);

use Test\Support\RoneiKunkel\Webstract\Session\FakeSessionKey;
use RoneiKunkel\Webstract\Session\Exception\SessionProviderUnreachableException;
use RoneiKunkel\Webstract\Session\Exception\SessionValueNotFoundException;
use Test\Support\RoneiKunkel\Webstract\Session\FakeSessionManager;

beforeEach(function () {
	$this->session = new FakeSessionManager();
	$this->session->create();
});

afterEach(fn() => $this->session->destroy());

test('it can create and destroy a session', function () {
	$this->session->create();
	expect($this->session->has(FakeSessionKey::TEST))->toBeFalse();

	$this->session->destroy();
	expect(fn() => $this->session->has(FakeSessionKey::TEST))->toThrow(SessionProviderUnreachableException::class);
});

test('it can set and get a value in the session', function () {
	$this->session->set(FakeSessionKey::TEST, 'value');
	expect($this->session->get(FakeSessionKey::TEST))->toBe('value');
});

test('it throws an exception when accessing a nonexistent key', function () {
	expect(fn() => $this->session->get(FakeSessionKey::ANOTHER_TEST))->toThrow(SessionValueNotFoundException::class);
});

test('it can check if a key exists', function () {
	$this->session->set(FakeSessionKey::TEST, 'value');
	expect($this->session->has(FakeSessionKey::TEST))->toBeTrue();
	expect($this->session->has(FakeSessionKey::ANOTHER_TEST))->toBeFalse();
});

test('it can delete a key from the session', function () {
	$this->session->set(FakeSessionKey::TEST, 'value');
	$this->session->delete(FakeSessionKey::TEST);

	expect($this->session->has(FakeSessionKey::TEST))->toBeFalse();
});

test('it can consume a key', function () {
	$this->session->set(FakeSessionKey::TEST, 'value');
	$value = $this->session->consume(FakeSessionKey::TEST);

	expect($value)->toBe('value');
	expect($this->session->has(FakeSessionKey::TEST))->toBeFalse();
});

test('it throws an exception when consuming a nonexistent key', function () {
	expect(fn() => $this->session->consume(FakeSessionKey::ANOTHER_TEST))->toThrow(SessionValueNotFoundException::class);
});

test('it can get or return a default value', function () {
	expect($this->session->getOrDefault(FakeSessionKey::TEST, 'default'))->toBe('default');

	$this->session->set(FakeSessionKey::TEST, 'value');
	expect($this->session->getOrDefault(FakeSessionKey::TEST, 'default'))->toBe('value');
});

test('it can consume or return a default value', function () {
	expect($this->session->consumeOrDefault(FakeSessionKey::TEST, 'default'))->toBe('default');

	$this->session->set(FakeSessionKey::TEST, 'value');
	expect($this->session->consumeOrDefault(FakeSessionKey::TEST, 'default'))->toBe('value');
	expect($this->session->has(FakeSessionKey::TEST))->toBeFalse();
});

test('it throws an exception if session provider is unreachable', function () {
	$this->session->destroy();
	$this->session->set(FakeSessionKey::TEST, 'value');
})->throws(SessionProviderUnreachableException::class);
