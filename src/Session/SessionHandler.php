<?php

declare(strict_types=1);

namespace Webstract\Session;

use Webstract\Session\Exception\SessionProviderUnreachableException;
use Webstract\Session\Exception\SessionValueNotFoundException;

interface SessionHandler
{
	/**
	 * @throws SessionProviderUnreachableException
	 * @return void
	 */
	public function initSession(): void;

	/**
	 * @return void
	 */
	public function destroySession(): void;

	/**
	 * @param SessionKeyInterface $key
	 * @throws SessionProviderUnreachableException
	 * @throws SessionValueNotFoundException
	 * @return mixed
	 */
	public function get(SessionKeyInterface $key): mixed;

	/**
	 * @param SessionKeyInterface $key
	 * @param mixed $default
	 * @throws SessionProviderUnreachableException
	 * @return mixed
	 */
	public function getOrDefault(SessionKeyInterface $key, mixed $default = null): mixed;

	/**
	 * @param SessionKeyInterface $key
	 * @param mixed $value
	 * @throws SessionProviderUnreachableException
	 * @return void
	 */
	public function set(SessionKeyInterface $key, mixed $value): void;

	/**
	 * @param SessionKeyInterface $key
	 * @throws SessionProviderUnreachableException
	 * @return bool
	 */
	public function has(SessionKeyInterface $key): bool;

	/**
	 * @param SessionKeyInterface $key
	 * @return void
	 */
	public function delete(SessionKeyInterface $key): void;

	/**
	 * @param SessionKeyInterface $key
	 * @throws SessionProviderUnreachableException
	 * @throws SessionValueNotFoundException
	 * @return mixed
	 */
	public function consume(SessionKeyInterface $key): mixed;

	/**
	 * @param SessionKeyInterface $key
	 * @param mixed $default
	 * @throws SessionProviderUnreachableException
	 * @return mixed
	 */
	public function consumeOrDefault(SessionKeyInterface $key, mixed $default = null): mixed;
}
