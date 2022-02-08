<?php
////////////////////////////////////////////////////////////////////////////////
// Part of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.
// -- For history see the end of this file.

////////////////////////////////////////////////////////////////////////////////
// This file contains dummy classes for mandatory system libraries; if each of
// those is not included, for various reasons, this one will provide generic
// classes so that the application won't crash later and you won't have to
// always check for their existance

if (!defined('SYSCAP_DUMMY')){
// this tells the whole framework that this system file has been included
define('SYSCAP_DUMMY', true);

if (!class_exists('CQSyslog')){
// class CQSyslog - class for logging errors, debug messages and other stuff

define('LOGTYPE_FILE',          0);
define('LOGTYPE_DATABASE',      1);

class CQSyslog
{
    private static $hInstance = NULL;
    
    function __construct(){}
    
    static function GetInstance()
    {
        if (self::$hInstance === NULL) self::$hInstance = new CQSyslog();
        
        return self::$hInstance;
    }
    
    function Init(&$kDatabase){}
    function Log($strLocation, $mxContent, $bReverseLogging = false){}
    function Write($mxContent){}
    function Debug($strWhat, $strContent, $bPostDevelop = false){}
}

// System Error Handling
function SysErrorHandler($nErrNo, $strErr, $strErrFile, $strErrLine){ return true; }
set_error_handler('SysErrorHandler');

// System Exception Handling
function SysExceptionHandler($kException){}
set_exception_handler('SysExceptionHandler');

} // endif class CQSyslog exists


if (!class_exists('CQSiteConfig')){
class CQSiteConfig
{
    private $arrData;
    
    function __construct()
    {
        $this->arrData = array();
        
        // set default values for some mandatory configurations
        $this->arrData['default-language-code']=array(
            'value' => 'en',
            'modified' => false
        );
        
        $this->arrData['theme']=array(
            'value' => 'thunderstorm',
            'modified' => false
        );
        
        $this->arrData['force-desktop']=array(
            'value' => true,
            'modified' => false
        );
    }
    
    function Init(&$kDatabase){}
    
    function Get($strStr, $strDefault='')
    {
        if (isset($this->arrData[$strStr])) return $this->arrData[$strStr]['value'];
        else return $strDefault;
    }
    
    function Set($strStr, $mxValue)
    {
        $this->arrData[$strStr]=array(
            'value' => $mxValue,
            'modified' => true
        );
    }
    
    function Remove($strStr)
    {
        if (isset($this->arrData[$strStr])) unset($this->arrData[$strStr]);
        
        return true;
    }
    
    function CommitChanges()
    {
        return false;
    }
}
} // endif class CQSiteConfig exists

if (!class_exists('CQDatabaseWrapper')){
class CQDatabaseWrapper
{
    // constructors
    function __construct(){}
    function __destruct(){}
    
    // functions
    function Init($pcTypeofDB, $pcAddr, $pcName, $pcUsr, $pcPass){}
    
    function Open(){ return NULL; }
    function Close(){}
    function RunQuery($pcQuery){ return false; }
    function RunMultiQuery($pcQuery){ return false; }
    function RunQuickDelete($pcFrom, $arrWhere){ return false; }
    function RunQuickUpdate($pcInto, $mxColumnList, $arrData, $arrWhere){ return false; }
    function RunQuickInsert($pcInto, $mxColumnList, $arrData){ return false; }
    function RunQuickSelect($mxWhat, $pcFrom, $arrWhere=NULL, $mxOrderBy=NULL, $mxLimits=NULL){ return NULL; }
    function RunQuickCount($pcFrom, $arrWhere=NULL){ return NULL; }
    function GetNextAutoincrement($pcTable){ return NULL; }
    function CleanString($pcStr){ return NULL; }
    function ChangeDatabase($pcNewDB){}
    function GetType(){ return NULL; }
    function GetError(){ return ''; }
    function GetLastQuery(){ return 'n/a'; }
}
} // endif class CQDatabaseWrapper exists

if (!class_exists('CQAuth')){
class CQAuth
{
    function __construct(){}
    
    // functions
    function Init(&$kDatabase, &$kConfig){ return true; }
    function IsAuthenticated(){ return false; }
    
    function GetNewSalt()
    {
        return '01234567890123456789012345678901';
    }
    
    function GetPasswordHash($strPass, $strUser='', $strSalt=''){ return ''; }
    function DoCompareHashes($strStrLeft, $strStrRight){ return false; }
    function LogIn($strUser, $strPass){ return $this->LogOut(); }
    function LogOut(){ return -1; }
    function CheckActionNeeded(){}
    function ChangePassword($strUser, $strOldPass, $strNewPass){ return -1; }
    function ResetPassword($nUser, $strNewPass){ return -1; }

    function HasAdvancedPerms($arrFlags, $mxLocation, $strAdvSection, $strAdvPerm=NULL)
    { return true; }
    
    function HasPermissions($arrFlags, $mxLocation){ return true; }
    function GetUser(){ return 'public'; }
    function GetUserId(){ return -1; }
    function GetUserIdFromName($strName){ return -6; }
    function IsMaster(){ return true; }
    function IsPublicUser(){ return true; }
    function AddNewUser($strUser, $strNewPass, &$nNewId){ return -4; }
    function DeleteUser($nIdx){ return -4; }
    
    function CheckRequestPermission($strReq, $nTolerance, $strKey)
    {
        $arrHashes = array();
        $nTStamp = time() - $nTolerance;
        
        for ($i = $nTStamp; $i <= ($nTStamp + (2 * $nTolerance)); $i++)
            $arrHashes[] = strtoupper(md5(gmdate('YmdHis', $i) . $strKey));
        
        if (in_array($strReq, $arrHashes)) return true;
        else return false;
    }
    
    function CreateRequestPermission($strKey)
    {
        return strtoupper(md5(gmdate('YmdHis') . $strKey));
    }
    
    function GenerateHMAC($strMessage, $strKey, $nType){ return NULL; }
    function GetCSRFCode(){ return '01234567890123456789012345678901'; }
    function CheckCSRF($strCode)
    {
        return '01234567890123456789012345678901' == $strCode;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // functions for setting and getting elegant permission variables
    function LoadUserPermissions($nIdUsr){ return -1; }
    private function GetSinglePermission(&$arrPermSng){}
    function GetStructuredPermissions(&$arrPerms){}
    private function GeneratePermissionList(&$arrList, $arrElement){}
    function SetStructuredPermissions($arrPerms){}
}
} // endif class CQAuth exists

} // endif defined DUMMY

////////////////////////////////////////////////////////////////////////////////
// History:
//  -- 21/06/2019 - v1 created;