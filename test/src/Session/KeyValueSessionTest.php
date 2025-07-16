<?php

declare(strict_types=1);

use Test\Support\Session\FakeKeyValueSessionHandler;
use Test\Support\Session\FakeSessionKey;
use Webstract\Session\Exception\SessionMissingException;
use Webstract\Session\Exception\SessionProviderUnreachableException;
use Webstract\Session\Exception\SessionValueNotFoundException;

beforeEach(function () {
	$this->session = new FakeKeyValueSessionHandler();
	$this->sessId = $this->session->initSession();
});

afterEach(fn() => $this->session->destroySession($this->sessId));

test('it can create and destroy a session', function () {
	$sessId = $this->session->initSession();
	expect($this->session->has($sessId, FakeSessionKey::TEST))->toBeFalse();

	$this->session->destroySession($sessId);
	expect(fn() => $this->session->has($sessId, FakeSessionKey::TEST))->toThrow(SessionMissingException::class);
});

test('it can set and get a value in the session', function () {
	$this->session->set($this->sessId, FakeSessionKey::TEST, 'value');
	expect($this->session->get($this->sessId, FakeSessionKey::TEST))->toBe('value');
});

test('it throws an exception when accessing a nonexistent key', function () {
	expect(fn() => $this->session->get($this->sessId, FakeSessionKey::ANOTHER_TEST))->toThrow(SessionValueNotFoundException::class);
});

test('it can check if a key exists', function () {
	$this->session->set($this->sessId, FakeSessionKey::TEST, 'value');
	expect($this->session->has($this->sessId, FakeSessionKey::TEST))->toBeTrue();
	expect($this->session->has($this->sessId, FakeSessionKey::ANOTHER_TEST))->toBeFalse();
});

test('it can delete a key from the session', function () {
	$this->session->set($this->sessId, FakeSessionKey::TEST, 'value');
	$this->session->delete($this->sessId, FakeSessionKey::TEST);

	expect($this->session->has($this->sessId, FakeSessionKey::TEST))->toBeFalse();
});

test('it can consume a key', function () {
	$this->session->set($this->sessId, FakeSessionKey::TEST, 'value');
	$value = $this->session->consume($this->sessId, FakeSessionKey::TEST);

	expect($value)->toBe('value');
	expect($this->session->has($this->sessId, FakeSessionKey::TEST))->toBeFalse();
});

test('it throws an exception when consuming a nonexistent key', function () {
	expect(fn() => $this->session->consume($this->sessId, FakeSessionKey::ANOTHER_TEST))->toThrow(SessionValueNotFoundException::class);
});

test('it can get or return a default value', function () {
	expect($this->session->getOrDefault($this->sessId, FakeSessionKey::TEST, 'default'))->toBe('default');

	$this->session->set($this->sessId, FakeSessionKey::TEST, 'value');
	expect($this->session->getOrDefault($this->sessId, FakeSessionKey::TEST, 'default'))->toBe('value');
});

test('it can consume or return a default value', function () {
	expect($this->session->consumeOrDefault($this->sessId, FakeSessionKey::TEST, 'default'))->toBe('default');

	$this->session->set($this->sessId, FakeSessionKey::TEST, 'value');
	expect($this->session->consumeOrDefault($this->sessId, FakeSessionKey::TEST, 'default'))->toBe('value');
	expect($this->session->has($this->sessId, FakeSessionKey::TEST))->toBeFalse();
});

test('it throws an exception if has no session', function () {
	$this->session->destroySession($this->sessId);
	$this->session->set($this->sessId, FakeSessionKey::TEST, 'value');
})->throws(SessionMissingException::class);
