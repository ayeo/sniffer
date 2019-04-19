About
=====

This is the code sniffer that scans only recently modified lines. Under the hood it uses PHPCS and just filter report to lines changed within particular commit. I have built this simple tool to support my Continuous Integration. It may turns out usefull for others struggling with large legacy code base.

Installation
============

```
composer require ayeo/sniffer
```

Usage
=====

From your project root directory type:
```
./vendor/bin/sniff
```

```
./vendor/bin/sniff --standard=/path/to/ruleset.xml
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

Issues
======

Feel free to submit issues and enhancement requests.

Contributing
============

Everyone welcome 
