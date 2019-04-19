<?php

namespace Ayeo\Sniffer;

class ParamRecognizer
{
	private $params;

	public function __construct(array $params)
	{
		$this->params = $params;
	}

	public function getStandardFilePath()
	{
		return $this->getValueForKey('standard');
	}

	private function getValueForKey($key) : ?string
	{
		$key = sprintf("--%s=", $key);
		foreach ($this->params as $value)
		{
			if (false !== strpos($value, $key))
			{
				return str_replace($key, '', $value);
			}
		}

		return null;
	}
}