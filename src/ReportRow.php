<?php
namespace Ayeo\Sniffer;

class ReportRow
{
	private $lineNumber;
	private $type;
	private $message;

	public function __construct($lineNumber, $type, $message)
	{
		$this->lineNumber = $lineNumber;
		$this->type = $type;
		$this->message = $message;
	}

	/**
	 * @return int
	 */
	public function getLineNumber()
	{
		return $this->lineNumber;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}
}
