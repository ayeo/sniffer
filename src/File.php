<?php
namespace Ayeo\Sniffer;

class File
{
	/**
	 * @var
	 */
	private $path;

	public function __construct($path)
	{
		$this->path = $path;
	}
}