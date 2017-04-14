<?php
namespace Ayeo\Sniffer;

class File
{
	/**
	 * @var
	 */
	private $path;

	private $changedLinesNumbers = [];

	public function __construct($path)
	{
		$this->path = $path;
	}

	public function getFileFullPath()
	{
		return $this->path;
	}

	public function markLineAsChanged($number)
	{
		if (array_search($number, $this->changedLinesNumbers) === false) {
			$this->changedLinesNumbers[] = $number;
		}

		sort($this->changedLinesNumbers);
	}

	public function getChangedLinesNumbers()
	{
		return $this->changedLinesNumbers;
	}

	public function isChanged($lineNumber)
	{
		if (array_search($lineNumber, $this->changedLinesNumbers) !== false) {
			return true;
		}

		if (array_search($lineNumber+1, $this->changedLinesNumbers) !== false) {
			return true;
		}

		if (array_search($lineNumber-1, $this->changedLinesNumbers) !== false) {
			return true;
		}

		return false;
	}
}
