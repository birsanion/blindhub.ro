<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

////////////////////////////////////////////////////////////////////////////////
// This file contains the core functions of any web PHP applications; this
// detects how the app was started, the environment, user agent details, etc.

if (!defined('SYSCAP_CORE')){
// this tells the whole framework that this system file has been included
define('SYSCAP_CORE', true);

// DEFINITIONS
define('ADDRTYPE_SCRIPTLOCATION',       1);
define('ADDRTYPE_REQUESTURI',           2);
define('ADDRTYPE_FILELOCATION',         3);
define('ADDRREL_ABSOLUTE',              1);
define('ADDRREL_RELATIVE',              2);

define('BRWSR_TYPE_UNKNOWN',            0);
define('BRWSR_TYPE_MOBILE',             1);
define('BRWSR_TYPE_BOT',                2);
define('BRWSR_TYPE_CLI',                3);
define('BRWSR_TYPE_DESKTOP',            4);
define('BRWSR_NAME_UNKNOWN',            0);
define('BRWSR_NAME_FIREFOX',            1);
define('BRWSR_NAME_IE',                 2);
define('BRWSR_NAME_CHROME',             3);
define('BRWSR_NAME_OPERA',              4);
define('BRWSR_NAME_SAFARI',             5);
define('PLATFORM_PC',                   0);
define('PLATFORM_ANDROID',              1);
define('PLATFORM_IOS',                  2);
define('PLATFORM_WINDOWSPHONE',         3);
define('PLATFORM_SONYPLAYSTATION',      4);
define('PLATFORM_OTHERMOBILE',          5);

define('REQTYPE_CLEAN',                 0);
define('REQTYPE_PARAM',                 1);
define('SESSTYPE_CLEAN',                0);
define('SESSTYPE_URL',                  1);
define('CALLMETH_HTTP',                 0);
define('CALLMETH_CLI',                  1);


class CQCore
{
    private $strPHPVersion;
    private $nPHPMajorVersion;
    private $nPHPMinorVersion;

    private $nRequestType;
    private $nSessionType;

    private $strScriptFilepath;         // Full path to physical file location on server (just the folder)
    private $arrCLIArgs;
    private $strBareURLAbsolute;
    private $strBareURLRelative;
    private $strRequestURLAbsolute;
    private $strRequestURLRelative;

    private $nBrowserType;
    private $nBrowserTypeReal;
    private $strUserScreenResolution;
    private $nBrowserName;
    private $nHardwareDevice;

    private $nCallingMethod;

    private $arrHTTPHeaders;
    private $strInputStream;
    private $strIPAddress;
    private $strUserAgent;
    private $strReferer;
    private $strHTTPAccept;
    private $strHTTPAcceptCharset;
    private $strHTTPAcceptEncoding;
    private $strHTTPAcceptLanguage;

    private $nExecStartTime;
    private $nExecFinishTime;

    function __construct()
    {
        $this->strPHPVersion = phpversion();
        $arrTemp = explode('.', $this->strPHPVersion);
        $this->nPHPMajorVersion = (int)$arrTemp[0];
        $this->nPHPMinorVersion = (int)$arrTemp[1];

        $this->nRequestType = REQTYPE_PARAM;
        $this->nSessionType = SESSTYPE_URL;

        $this->strScriptFilepath = '';
        $this->strBareURLAbsolute = '';
        $this->strBareURLRelative = '';
        $this->strRequestURLAbsolute = '';
        $this->strRequestURLRelative = '';

        $this->nBrowserType = 0;
        $this->nBrowserTypeReal = 0;
        $this->strUserScreenResolution = '';
        $this->nBrowserName = 0;
        $this->nHardwareDevice = 0;

        $this->nCallingMethod = CALLMETH_HTTP;
        $this->arrCLIArgs = NULL;

        $this->arrHTTPHeaders = array();
        $this->strInputStream = '';
        $this->strIPAddress = 'localhost';
        $this->strUserAgent = '';
        $this->strHTTPAccept = '';
        $this->strHTTPAcceptCharset = '';
        $this->strHTTPAcceptEncoding = '';
        $this->strHTTPAcceptLanguage = '';
        $this->strReferer = '';

        $this->nExecStartTime = microtime(true);
        $this->nExecFinishTime = 0;
    }

    private function DetectURLType()
    {
        if (function_exists('apache_get_modules')){
            if (in_array('mod_rewrite', apache_get_modules())){
                if (file_exists('.htaccess')){
                    $strContent = file_get_contents('.htaccess');
                    if (preg_match('/RewriteEngine([\ \t]*)On/', $strContent) == 1)
                        $this->nRequestType = REQTYPE_CLEAN;
                    else $this->nRequestType = REQTYPE_PARAM;
                }else $this->nRequestType = REQTYPE_PARAM;
            }else $this->nRequestType = REQTYPE_PARAM;
        }else $this->nRequestType = REQTYPE_PARAM;
        // TODO: the above is a forced assumption; maybe something smarter can be done
    }

    private function DetectSessionType()
    {
        if (!isset($_COOKIE[session_name()]))
            $this->nSessionType = SESSTYPE_URL;
        else $this->nSessionType = SESSTYPE_CLEAN;
    }

    private function DetectCallingType()
    {
        global $argc;
        global $argv;

        if ((isset($argc) && isset($argv)) || (!isset($_SERVER['SERVER_PROTOCOL']) &&
            !isset($_SERVER['SERVER_PORT']) && !isset($_SERVER['SERVER_NAME'])))
        {

            $this->nCallingMethod = CALLMETH_CLI;

            if (is_array($argv)) $this->arrCLIArgs = $argv;
            else $this->arrCLIArgs = array($argv);
        }
    }

    private function GetSelfAddress($nType, $nRelativity)
    {
        $strServerReqURI = '';
        $strFull = '';
        switch ($nType)
        {
            case ADDRTYPE_FILELOCATION:{
                return dirname($this->SERVER('SCRIPT_FILENAME'));
            }break;
            case ADDRTYPE_SCRIPTLOCATION:{
                $strServerReqURI = $this->SERVER('PHP_SELF');
                if (strpos($strServerReqURI, '.php') == strlen($strServerReqURI) - 4){
                    $strServerReqURI = substr($strServerReqURI, 0, strrpos($strServerReqURI, '/'));
                }
            }break;
            case ADDRTYPE_REQUESTURI:{
                $strServerReqURI = $this->SERVER('REQUEST_URI', '/');
                if (strpos($strServerReqURI, '.php') == strlen($strServerReqURI) - 4){
                    $strServerReqURI = substr($strServerReqURI, 0, strrpos($strServerReqURI, '/'));
                }
            }break;
        }

        $strHTTPS = '';
        $strProtocol = '';
        $strPort = '';

        if ($this->nCallingMethod == CALLMETH_HTTP){
            $strHTTPS = empty($_SERVER['HTTPS']) ? '' : (($_SERVER['HTTPS'] == 'on') ? 's' : '');
            $strProtocol = substr(strtolower($this->SERVER('SERVER_PROTOCOL')), 0,
                strpos($this->SERVER('SERVER_PROTOCOL'), '/')) . $strHTTPS;
            $strPort = (($strHTTPS == '' && $this->SERVER('SERVER_PORT') == '80') ||
                ($strHTTPS == 's' && $this->SERVER('SERVER_PORT') == '443'))
                        ? '' : (':' . $this->SERVER('SERVER_PORT'));
        }else $strProtocol = 'cli';

        switch ($nRelativity)
        {
            case ADDRREL_ABSOLUTE:{
                $strFull = $strProtocol . '://' .
                    ($this->nCallingMethod == CALLMETH_HTTP ? $this->SERVER('SERVER_NAME') : 'localhost') .
                    $strPort . $strServerReqURI;
            }break;
            case ADDRREL_RELATIVE:{
                $strFull = $strServerReqURI;
            }break;
        }

        if (strlen($strFull) > 0 && $strFull[strlen($strFull)-1] == '/')
            $strFull = substr($strFull, 0, -1);

        return $strFull;    // no more strtolower here
    }

    private function DetectPlatform()
    {
        $nBrowserType = BRWSR_TYPE_UNKNOWN;
        $strResolution = 'unknown';

        $strUA = strtolower($this->SERVER('HTTP_USER_AGENT'));
        $strAccept = $this->SERVER('HTTP_ACCEPT');
        $strAcceptEncoding = $this->SERVER('HTTP_ACCEPT_ENCODING');
        $strAcceptLanguage = $this->SERVER('HTTP_ACCEPT_LANGUAGE');

        ////////////////////////////////////////////////////////////////////
        // DETECT PLATFORM TYPE

        // detect mobile devices
        if ($nBrowserType == BRWSR_TYPE_UNKNOWN){
            $arrMobileKeywords = array('mobile', 'iphone', 'htc', 'windows ce',
                'midp', 'cldc', 'ppc', 'wap', 'psp', 'playstation portable', 'android');
            $nKeywords = count($arrMobileKeywords);

            for ($i=0; $i<$nKeywords; $i++){
                if (is_int(strpos($strUA, $arrMobileKeywords[$i]))){
                    $nBrowserType = BRWSR_TYPE_MOBILE;
                    $i = $nKeywords + 1;
                }
            }
        }

        // detect bots
        if ($nBrowserType == BRWSR_TYPE_UNKNOWN){
            $arrBotKeywords = array('bot', 'facebook', 'ask.com', 'crawl',
                'krawl', 'spider', 'altavista', 'java', 'clamav', 'clambot',
                'curl/', 'docomo', 'findlinks/', 'flashget', 'godzilla/',
                'libwww-perl', 'archiver', 'spam', 'findlinks/');
            $nKeywords = count($arrBotKeywords);

            for ($i=0; $i<$nKeywords; $i++){
                if (is_int(strpos($strUA, $arrBotKeywords[$i]))){
                    $nBrowserType = BRWSR_TYPE_BOT;
                    $i = $nKeywords + 1;
                }
            }
        }

        if ($nBrowserType == BRWSR_TYPE_UNKNOWN && $strAccept == '*/*' &&
            strlen($strAcceptEncoding) == 0 && strlen($strAcceptLanguage) == 0)
            $nBrowserType = BRWSR_TYPE_BOT;

        // if in CLI, browser does not exist
        if ($this->nCallingMethod == CALLMETH_CLI)
            $nBrowserType = BRWSR_TYPE_CLI;

        // the rest are desktops
        if ($nBrowserType == BRWSR_TYPE_UNKNOWN) $nBrowserType = BRWSR_TYPE_DESKTOP;

        ////////////////////////////////////////////////////////////////////
        // DETECT RESOLUTION

        $arrMatch = array();
        if (preg_match('/;[\t| ]*([0-9]+)x([0-9]+)[\t| ]*;/', $strUA, $arrMatch))
            $strResolution = $arrMatch[1].'x'.$arrMatch[2];

        // DETECT BROWSER CLIENT (InternetExplorer, Firefox, Chrome, Opera, etc.)
        $nBrowserName = BRWSR_NAME_UNKNOWN;
        // example for firefox: Mozilla/5.0 (Windows NT 5.1; rv:17.0) Gecko/20100101 Firefox/17.0
        // example for IE: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)
        // example for Chrome: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31
        // example for Opera: Opera/9.80 (Windows NT 5.1) Presto/2.12.388 Version/12.15
        // TODO: add more browsers here
        if (stripos($strUA, 'Firefox') !== FALSE) $nBrowserName = BRWSR_NAME_FIREFOX;
        if ($nBrowserName == BRWSR_NAME_UNKNOWN && stripos($strUA, 'MSIE') !== FALSE)
            $nBrowserName = BRWSR_NAME_IE;

        if ($nBrowserName == BRWSR_NAME_UNKNOWN && stripos($strUA, 'Chrome') !== FALSE)
            $nBrowserName = BRWSR_NAME_CHROME;
        if ($nBrowserName == BRWSR_NAME_UNKNOWN && stripos($strUA, 'Opera') !== FALSE)
            $nBrowserName = BRWSR_NAME_OPERA;
        if ($nBrowserName == BRWSR_NAME_UNKNOWN && stripos($strUA, 'Safari') !== FALSE &&
            stripos($strUA, 'Chrome') === FALSE)
            $nBrowserName = BRWSR_NAME_SAFARI;

        // detect actual platform
        if (is_int(strpos($strUA, 'android'))){
            $nPlatform = PLATFORM_ANDROID;
        }elseif (is_int(strpos($strUA, 'iphone'))){
            $nPlatform = PLATFORM_IOS;
        }elseif (is_int(strpos($strUA, 'windows ce'))){
            $nPlatform = PLATFORM_WINDOWSPHONE;
        }elseif (is_int(strpos($strUA, 'playstation portable')) || is_int(strpos($strUA, 'psp'))){
            $nPlatform = PLATFORM_SONYPLAYSTATION;
        }elseif ($nBrowserType == BRWSR_TYPE_MOBILE){
            $nPlatform = PLATFORM_OTHERMOBILE;
        }else $nPlatform = PLATFORM_PC;

        $this->nBrowserType = $nBrowserType;
        $this->nBrowserTypeReal = $nBrowserType;
        $this->strUserScreenResolution = $strResolution;
        $this->nBrowserName = $nBrowserName;
        $this->nHardwareDevice = $nPlatform;
    }

    function Init()
    {
        // other stuff
        if (function_exists('getallheaders'))
            $this->arrHTTPHeaders = @getallheaders();

        $this->strInputStream = file_get_contents('php://input');
        $this->strIPAddress = $this->SERVER('REMOTE_ADDR');
        $this->strUserAgent = $this->SERVER('HTTP_USER_AGENT');
        $this->strHTTPAccept = $this->SERVER('HTTP_ACCEPT');
        $this->strHTTPAcceptCharset = $this->SERVER('HTTP_ACCEPT_CHARSET');
        $this->strHTTPAcceptEncoding = $this->SERVER('HTTP_ACCEPT_ENCODING');
        $this->strHTTPAcceptLanguage = $this->SERVER('HTTP_ACCEPT_LANGUAGE');
        $this->strReferer = $this->SERVER('HTTP_REFERER');

        // detect how this application was launched (called)
        $this->DetectCallingType();

        if ($this->nCallingMethod == CALLMETH_CLI)
            $this->strHTTPAcceptLanguage = $this->SERVER('LANG');

        // determine URL type (clean or parametric, by mod_rewrite)
        $this->DetectURLType();

        // determine session type
        $this->DetectSessionType();

        // determine website location and requested address
        $this->strScriptFilepath = $this->GetSelfAddress(ADDRTYPE_FILELOCATION, 0);
        $this->strBareURLAbsolute = $this->GetSelfAddress(ADDRTYPE_SCRIPTLOCATION, ADDRREL_ABSOLUTE);
        $this->strBareURLRelative = $this->GetSelfAddress(ADDRTYPE_SCRIPTLOCATION, ADDRREL_RELATIVE);
        $this->strRequestURLAbsolute = $this->GetSelfAddress(ADDRTYPE_REQUESTURI, ADDRREL_ABSOLUTE);
        $this->strRequestURLRelative = $this->GetSelfAddress(ADDRTYPE_REQUESTURI, ADDRREL_RELATIVE);

        // determine platform
        $this->DetectPlatform();
    }

    function UpdateExecFinishTime()
    {
        $this->nExecFinishTime = microtime(true);
        $_SESSION['time_last_gen'] = $this->nExecFinishTime - $this->nExecStartTime;
    }

    function GetStartTime()
    {
        return $this->nExecStartTime;
    }

    function GetExecutionTime()
    {
        return ($this->nExecFinishTime > 0 ?
            $this->nExecFinishTime : microtime(true)) - $this->nExecStartTime;
    }

    // some convenience functions
    function POST($strParam, $strDefault = '')
    {
        return (isset($_POST[$strParam]) ? $_POST[$strParam] : $strDefault);
    }

    function GET($strParam, $strDefault = '')
    {
        return (isset($_GET[$strParam]) ? $_GET[$strParam] : $strDefault);
    }

    function COOKIE($strParam, $strDefault = '')
    {
        return (isset($_COOKIE[$strParam]) ? $_COOKIE[$strParam] : $strDefault);
    }

    function SESSION($strParam, $strDefault = '')
    {
        return (isset($_SESSION[$strParam]) ? $_SESSION[$strParam] : $strDefault);
    }

    function SERVER($strParam, $strDefault = '')
    {
        return (isset($_SERVER[$strParam]) ? $_SERVER[$strParam] : $strDefault);
    }

    // and now some functions that actually retrieve something
    function GetScriptFilepath()
    {
        return $this->strScriptFilepath;
    }

    function GetAppURLAbsolute()
    {
        return $this->strBareURLAbsolute;
    }

    function GetAppURLRelative()
    {
        return $this->strBareURLRelative;
    }

    function GetRequestURLAbsolute()
    {
        return $this->strRequestURLAbsolute;
    }

    function GetRequestURLRelative()
    {
        return $this->strRequestURLRelative;
    }

    function GetAppURLType()
    {
        return $this->nRequestType;
    }

    function GetSessionType()
    {
        return $this->nSessionType;
    }

    function GetBrowserType()
    {
        return $this->nBrowserType;
    }

    function GetBrowserName($bAsString = false)
    {
        if ($bAsString){
            switch ($this->nBrowserName)
            {
                case BRWSR_NAME_CHROME: return 'Chrome';
                case BRWSR_NAME_FIREFOX: return 'Firefox';
                case BRWSR_NAME_IE: return 'Internet Explorer';
                case BRWSR_NAME_OPERA: return 'Opera';
                case BRWSR_NAME_SAFARI: return 'Safari';
                case BRWSR_NAME_UNKNOWN: return '?';
            }
        }else return $this->nBrowserName;
    }

    function ForceBrowserType($nType)
    {
        $this->nBrowserType = $nType;
    }

    function GetBrowserTypeReal()
    {
        return $this->nBrowserTypeReal;
    }

    function GetBrowserResolution()
    {
        return $this->strUserScreenResolution;
    }

    function GetBrowserHardware()
    {
        return $this->nHardwareDevice;
    }

    function GetCallingMethod()
    {
        return $this->nCallingMethod;
    }

    function GetCLIArguments()
    {
        return $this->arrCLIArgs;
    }

    function GetQWFVersion()
    {
        return QUICKWEBFRAME_VERSION;
    }

    function GetQWFVersionStage()
    {
        return 'alpha';
    }

    function GetQWFReleaseDate()
    {
        return '30/11/2020';
    }
}

////////////////////////////////////////////////////////////////////////////////
// some convenience functions
function POST($strParam, $strDefault = '')
{
    return (isset($_POST[$strParam]) ? $_POST[$strParam] : $strDefault);
}

function GET($strParam, $strDefault = '')
{
    return (isset($_GET[$strParam]) ? $_GET[$strParam] : $strDefault);
}

function COOKIE($strParam, $strDefault = '')
{
    return (isset($_COOKIE[$strParam]) ? $_COOKIE[$strParam] : $strDefault);
}

function SESSION($strParam, $strDefault = '')
{
    return (isset($_SESSION[$strParam]) ? $_SESSION[$strParam] : $strDefault);
}

function SERVER($strParam, $strDefault = '')
{
    return (isset($_SERVER[$strParam]) ? $_SERVER[$strParam] : $strDefault);
}

// a few global functions that help
function strcount($strHaystack, $strNeedle)
{
    $nCnt = 0;
    $nLen = strlen($strHaystack);
    for ($i=0; $i<$nLen; $i++) if ($strHaystack[$i] == $strNeedle[0]) $nCnt++;
    return $nCnt;
}

function array_merge_all(&$Arr1, $Arr2)
{
    foreach($Arr2 as $key => $Value)
    {
        if (array_key_exists($key, $Arr1) && is_array($Value))
            $Arr1[$key] = array_merge_all($Arr1[$key], $Arr2[$key]);
        else $Arr1[$key] = $Value;
    }

    return $Arr1;
}

function trimexplode($strDelim, $strStr)
{
    return array_map('trim', explode($strDelim, $strStr));
}

function GetArrayAsDebugArray($arr, &$arrDebug, $pcPrefix='')
{
    if (is_array($arr)){
        if (is_array($arr) && empty($arr)){
            $arrDebug[]=array(
                'key' => $pcPrefix.'[]',
                'val' => 'empty array'
            );
        }else{
            foreach ($arr as $pcKey => $mxVal){
                if (is_array($mxVal)){
                    GetArrayAsDebugArray($mxVal, $arrDebug, $pcPrefix.'[\''.$pcKey.'\']');
                }else{
                    $arrDebug[]=array(
                        'key' => $pcPrefix.'[\''.$pcKey.'\']',
                        'val' => ($mxVal===true ? 'true' :
                            ($mxVal===false ? 'false' :
                                (is_array($mxVal) && empty($mxVal) ? '' : $mxVal)))
                    );
                }
            }
        }
    }
}

function PrintHTMLDebug($mxSomething)
{
    echo '<div style="font-family: courier-new, monospace; font-size: 16px;">' .
        str_replace(array("\r", ' ', "\n"), array('', '&nbsp;', '<br />'),
            print_r($mxSomething, true)) . '</div>';
}

function DebugTranslateIntConstants($n, $arrCts)
{
    return (isset($arrCts[$n]) ? $arrCts[$n] : 'n/a');
}

} // endif defined SYSCAP_CORE

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;