<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\Session;

use RoneiKunkel\Webstract\Session\Exception\SessionProviderUnreachableException;
use RoneiKunkel\Webstract\Session\Exception\SessionValueNotFoundException;
use RoneiKunkel\Webstract\Session\SessionHandlerInterface;
use RoneiKunkel\Webstract\Session\SessionInterface;
use RoneiKunkel\Webstract\Session\SessionKeyInterface;

final class FakeSessionManager implements SessionInterface, SessionHandlerInterface
{
	private static ?array $session = null;

	public function create(): void
	{
		self::$session = [];
	}

	public function destroy(): void
	{
		self::$session = null;
	}

	public function get(SessionKeyInterface $key): mixed
	{
		if (self::$session === null) {
			throw new SessionProviderUnreachableException();
		}

		if (!array_key_exists($key->getName(), self::$session)) {
			throw new SessionValueNotFoundException();
		}

		return self::$session[$key->getName()];
	}

	public function getOrDefault(SessionKeyInterface $key, mixed $default = null): mixed
	{
		if (self::$session === null) {
			throw new SessionProviderUnreachableException();
		}

		if (!array_key_exists($key->getName(), self::$session)) {
			return $default;
		}

		return self::$session[$key->getName()];
	}

	public function set(SessionKeyInterface $key, mixed $value): void
	{
		if (self::$session === null) {
			throw new SessionProviderUnreachableException();
		}

		self::$session[$key->getName()] = $value;
	}

	public function has(SessionKeyInterface $key): bool
	{
		if (self::$session === null) {
			throw new SessionProviderUnreachableException();
		}

		return array_key_exists($key->getName(), self::$session);
	}

	public function delete(SessionKeyInterface $key): void
	{
		if (self::$session === null) {
			throw new SessionProviderUnreachableException();
		}

		unset(self::$session[$key->getName()]);
	}

	public function consume(SessionKeyInterface $key): mixed
	{
		if (self::$session === null) {
			throw new SessionProviderUnreachableException();
		}

		if (!array_key_exists($key->getName(), self::$session)) {
			throw new SessionValueNotFoundException();
		}

		$value = self::$session[$key->getName()];

		unset(self::$session[$key->getName()]);

		return $value;
	}

	public function consumeOrDefault(SessionKeyInterface $key, mixed $default = null): mixed
	{
		if (self::$session === null) {
			throw new SessionProviderUnreachableException();
		}

		if (!array_key_exists($key->getName(), self::$session)) {
			return $default;
		}

		$value = self::$session[$key->getName()];

		unset(self::$session[$key->getName()]);

		return $value;
	}
}
