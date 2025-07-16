<?php

declare(strict_types=1);

namespace Test\Support\Session;

use Webstract\Session\Exception\SessionMissingException;
use Webstract\Session\Exception\SessionValueNotFoundException;
use Webstract\Session\KeyValueSessionHandler;
use Webstract\Session\SessionKeyInterface;

final class FakeKeyValueSessionHandler implements KeyValueSessionHandler
{
	private static array $session = [];

	public function initSession(): string
	{
		$sessId = uniqid();
		self::$session[$sessId] = [];
		return $sessId;
	}

	public function destroySession(string $sessId): void
	{
		unset(self::$session[$sessId]);
	}

	public function get(string $sessId, SessionKeyInterface $key): mixed
	{
		if(!array_key_exists($sessId, self::$session)) {
			throw new SessionMissingException();
		}

		if (!array_key_exists($key->getName(), self::$session[$sessId])) {
			throw new SessionValueNotFoundException();
		}

		return self::$session[$sessId][$key->getName()];
	}

	public function getOrDefault(string $sessId, SessionKeyInterface $key, mixed $default = null): mixed
	{
		if(!array_key_exists($sessId, self::$session)) {
			throw new SessionMissingException();
		}

		if (!array_key_exists($key->getName(), self::$session[$sessId])) {
			return $default;
		}

		return self::$session[$sessId][$key->getName()];
	}

	public function set(string $sessId, SessionKeyInterface $key, mixed $value): void
	{
		if(!array_key_exists($sessId, self::$session)) {
			throw new SessionMissingException();
		}

		self::$session[$sessId][$key->getName()] = $value;
	}

	public function has(string $sessId, SessionKeyInterface $key): bool
	{
		if(!array_key_exists($sessId, self::$session)) {
			throw new SessionMissingException();
		}

		return array_key_exists($key->getName(), self::$session[$sessId]);
	}

	public function delete(string $sessId, SessionKeyInterface $key): void
	{
		if(!array_key_exists($sessId, self::$session)) {
			throw new SessionMissingException();
		}

		unset(self::$session[$sessId][$key->getName()]);
	}

	public function consume(string $sessId, SessionKeyInterface $key): mixed
	{
		if(!array_key_exists($sessId, self::$session)) {
			throw new SessionMissingException();
		}

		if (!array_key_exists($key->getName(), self::$session[$sessId])) {
			throw new SessionValueNotFoundException();
		}

		$value = self::$session[$sessId][$key->getName()];

		unset(self::$session[$sessId][$key->getName()]);

		return $value;
	}

	public function consumeOrDefault(string $sessId, SessionKeyInterface $key, mixed $default = null): mixed
	{
		if(!array_key_exists($sessId, self::$session)) {
			throw new SessionMissingException();
		}

		if (!array_key_exists($key->getName(), self::$session[$sessId])) {
			return $default;
		}

		$value = self::$session[$sessId][$key->getName()];

		unset(self::$session[$sessId][$key->getName()]);

		return $value;
	}
}
