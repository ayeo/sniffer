<?php
namespace Ayeo\Sniffer;

class ChangedFilesFinder
{
	private $basePath;

	public function __construct($basePath)
	{
		$this->basePath = $basePath;
	}

	public function findAll($commitSHA)
	{
		$listFilesCommand = "cd %s && git diff -M --name-only %s^ %s";
		$parsedCommand = sprintf($listFilesCommand, $this->basePath, $commitSHA, $commitSHA);
		$changedFilesList = array_filter(explode("\n", `$parsedCommand`), function($item) { return strlen($item) > 0; });

		var_dump($changedFilesList);
		//$changedLineNumbers = "cd %s && git blame -p %s -- %s | grep %s";
	}
}