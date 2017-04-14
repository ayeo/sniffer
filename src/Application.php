<?php
$path = __DIR__."/../../ticket/isklep/";
$exit = 0;

$commitSHA = substr(`cd $path && git rev-parse HEAD`, 0, 8);

$listFilesCommand = "cd %s && git diff -M --name-only %s^ %s";
$parsedCommand = sprintf($listFilesCommand, $path, $commitSHA, $commitSHA);
$changedFilesList = array_filter(explode("\n", `$parsedCommand`), function($item) { return strlen($item) > 0; });
$changedLineNumbers = "cd %s && git blame -p %s -- %s | grep %s";

foreach ($changedFilesList as $file) {
	$lines = [];
	$xx = sprintf($changedLineNumbers, $path, $commitSHA, $file, $commitSHA);

	foreach (explode("\n", `$xx`) as $row) {
		preg_match("#^[a-z0-9]{40}\s(\d+)\s(\d+)#", $row, $match);

		if (empty($match)) {
			continue;
		}

		if (array_search($match[1], $lines) === false) {
			if (is_null($match[1]) === false) {
				$lines[] = $match[1];
			}
		}

		if (array_search($match[2], $lines) === false) {

			if (is_null($match[2]) === false) {
				$lines[] = $match[2];
			}
		}
	}

	$command = './vendor/bin/phpcs --standard=%s/code_style/ISystems/ruleset.xml %s --report=csv';
	$xxx = sprintf($command, $path, $path.$file);
	$ddddd = explode(PHP_EOL, `$xxx`);
	$array = [];
	foreach ($ddddd as $line) {
		$array[] = str_getcsv($line);
	}

	echo "\n".$file."\n";
	foreach ($array as $row) {
		//todo: search one line below and above
		if (isset($row[1]) && is_numeric($row[1])) {
			if (array_search($row[1], $lines) !== false) {
				$exit = 1;
				echo sprintf("%s \t | %s\n", $row[1], $row[4]);
			}
		}
	}
}

exit($exit);