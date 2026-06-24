<?php

declare(strict_types=1);

namespace Test\Support\Contracts\Session;

use Webstract\Session\Exception\SessionMissingException;
use Webstract\Session\Exception\SessionProviderUnreachableException;
use Webstract\Session\Exception\SessionValueNotFoundException;
use Webstract\Session\KeyValueSessionHandler;
use Webstract\Session\SessionHandler;
use Webstract\Session\SessionKeyInterface;

class FakeSessionHandler implements SessionHandler
{
	private array $values = [];
	public bool $initialized = false;
	public function initSession(): void { $this->initialized = true; }
	public function destroySession(): void { $this->values = []; $this->initialized = false; }
	public function get(SessionKeyInterface $key): mixed
	{
		if (!$this->has($key)) throw new SessionValueNotFoundException();
		return $this->values[$key->getName()];
	}
	public function getOrDefault(SessionKeyInterface $key, mixed $default = null): mixed { return $this->values[$key->getName()] ?? $default; }
	public function set(SessionKeyInterface $key, mixed $value): void { $this->values[$key->getName()] = $value; }
	public function has(SessionKeyInterface $key): bool { return array_key_exists($key->getName(), $this->values); }
	public function delete(SessionKeyInterface $key): void { unset($this->values[$key->getName()]); }
	public function consume(SessionKeyInterface $key): mixed { $value = $this->get($key); $this->delete($key); return $value; }
	public function consumeOrDefault(SessionKeyInterface $key, mixed $default = null): mixed { $value = $this->getOrDefault($key, $default); $this->delete($key); return $value; }
}

class FakeKeyValueSessionHandler implements KeyValueSessionHandler
{
	private array $sessions = [];
	public function initSession(): string { $id = 'sess-' . (count($this->sessions) + 1); $this->sessions[$id] = []; return $id; }
	public function destroySession(string $sessId): void { $this->assertSessionExists($sessId); unset($this->sessions[$sessId]); }
	public function get(string $sessId, SessionKeyInterface $key): mixed
	{ $this->assertSessionExists($sessId); if (!$this->has($sessId, $key)) throw new SessionValueNotFoundException(); return $this->sessions[$sessId][$key->getName()]; }
	public function getOrDefault(string $sessId, SessionKeyInterface $key, mixed $default = null): mixed
	{ $this->assertSessionExists($sessId); return $this->sessions[$sessId][$key->getName()] ?? $default; }
	public function set(string $sessId, SessionKeyInterface $key, mixed $value): void { $this->assertSessionExists($sessId); $this->sessions[$sessId][$key->getName()] = $value; }
	public function has(string $sessId, SessionKeyInterface $key): bool { $this->assertSessionExists($sessId); return array_key_exists($key->getName(), $this->sessions[$sessId]); }
	public function delete(string $sessId, SessionKeyInterface $key): void { $this->assertSessionExists($sessId); unset($this->sessions[$sessId][$key->getName()]); }
	public function consume(string $sessId, SessionKeyInterface $key): mixed { $value = $this->get($sessId, $key); $this->delete($sessId, $key); return $value; }
	public function consumeOrDefault(string $sessId, SessionKeyInterface $key, mixed $default = null): mixed
	{ $value = $this->getOrDefault($sessId, $key, $default); $this->delete($sessId, $key); return $value; }
	private function assertSessionExists(string $sessId): void { if (!array_key_exists($sessId, $this->sessions)) throw new SessionMissingException(); }
}
