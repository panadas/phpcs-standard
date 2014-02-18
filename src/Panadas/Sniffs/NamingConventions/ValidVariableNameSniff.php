<?php
if (!class_exists("PHP_CodeSniffer_Standards_AbstractVariableSniff")) {
    throw new PHP_CodeSniffer_Exception("Class PHP_CodeSniffer_Standards_AbstractVariableSniff not found");
}

class Panadas_Sniffs_NamingConventions_ValidVariableNameSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param integer              $stackPtr
     * @param string               $name
     */
    protected function validate(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $name)
    {
        $reserved = [
            "_SERVER",
            "_GET",
            "_POST",
            "_REQUEST",
            "_SESSION",
            "_ENV",
            "_COOKIE",
            "_FILES",
            "GLOBALS"
        ];

        if (in_array($name, $reserved)) {
            return;
        }

        if (PHP_CodeSniffer::isCamelCaps($name, false, true, false)) {
            return;
        }

        $phpcsFile->addError("Variable \"{$name}\" is an invalid name", $stackPtr);
    }

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param integer              $stackPtr
     */
    protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $content = $phpcsFile->getTokens()[$stackPtr]["content"];
        $this->validate($phpcsFile, $stackPtr, ltrim($content, "$"));
    }

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param integer              $stackPtr
     */
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $content = $phpcsFile->getTokens()[$stackPtr]["content"];
        $this->validate($phpcsFile, $stackPtr, ltrim($content, "$"));
    }

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int                  $stackPtr
     */
    protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $content = $phpcsFile->getTokens()[$stackPtr]["content"];
        $matches = [];
        if (preg_match_all("/[^\\\]\\$([a-zA-Z][a-zA-Z0-9_]*)/", $content, $matches)) {
            foreach ($matches[1] as $name) {
                $this->validate($phpcsFile, $stackPtr, $name);
            }
        }
    }
}
