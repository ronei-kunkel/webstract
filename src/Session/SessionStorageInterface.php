<?php

declare(strict_types=1);

namespace Webstract\Session;

interface SessionStorageInterface
{
	/**
	 * @return void
	 */
	public function initSession(): void;

	/**
	 * @return void
	 */
	public function destroySession(): void;

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key): bool;

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get(string $key): mixed;

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function set(string $key, mixed $value): void;

	/**
	 * @param string $key
	 * @return void
	 */
	public function delete(string $key): void;
}
