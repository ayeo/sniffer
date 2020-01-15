<?php
namespace Isystems\Sniffer\Methods;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\PSR1\Sniffs\Methods\CamelCapsMethodNameSniff as SnifferCamelCapsMethodNameSniff;

if (class_exists('PHP_CodeSniffer\Sniffs\AbstractScopeSniff', true) === false) {
    throw new PHP_CodeSniffer\Exceptions\RuntimeException('Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found');
}

class CamelCapsMethodNameSniff extends SnifferCamelCapsMethodNameSniff
{
    protected function processTokenWithinScope(File $phpcsFile, $stackPtr, $currScope)
    {
        $fileName = $phpcsFile->getFilename();
        $isNotController = strpos($fileName, 'Controller.php') === false;
        if ($isNotController) {
            parent::processTokenWithinScope($phpcsFile, $stackPtr, $currScope);
        }
    }
}