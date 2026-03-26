<?php

declare(strict_types=1);

namespace Webstract\Session;

use Webstract\Session\Exception\SessionProviderUnreachableException;
use Webstract\Session\Exception\SessionValueNotFoundException;

final class AbstractedSessionHandler implements SessionHandler
{
	public function __construct(private SessionStorageInterface $storage)
	{
	}

	/**
	 * @throws SessionProviderUnreachableException
	 */
	public function initSession(): void
	{
		$this->storage->initSession();
	}

	public function destroySession(): void
	{
		$this->storage->destroySession();
	}

	/**
	 * @throws SessionValueNotFoundException
	 */
	public function get(SessionKeyInterface $key): mixed
	{
		$keyName = $key->getName();
		if (!$this->storage->has($keyName)) {
			throw new SessionValueNotFoundException(sprintf('Session value `%s` was not found', $keyName));
		}

		return $this->storage->get($keyName);
	}

	public function getOrDefault(SessionKeyInterface $key, mixed $default = null): mixed
	{
		if (!$this->storage->has($key->getName())) {
			return $default;
		}

		return $this->storage->get($key->getName());
	}

	public function set(SessionKeyInterface $key, mixed $value): void
	{
		$this->storage->set($key->getName(), $value);
	}

	public function has(SessionKeyInterface $key): bool
	{
		return $this->storage->has($key->getName());
	}

	public function delete(SessionKeyInterface $key): void
	{
		$this->storage->delete($key->getName());
	}

	/**
	 * @throws SessionValueNotFoundException
	 */
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
