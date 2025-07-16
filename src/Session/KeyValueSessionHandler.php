<?php

declare(strict_types=1);

namespace Webstract\Session;

use Webstract\Session\Exception\SessionMissingException;
use Webstract\Session\Exception\SessionProviderUnreachableException;
use Webstract\Session\Exception\SessionValueNotFoundException;

interface KeyValueSessionHandler
{
	/**
	 * @throws SessionProviderUnreachableException
	 * @return string SESSID
	 */
	public function initSession(): string;

	/**
	 * @param string $sessId
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @return void
	 */
	public function destroySession(string $sessId): void;

	/**
	 * @param string $sessId
	 * @param SessionKeyInterface $key
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @throws SessionValueNotFoundException
	 * @return mixed
	 */
	public function get(string $sessId, SessionKeyInterface $key): mixed;

	/**
	 * @param string $sessId
	 * @param SessionKeyInterface $key
	 * @param mixed $default
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @return mixed
	 */
	public function getOrDefault(string $sessId, SessionKeyInterface $key, mixed $default = null): mixed;

	/**
	 * @param string $sessId
	 * @param SessionKeyInterface $key
	 * @param mixed $value
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @return void
	 */
	public function set(string $sessId, SessionKeyInterface $key, mixed $value): void;

	/**
	 * @param string $sessId
	 * @param SessionKeyInterface $key
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @return bool
	 */
	public function has(string $sessId, SessionKeyInterface $key): bool;

	/**
	 * @param string $sessId
	 * @param SessionKeyInterface $key
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @return void
	 */
	public function delete(string $sessId, SessionKeyInterface $key): void;

	/**
	 * @param string $sessId
	 * @param SessionKeyInterface $key
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @throws SessionValueNotFoundException
	 * @return mixed
	 */
	public function consume(string $sessId, SessionKeyInterface $key): mixed;

	/**
	 * @param string $sessId
	 * @param SessionKeyInterface $key
	 * @param mixed $default
	 * @throws SessionProviderUnreachableException
	 * @throws SessionMissingException
	 * @return mixed
	 */
	public function consumeOrDefault(string $sessId, SessionKeyInterface $key, mixed $default = null): mixed;
}
