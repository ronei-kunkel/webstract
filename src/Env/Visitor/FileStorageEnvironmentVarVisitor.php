<?php

declare(strict_types=1);

namespace Webstract\Env\Visitor;

interface FileStorageEnvironmentVarVisitor
{
	public function fileStorageIsDevEnv(): bool;
	public function getFileStoragePublicApiKey(): string;
	public function getFileStorageSecretApiKey(): string;
	public function getFileStorageBucketRegion(): string;
	public function getFileStorageBucketName(): string;
	public function getFileStorageBucketNamespace(): string;
	public function getFileStoragePreauthReadImages(): string;
	public function getFileStoragePreauthReadBackups(): string;
}
