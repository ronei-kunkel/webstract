<?php

declare(strict_types=1);

namespace Webstract\Session;

use Webstract\Session\Exception\SessionProviderUnreachableException;

final class SuperglobalSessionStorage implements SessionStorageInterface
{
	/**
	 * @throws SessionProviderUnreachableException
	 */
	public function initSession(): void
	{
		if (session_status() === \PHP_SESSION_ACTIVE) {
			return;
		}

		if (!session_start()) {
			throw new SessionProviderUnreachableException('Unable to start session provider');
		}
	}

	public function destroySession(): void
	{
		if (session_status() !== \PHP_SESSION_ACTIVE) {
			$_SESSION = [];
			return;
		}

		$_SESSION = [];
		session_destroy();
	}

	public function has(string $key): bool
	{
		return array_key_exists($key, $_SESSION);
	}

	public function get(string $key): mixed
	{
		return $_SESSION[$key];
	}

	public function set(string $key, mixed $value): void
	{
		$_SESSION[$key] = $value;
	}

	public function delete(string $key): void
	{
		unset($_SESSION[$key]);
	}
}
