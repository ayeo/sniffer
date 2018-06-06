<?php
namespace Ayeo\Sniffer;

class Sniffer
{
    /**
     * @var string
     */
	private $standardFile;
    private $vendorPath;

    /**
     * @param string $standardFile
     * @param $vendorPath
     */
	public function __construct($standardFile, $vendorPath)
	{
		$this->standardFile = $standardFile;
        $this->vendorPath = $vendorPath;
    }

    /**
     * @param File $file
     * @return array
     */
	public function getReport(File $file)
	{
		$rows = [];

		$command = '%s/bin/phpcs --standard=%s %s --report=csv';
		$xxx = sprintf($command, $this->vendorPath, $this->standardFile, $file->getFileFullPath());
		$ddddd = explode(PHP_EOL, `$xxx`);
		$array = [];
		foreach ($ddddd as $line) {
			$array[] = str_getcsv($line);
		}

		foreach ($array as $row) {
			if (isset($row[1]) && is_numeric($row[1])) {
				$rows[] = new ReportRow($row[1], $row[3], $row[4]);
			}
		}

		return $rows;
	}
}