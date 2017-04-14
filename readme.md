Installation
============

Add custom repo to your composer repositories and install: 
```
composer.phar config repositories.ayeo git git@github.com:ayeo/sniffer.git
composer require ayeo/sniffer
```

This will be moved to packagist in future

Usage
=====

From your project root type:
```
./vendor/bin/sniff
```

Result (example)
================
```
File: application/modules/behat_api/src/V1/Model/Producer.php
| 4 | error	| Missing class doc comment
| 4 | error	| Opening brace of a class must be on the line after the definition
| 5 | error	| Spaces must be used to indent lines; tabs are not allowed
| 5 | error	| Line indented incorrectly; expected 4 spaces, found 1
| 8 | error	| Spaces must be used to indent lines; tabs are not allowed
| 8 | error	| Line indented incorrectly; expected 4 spaces, found 1
| 9 | error	| Spaces must be used to indent lines; tabs are not allowed
| 9 | error	| Line indented incorrectly; expected 4 spaces, found 1
| 9 | error	| Spaces must be used for alignment; tabs are not allowed
```