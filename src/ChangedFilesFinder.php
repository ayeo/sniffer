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
		$changedLineNumbers = "cd %s && git blame -p %s -- %s | grep %s";

		$filesCollection = [];
		foreach ($changedFilesList as $filePath) {
			$pathInfo = pathinfo($filePath);
			$extension = key_exists('extension', $pathInfo) ? $pathInfo['extension'] : null;
			$isPHP = $extension == 'php' ? true : false;
			if (file_exists($filePath) === false || $isPHP == false) {
				continue; //skip deleted
			}

			$file = new File($filePath);

			$xx = sprintf($changedLineNumbers, $this->basePath, $commitSHA, $filePath, $commitSHA);

			foreach (explode("\n", `$xx`) as $row) {
				preg_match("#^[a-z0-9]{40}\s(\d+)\s(\d+)#", $row, $match);

				if (is_null($match[1]) === false) {
					$file->markLineAsChanged($match[1]);
				}

				if (is_null($match[2]) === false) {
					$file->markLineAsChanged($match[2]);
				}
			}

			$filesCollection[] = $file;
		}

		return $filesCollection;
	}

}