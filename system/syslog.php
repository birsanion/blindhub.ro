<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

if (!defined('SYSCAP_LOG')){
// this tells the whole framework that this system file has been included
define('SYSCAP_LOG', true);

define('LOGTYPE_FILE',          0);
define('LOGTYPE_DATABASE',      1);

// class CQSyslog - class for logging errors, debug messages and other stuff

class CQSyslog
{
    private static $hInstance = NULL;
    private $nType;
    private $DATABASE;
    
    function __construct()
    {
        $this->nType = LOGTYPE_FILE;
        $this->DATABASE = NULL;
    }
    
    static function GetInstance()
    {
        if (self::$hInstance === NULL) self::$hInstance = new CQSyslog();
        
        return self::$hInstance;
    }
    
    function Init(&$kDatabase)
    {
        $this->DATABASE = $kDatabase;
    }
    
    function Log($strLocation, $mxContent, $bReverseLogging = false)
    {
        switch ($this->nType)
        {
            case LOGTYPE_FILE:{
                if (is_array($mxContent)){
                    if (!$bReverseLogging)
                        file_put_contents("logs/$strLocation.txt",
                            date('Y-m-d H:i:s') . ' - ' . print_r($mxContent, true) .
                            "\r\n", FILE_APPEND);
                    else file_put_contents("logs/$strLocation.txt",
                        date('Y-m-d H:i:s') . ' - ' . print_r($mxContent, true) .
                            "\r\n" . file_get_contents("logs/$strLocation.txt"));
                }else{
                    if (!$bReverseLogging)
                        file_put_contents("logs/$strLocation.txt", date('Y-m-d H:i:s').
                            " - $mxContent\r\n", FILE_APPEND);
                    else file_put_contents("logs/$strLocation.txt", date('Y-m-d H:i:s').
                        " - $mxContent\r\n" . file_get_contents("logs/$strLocation.txt"));
                }
            }break;
        }
    }
    
    function Write($mxContent)
    {
        switch ($this->nType)
        {
            case LOGTYPE_FILE:{
                if (is_array($mxContent))
                    file_put_contents("logs/syslog.txt", date('Y-m-d H:i:s').
                        ' - '. print_r($mxContent, true) . "\r\n", FILE_APPEND);
                else file_put_contents("logs/syslog.txt", date('Y-m-d H:i:s').
                    " - $mxContent\r\n", FILE_APPEND);
            }break;
        }
    }
    
    function Debug($strWhat, $strContent, $bPostDevelop = false)
    {
        if ((defined('DEBUGMODE') && DEBUGMODE) || $bPostDevelop){
            if (is_string($strContent))
                file_put_contents("logs/debug-$strWhat.txt", date('Y-m-d H:i:s').
                    " - $strContent\r\n", FILE_APPEND);
            
            if (is_array($strContent))
                file_put_contents("logs/debug-$strWhat.txt", date('Y-m-d H:i:s').
                    " - " . print_r($strContent, true) . "\r\n", FILE_APPEND);
        }
    }
}

// System Error Handling
function SysErrorHandler($nErrNo, $strErr, $strErrFile, $strErrLine)
{
    switch ($nErrNo)
    {
        case E_NOTICE:
        case E_USER_NOTICE:
            $strError = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $strError = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $strError = 'Error';
            break;
        default:
            $strError = 'Unknown';
            break;
    }
    
    $LOG = CQSyslog::GetInstance();
    
    $LOG->Write("PHP $strError: $strErr in $strErrFile on line $strErrLine");

    return true;
}

set_error_handler('SysErrorHandler');

// System Exception Handling
function SysExceptionHandler($kException)
{
    $LOG = CQSyslog::GetInstance();
    
    $LOG->Write('PHP Exception: ' . $kException->getMessage() . ' in file ' .
        $kException->getFile() . ' on line ' . $kException->getLine() .
        "\r\n\t" . str_replace("\n", "\n\t", $kException->getTraceAsString()));
}

set_exception_handler('SysExceptionHandler');

} // endif define SYSCAP_LOG

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;