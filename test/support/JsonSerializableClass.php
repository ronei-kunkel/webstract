<?php

namespace Test\Support\RoneiKunkel\Webstract;

use JsonSerializable;

class JsonSerializableClass implements JsonSerializable
{
	public function jsonSerialize(): array
	{
		return ['status' => 'success'];
	}
};
