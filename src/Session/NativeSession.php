<?php

declare(strict_types=1);

namespace Webstract\Session;

use Webstract\Session\Exception\SessionValueNotFoundException;
use Webstract\Session\SessionHandler;
use Webstract\Session\SessionKeyInterface;

final class NativeSession implements SessionHandler
{
	private const array OPTIONS = [
		'cookie_secure' => true,
		'cookie_httponly' => true,
		'cookie_samesite' => 'Strict',
		'use_strict_mode' => true,
	];

	public function initSession(): void
	{
		@session_start(self::OPTIONS);
	}

	public function destroySession(): void
	{
		session_destroy();
	}

	public function get(SessionKeyInterface $key): mixed
	{
		if (!$this->has($key)) {
			throw new SessionValueNotFoundException();
		}

		$value = $_SESSION[$key->getName()];

		$unserializedObject = @unserialize($value);
		if (!$unserializedObject) {
			return $value;
		}

		return $unserializedObject;
	}

	public function getOrDefault(SessionKeyInterface $key, mixed $default = null): mixed
	{
		if (!$this->has($key)) {
			return $default;
		}

		$value = $_SESSION[$key->getName()];

		$unserializedObject = @unserialize($value);
		if (!$unserializedObject) {
			return $value;
		}

		return $unserializedObject;
	}

	public function set(SessionKeyInterface $key, mixed $value): void
	{
		if (is_object($value)) {
			$value = serialize($value);
		}

		$_SESSION[$key->getName()] = $value;
	}

	public function has(SessionKeyInterface $key): bool
	{
		return isset($_SESSION[$key->getName()]);
	}

	public function delete(SessionKeyInterface $key): void
	{
		if ($this->has($key)) {
			unset($_SESSION[$key->getName()]);
		}
	}

	public function consume(SessionKeyInterface $key): mixed
	{
		$value = $this->get($key);
		$this->delete($key);
		return $value;
	}

	public function consumeOrDefault(SessionKeyInterface $key, mixed $default = null): mixed
	{
		$value = $this->getOrDefault($key, $default);
		$this->delete($key);
		return $value;
	}
}
