<?php
namespace Isystems\Sniffer\Methods;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\MethodDeclarationSniff as SnifferMethodDeclarationSniff;

if (class_exists('PHP_CodeSniffer\Sniffs\AbstractScopeSniff', true) === false) {
    throw new PHP_CodeSniffer\Exceptions\RuntimeException('Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found');
}

class MethodDeclarationSniff extends SnifferMethodDeclarationSniff
{
    protected function processTokenWithinScope(File $phpcsFile, $stackPtr, $currScope)
    {
        $fileName = $phpcsFile->getFilename();
        $isNotController = strpos($fileName, 'Controller.php') === false;
        if ($isNotController) {
            parent::processTokenWithinScope($phpcsFile, $stackPtr, $currScope);
        } else {
            //process like in parent but without underscore
            $tokens = $phpcsFile->getTokens();
            $methodName = $phpcsFile->getDeclarationName($stackPtr);
            if ($methodName === null) {
                // Ignore closures.
                return;
            }

            $visibility = 0;
            $static     = 0;
            $abstract   = 0;
            $final      = 0;

            $find   = Tokens::$methodPrefixes;
            $find[] = T_WHITESPACE;
            $prev   = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);

            $prefix = $stackPtr;
            while (($prefix = $phpcsFile->findPrevious(Tokens::$methodPrefixes, ($prefix - 1), $prev)) !== false) {
                switch ($tokens[$prefix]['code']) {
                    case T_STATIC:
                        $static = $prefix;
                        break;
                    case T_ABSTRACT:
                        $abstract = $prefix;
                        break;
                    case T_FINAL:
                        $final = $prefix;
                        break;
                    default:
                        $visibility = $prefix;
                        break;
                }
            }

            $fixes = array();

            if ($visibility !== 0 && $final > $visibility) {
                $error = 'The final declaration must precede the visibility declaration';
                $fix   = $phpcsFile->addFixableError($error, $final, 'FinalAfterVisibility');
                if ($fix === true) {
                    $fixes[$final]       = '';
                    $fixes[($final + 1)] = '';
                    if (isset($fixes[$visibility]) === true) {
                        $fixes[$visibility] = 'final '.$fixes[$visibility];
                    } else {
                        $fixes[$visibility] = 'final '.$tokens[$visibility]['content'];
                    }
                }
            }

            if ($visibility !== 0 && $abstract > $visibility) {
                $error = 'The abstract declaration must precede the visibility declaration';
                $fix   = $phpcsFile->addFixableError($error, $abstract, 'AbstractAfterVisibility');
                if ($fix === true) {
                    $fixes[$abstract]       = '';
                    $fixes[($abstract + 1)] = '';
                    if (isset($fixes[$visibility]) === true) {
                        $fixes[$visibility] = 'abstract '.$fixes[$visibility];
                    } else {
                        $fixes[$visibility] = 'abstract '.$tokens[$visibility]['content'];
                    }
                }
            }

            if ($static !== 0 && $static < $visibility) {
                $error = 'The static declaration must come after the visibility declaration';
                $fix   = $phpcsFile->addFixableError($error, $static, 'StaticBeforeVisibility');
                if ($fix === true) {
                    $fixes[$static]       = '';
                    $fixes[($static + 1)] = '';
                    if (isset($fixes[$visibility]) === true) {
                        $fixes[$visibility] = $fixes[$visibility].' static';
                    } else {
                        $fixes[$visibility] = $tokens[$visibility]['content'].' static';
                    }
                }
            }

            // Batch all the fixes together to reduce the possibility of conflicts.
            if (empty($fixes) === false) {
                $phpcsFile->fixer->beginChangeset();
                foreach ($fixes as $stackPtr => $content) {
                    $phpcsFile->fixer->replaceToken($stackPtr, $content);
                }

                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}