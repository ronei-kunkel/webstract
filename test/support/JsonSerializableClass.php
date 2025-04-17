<?php

namespace Test\Support;

use JsonSerializable;

class JsonSerializableClass implements JsonSerializable
{
	public function jsonSerialize(): array
	{
		return ['status' => 'success'];
	}
};
